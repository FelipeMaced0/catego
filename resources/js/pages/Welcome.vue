<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { TailwindPagination } from 'laravel-vue-pagination';
import { ref, VueElement } from 'vue';
import { CIcon } from '@coreui/icons-vue';
import { cilCaretBottom, cilCaretTop, cilPlus, cilTrash, cilPencil } from '@coreui/icons';
import axios from 'axios';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';

const $toast = useToast();


const laravelData = ref({});
const formCadastro = ref<categoriaInter>({ nome: '', descricao: '' });
const formCadastroPai = ref({});
const showStoreModal = ref(false);

interface categoriaInter {
    id?: Number | null,
    pai_id?: Number | null,
    modo?: 'cadastrar' | 'atualizar'
    nome: String,
    descricao: String
};

interface categoriaRemotoInter {
    id: Number | null | undefined,
    nome: String,
    descricao: String,
    pai_id: Number | null | undefined
};


const getResults = async (page = 1) => {
    const response = await fetch(`/api/categorias?page=${page}&include=subCategorias`);
    laravelData.value = await response.json();
}

getResults();

const mostrarModalCadastro = (categoria: any, modo: 'cadastrar' | 'atualizar') => {
    showStoreModal.value = !showStoreModal.value;
    
    if(modo=='cadastrar'){
        formCadastro.value.pai_id = categoria?.id;
        formCadastro.value.modo = modo;
        formCadastroPai.value = categoria;
    }else{
        formCadastro.value=categoria
    }
    
}

const abrirCategoria = (categoria: any) => {
    categoria.aberta = !categoria.aberta;
}

function removerObjetoPorId(objeto: any, id: Number) {
   
    return objeto.map(categoria => {

        if (categoria.id === id) {
            return null; // Remove o objeto atual
        }

        if (categoria.sub_categorias && categoria.sub_categorias.length > 0) {
            // Percorre os filhos e remove o categoria com o ID especificado
            categoria.sub_categorias = removerObjetoPorId(categoria.sub_categorias, id);
        }

        return categoria;
    }).filter(Boolean);
}


const deletarCategoria = async (categoria: any) => {
    const response = await axios.delete(`/api/categoria/${categoria.id}`);
    if (response.status >= 200 && response.status < 300) {
        $toast.success(response.data.message);
    }

    laravelData.value.data = removerObjetoPorId(laravelData.value.data, categoria.id);
    //getResults();
}

const atualizarCategoria = async () => {
    const response = await axios.put(`/api/categoria/${formCadastro.value.id}`, formCadastro.value);
    if (response.status >= 200 && response.status < 300) {
        $toast.success('Categoria atualizada!');
    }

    getResults();
}

const cadastrarCategoria = async () => {

    const response = await axios.post('/api/categoria', formCadastro.value);
    //Adicionando a subcategoria ao pai assim que ela é criada
    //sem fazer uma busca completa no banco de dados
    //
    if (response.status >= 200 && response.status < 300) {
        $toast.success('Categoria cadastrada!');
    }

    if(formCadastro.value.pai_id == null || formCadastro.value.pai_id == undefined){
        getResults();
    }
    else{
        formCadastroPai.value.sub_categorias.push(response.data);
    }

    
}

const acaoForm = ()=> {
    if(formCadastro.value.modo=='cadastrar'){
        cadastrarCategoria();
    } else{
        atualizarCategoria();
    }
}

</script>

<template>

    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
        <header class="not-has-[nav]:hidden mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]">
                Dashboard
                </Link>
                <template v-else>
                    <Link :href="route('login')"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]">
                    Log in
                    </Link>
                    <Link :href="route('register')"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]">
                    Cadastro
                    </Link>
                </template>
            </nav>
        </header>
        <div
            class="duration-750 starting:opacity-0 flex w-full items-center justify-center opacity-100 transition-opacity lg:grow">
            <main class="flex w-full max-w-[335px] overflow-hidden rounded-lg lg:max-w-4xl lg:flex-row">
                <div>
                    <div class="w-full min-h-fit flex flex-row justify-end px-2 border border-gree-600">
                        Nova Categoria
                        <CIcon class="text-green-600 hover:cursor-pointer" :icon="cilPlus"
                            v-on:click="mostrarModalCadastro(null, 'cadastrar')" width="25" />
                    </div>

                    <ul>
                        <li v-for="categoria in laravelData?.data" :key="categoria.id" class="mt-2">
                            <div class="flex flex-row justify-between px-2 w-68 border border-cyan-300">
                                {{ categoria.nome }}
                                <div class="flex flex-row justify-between px-2 w-24  border border-red-600">
                                    <CIcon class="text-green-600 hover:cursor-pointer" :icon="cilPlus"
                                        v-on:click="mostrarModalCadastro(categoria, 'cadastrar')" width="15" />
                                    <CIcon class="text-red-600 hover:cursor-pointer" :icon="cilTrash"
                                        v-on:click="deletarCategoria(categoria)" width="15" />
                                    <CIcon class="text-blue-600 hover:cursor-pointer" :icon="cilPencil"
                                        v-on:click="mostrarModalCadastro(categoria, 'atualizar')" width="15" />
                                    <CIcon class="hover:cursor-pointer"
                                        :icon="categoria.aberta ? cilCaretTop : cilCaretBottom"
                                        v-on:click="abrirCategoria(categoria)" width="15" />
                                </div>

                            </div>
                            <ul class="ml-5" :class="categoria.aberta ? '' : 'hidden'">
                                <li v-for="subCategoria in categoria.sub_categorias" :key="subCategoria.id">
                                    {{ subCategoria.nome }}
                                </li>
                            </ul>
                        </li>
                    </ul>

                    <TailwindPagination :data="laravelData" @pagination-change-page="getResults" />
                </div>
                <div id="default-modal" tabindex="-1" aria-hidden="true" :class="showStoreModal ? '' : 'hidden'"
                    class="overflow-y-auto overflow-x-hidden absolute top-10  left-[540px] z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-2xl max-h-full">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                            <!-- Modal header -->
                            <div
                                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    {{formCadastro.modo=='cadastrar' ? 'Cadastrar':'Atualizar'}} Categoria
                                </h3>
                                <button v-on:click="mostrarModalCadastro"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                </button>
                            </div>
                            <!-- Modal body -->
                            <div class="p-4 md:p-5 space-y-4">
                                <div class="flex flex-row justify-around w-[540px]">
                                    <input type="text" placeholder="Nome" v-model="formCadastro.nome">
                                    <input type="text" placeholder="Descrição" v-model="formCadastro.descricao">
                                </div>

                            </div>
                            <!-- Modal footer -->
                            <div
                                class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <button data-modal-hide="default-modal" type="button" v-on:click="acaoForm"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    {{formCadastro.modo=='cadastrar' ? 'Cadastrar':'Atualizar'}}</button>
                                <button v-on:click="mostrarModalCadastro"
                                    class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>

        <!-- Main modal -->

    </div>
</template>
