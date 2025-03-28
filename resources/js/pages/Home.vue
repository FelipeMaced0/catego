<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Categorias from '@/components/Categorias.vue';
import Nav from '@/components/Nav.vue';
import BotaoFlutuante from '@/components/BotaoFlutuante.vue';
import { CategoriaItem, PaginacaoType } from '@/types';
import { provide, ref } from 'vue';
import { useToast } from 'primevue/usetoast';
import axios from 'axios';
import Dialog from 'primevue/dialog';
import Toast from 'primevue/toast';


const toast = useToast();

const categorias = ref<PaginacaoType>({ data: [] });
const categoriasFiltradas = ref<Array<CategoriaItem>>([]);

const categoria = ref<CategoriaItem>({ nome: '', descricao: '' });
const categoriaPai = ref<CategoriaItem | undefined>({ nome: '', descricao: '' });


const modo = ref("cadastrar");
const visible = ref(false);


const mudarExibirModal = () => {
    visible.value = !visible.value;
}

const cadastrarCategoriaSemPai = () => {
    modo.value = 'cadastrar';
    categoriaPai.value = undefined;
    categoria.value = { nome: '', descricao: '' };
    visible.value = true;
}

const getResults = async (page = 1, perPage = 5) => {
    try {
        const response = await fetch(`/api/categorias?page=${page}&perPage=${perPage}`);
        categorias.value = await response.json();
    } catch (e) {
        console.log('Error');
    }

}
getResults();


const cadastrarCategoria = async () => {
    try {
        const response = await axios.post('/api/categoria', categoria.value);

        if (response.status >= 200 && response.status < 300) {
            toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Categoria cadastrada!', life: 3000 });
        }
        //Adicionando a subcategoria ao pai assim que ela é criada
        //sem fazer uma busca completa no banco de dados
        //
        if (categoriaPai.value != undefined) {
            categoriaPai?.value?.sub_categorias?.push(response.data);
        } else {
            getResults();
        }

    } catch (e) {
        toast.add({ severity: 'error', summary: 'Erro', detail: 'Erro ao realizar a requisição!', life: 3000 });
    }
}

const atualizarCategoria = async () => {
    try {


        const response = await axios.put(`/api/categoria/${categoria?.value.id}`, categoria.value);
        if (response.status >= 200 && response.status < 300) {
            toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Categoria atualizada!', life: 3000 });
        }
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Erro', detail: 'Erro ao atualizar!', life: 3000 });
    }

}


const submit = async () => {

    if (modo.value == 'cadastrar') {
        cadastrarCategoria();
    } else if (modo.value == 'atualizar') {
        atualizarCategoria();
    }

    visible.value = false;
}

function removerObjetoPorId(objeto: any, id: Number) {

    return objeto.map((categoria: CategoriaItem) => {

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

const deletarCategoria = async (id: number) => {
    try {


        const response = await axios.delete(`/api/categoria/${id}`);
        if (response.status >= 200 && response.status < 300) {
            toast.add({ severity: 'success', summary: 'Sucesso', detail: response.data.message });
        }


        categorias.value.data = removerObjetoPorId(categorias.value.data, id);
        visible.value = false;
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Erro', detail: 'Erro ao realizar a requisição!' });
    }
}




const mudarModoModal = (tipo: "cadastrar" | "atualizar") => {
    modo.value = tipo;
}


const mudarCategoria = (cat: CategoriaItem) => {
    categoria.value = cat;
}

const mudarCategoriaPai = (cat: CategoriaItem | undefined) => {
    categoriaPai.value = cat;
}


provide('deletarCategoria', deletarCategoria);
provide('mudarCategoria', mudarCategoria);
provide('mudarModoModal', mudarModoModal);
provide('mudarCategoriaPai', mudarCategoriaPai);
provide('mudarExibirModal', mudarExibirModal);
provide('getResults', getResults);
provide('categoriasFiltradas', categoriasFiltradas);

export type deletarCategoriaType = typeof deletarCategoria;
export type mudarModoModalType = typeof mudarModoModal;
export type mudarCategoriaType = typeof mudarCategoria;
export type mudarCategoriaPaiType = typeof mudarCategoriaPai;
export type getResultsType = typeof getResults;
export type mudarExibirModalType = typeof mudarExibirModal;

</script>

<template>

    <Head title="Página Inicial">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC]">
        <header class="mb-6 w-full bg-blue-600">
            <Nav :categorias="categorias.data" />
        </header>
        <div class="flex flex-row w-full items-center justify-center lg:grow">
            <Categorias :categorias="categorias" :categorias-filtradas="categoriasFiltradas" />
            <BotaoFlutuante v-on:click="cadastrarCategoriaSemPai" />
            <Dialog v-model:visible="visible" modal
                :header="modo == 'cadastrar' ? 'Cadastrar Sub-Categoria' : 'Atualizar Categoria'"
                :style="{ width: '25rem' }">
                <div class="p-4 md:p-5 space-y-4">
                    <div class="flex flex-col justify-between w-full gap-y-5">
                        <input type="text" class="w-full h-8" placeholder="Nome" v-model="categoria.nome">
                        <input type="text" class="w-full h-8" placeholder="Descrição" v-model="categoria.descricao">
                    </div>

                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b ">
                    <button data-modal-hide="default-modal" type="button" v-on:click="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        {{ modo == 'cadastrar' ? 'Cadastrar' : 'Atualizar' }}</button>
                    <button v-on:click="visible = false"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancelar</button>
                </div>
            </Dialog>
        </div>
    </div>
    <Toast />
</template>
