<script setup lang="ts">
import { CategoriaItem, ItemCategoriaType } from '@/types';
import { provide, ref } from 'vue';
import CIcon from '@coreui/icons-vue';
import { cilPlus, cilMagnifyingGlass } from '@coreui/icons';

import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import { TailwindPagination } from 'laravel-vue-pagination';
import Categoria from './Categoria.vue';
import Modal from './Modal.vue';
import axios from 'axios';


const timer = ref();
const categorias = ref({ data: [] });
const categoriasFiltradas = ref({ query: '', result: [] });
const $toast = useToast();

export type deletarCategoriaType = typeof deletarCategoria;
export type mudarModoModalType = typeof mudarModoModal;
export type mudarExibirModalType = typeof mudarExibirModal;
export type mudarCategoriaType = typeof mudarCategoria;
export type mudarCategoriaPaiType = typeof mudarCategoriaPai;

const getResults = async (page = 1) => {
    try {
        const response = await fetch(`/api/categorias?page=${page}`);
        categorias.value = await response.json();
    } catch (e) {
        console.log('Error');
    }

}
getResults();


const exibirModal = ref(false);
const modo = ref("cadastrar");
const categoriaFilho = ref<CategoriaItem>({ nome: '', descricao: '' });
const categoriaPai = ref<CategoriaItem | undefined>({ nome: '', descricao: '' });

const mudarModoModal = (tipo: "cadastrar" | "atualizar") => {
    modo.value = tipo;
}

const mudarExibirModal = () => {
    exibirModal.value = !exibirModal.value;
}

const mudarCategoria = (categoria: CategoriaItem) => {
    categoriaFilho.value = categoria;
}

const mudarCategoriaPai = (categoria: CategoriaItem | undefined) => {
    categoriaPai.value = categoria;
}




const deletarCategoria = async (id: number) => {
    try {


        const response = await axios.delete(`/api/categoria/${id}`);
        if (response.status >= 200 && response.status < 300) {
            $toast.success(response.data.message);
        }


        categorias.value.data = removerObjetoPorId(categorias.value.data, id);
    } catch (e) {
        $toast.error('Erro ao realizar a requisição!');
    }
}

provide('deletarCategoria', deletarCategoria);
provide('mudarExibirModal', mudarExibirModal);
provide('mudarCategoria', mudarCategoria);
provide('mudarModoModal', mudarModoModal);
provide('mudarCategoriaPai', mudarCategoriaPai);
provide('getResults', getResults);

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

const textSearch = (e) => {
    clearTimeout(timer.value)

    timer.value = setTimeout(() => {
        categoriasFiltradas.value.result = [];
        filtrarCategorias(categorias.value.data);
    }, 1000)
}


const filtrarCategorias = (conjunto: Array<CategoriaItem>) => {


    if (categoriasFiltradas.value.query != "") {
        conjunto.forEach((elemento: CategoriaItem) => {
            if (elemento.nome.toLowerCase().includes(categoriasFiltradas.value.query.toLowerCase())) {
                categoriasFiltradas.value.result.push(elemento);
            }

            if (elemento.sub_categorias && elemento.sub_categorias.length > 0) {

                filtrarCategorias(elemento.sub_categorias);
            }
        });
    } 
}

const cadastrarCategoriaSemPai = () => {
    mudarCategoriaPai(undefined);
    mudarExibirModal();
}

</script>

<template>
    <div class="flex flex-col md:flex-row w-full min-h-[300px]">
        <div
            class="flex flex-col items-center w-full min-w-[300px] max-w-[500px] border border-gray-500 rounded-sm shadow-lg p-3">
            <div class="w-full min-w-[300px] min-h-fit flex flex-row justify-around px-2 borderborder-green-600">
                <div class="flex flex-row w-46 justify-around items-center  border border-gray-500 rounded-sm my-2">
                    <input type="text" class="h-8 w-38" placeholder="Digite algo..." maxlength="80"
                        v-model="categoriasFiltradas.query" v-on:keydown="textSearch">
                    <CIcon class=" hover:cursor-pointer" :icon="cilMagnifyingGlass" width="25" />
                </div>
                <div class="flex flex-row w-38 justify-around items-center">
                    <div class="hidden md:block text-[12px]">Nova Categoria</div>
                    <CIcon class="text-green-600 hover:cursor-pointer" :icon="cilPlus"
                        v-on:click="cadastrarCategoriaSemPai" width="25" />
                </div>
            </div>
            <ul class="w-full">
                <template
                    v-for="categoria in (categoriasFiltradas.result.length > 0 ? categoriasFiltradas.result : categorias.data)"
                    :key="categoria.id">
                    <Categoria :categoria="categoria" />
                </template>
            </ul>
            <div class="mt-2">

                <TailwindPagination :data="categorias" @pagination-change-page="getResults" />

            </div>
        </div>
        <Modal :exibir="exibirModal" :categoria="categoriaFilho" :categoria-pai="categoriaPai" :modo="modo" />
    </div>
</template>
