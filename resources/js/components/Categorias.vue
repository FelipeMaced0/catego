<script setup lang="ts">
import { CategoriaItem, ItemCategoriaType } from '@/types';
import { provide, ref } from 'vue';
import CIcon from '@coreui/icons-vue';
import { cilPlus, cilMagnifyingGlass } from '@coreui/icons';

import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';

import Categoria from './Categoria.vue';
import Modal from './Modal.vue';
import axios from 'axios';


const categorias = ref({data:[]});
const categoriasFiltradas = ref({ query: '', result: [] });
const $toast = useToast();

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
const categoriaINT = ref<CategoriaItem>({ nome: '', descricao: '' });
const categoriaPai = ref<CategoriaItem>({ nome: '', descricao: '' });

const mudarModoModal = (tipo: "cadastrar" | "atualizar") => {
    modo.value = tipo;
}

const mudarExibirModal = () => {
    exibirModal.value = !exibirModal.value;
}

const mudarCategoria = (categoria: CategoriaItem) => {
    categoriaINT.value = categoria;
}

const mudarCategoriaPai = (categoria: CategoriaItem) => {
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

const filtrarCategorias = () => {
    categoriasFiltradas.value.result = categorias?.value?.data?.filter((categoria: CategoriaItem) => categoria.nome.toLowerCase().includes(categoriasFiltradas.value.query.toLowerCase()));
}

const cadastrarCategoriaSemPai = () => {
    mudarCategoriaPai({ pai_id: undefined, id: undefined, nome: '', descricao: '' });
    mudarExibirModal();
}

</script>

<template>
    <div class="flex flex-col items-center w-[500px] border border-gray-500 rounded-sm shadow-lg p-3">
        <div class="w-full min-h-fit flex flex-row justify-around px-2 borderborder-green-600">
            <div class="flex flex-row w-46 justify-around items-center  border border-gray-500 rounded-sm my-2">
                <input type="text" class="h-8 w-38" placeholder="Digite algo..." maxlength="80"
                    v-model="categoriasFiltradas.query" v-on:keydown="filtrarCategorias">
                <CIcon class=" hover:cursor-pointer" :icon="cilMagnifyingGlass" width="25" />
            </div>
            <div class="flex flex-row w-38 justify-around items-center">
                Nova Categoria
                <CIcon class="text-green-600 hover:cursor-pointer" :icon="cilPlus" v-on:click="cadastrarCategoriaSemPai"
                    width="25" />
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
            <!--
            <TailwindPagination :data="categorias" @pagination-change-page="getResults" />
            -->
        </div>
        <Modal :exibir="exibirModal" :categoria="categoriaINT" :categoria-pai="categoriaPai" :modo="modo" />
    </div>
</template>
