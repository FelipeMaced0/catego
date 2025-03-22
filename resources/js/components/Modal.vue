<script setup lang="ts">
import { CategoriaItem } from '@/types';
import axios from 'axios';
import { inject } from 'vue';
import { useToast } from 'vue-toast-notification';
const $toast = useToast();

const props = defineProps<{
    exibir: Boolean
    modo: string
    funcaoSubmit?: () => {},
    categoria: CategoriaItem,
    categoriaPai?: CategoriaItem
}>();


const mudarExibirModal = inject('mudarExibirModal');
const getResults = inject('getResults');

const cadastrarCategoria = async () => {
    try {
        const response = await axios.post('/api/categoria', props.categoria);

        if (response.status >= 200 && response.status < 300) {
            $toast.success('Categoria cadastrada!');
        }
        //Adicionando a subcategoria ao pai assim que ela é criada
        //sem fazer uma busca completa no banco de dados
        //
        if(props?.categoriaPai != undefined){
            props?.categoriaPai?.sub_categorias?.push(response.data);
        }else{
            getResults();
        }

        console.log(props.categoriaPai);
        
    } catch (e) {
        $toast.error('Erro ao realizar a requisição!');
    }
}

const atualizarCategoria = async () => {
    try {


        const response = await axios.put(`/api/categoria/${props.categoria?.id}`, props.categoria);
        if (response.status >= 200 && response.status < 300) {
            $toast.success('Categoria atualizada!');
        }
    } catch (e) {
        $toast.error('Erro ao realizar a requisição!');
    }

}

const submit = async () => {
    if (props.modo == 'cadastrar') {
        cadastrarCategoria();
    } else if (props.modo == 'atualizar') {
        atualizarCategoria();
    }
}



</script>

<template>
    
        <div :class="exibir?'':'hidden'" class="w-full p-4 min-w-[300px] max-w-[500px] max-h-full">
            <!-- Modal content -->
            <div class="bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <!-- Modal header -->
                <div
                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ modo == 'cadastrar' ? 'Cadastrar' : 'Atualizar' }} Categoria {{ (modo == 'cadastrar' && props.categoriaPai != undefined) ? 'em ' + props.categoriaPai.nome : '' }}
                    </h3>
                    <button v-on:click="mudarExibirModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <div class="flex flex-row justify-between w-[400px]">
                        <input type="text" class="w-24" placeholder="Nome" v-model="categoria.nome">
                        <input type="text" class="w-64 min-w-34" placeholder="Descrição" v-model="categoria.descricao">
                    </div>

                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button data-modal-hide="default-modal" type="button" v-on:click="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        {{ modo == 'cadastrar' ? 'Cadastrar' : 'Atualizar' }}</button>
                    <button v-on:click="mudarExibirModal"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancelar</button>
                </div>
            </div>
    </div>

</template>