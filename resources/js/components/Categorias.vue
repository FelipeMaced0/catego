<script setup lang="ts">
import { inject, ref } from 'vue';
import Categoria from './Categoria.vue';
import { CategoriaItem, PaginacaoType } from '@/types';
import Paginator, { PageState } from 'primevue/paginator';
import type {getResultsType} from '../pages/Home.vue';

const props = defineProps<{ categorias: PaginacaoType, categoriasFiltradas: Array<CategoriaItem> }>();
const getResults:getResultsType|Function = inject('getResults') ?? (()=>{});

const onPage = (state: PageState) => {
    getResults(state.page + 1, state.rows);
}
</script>

<template>

    <div class="flex flex-col items-center justify-center w-full min-w-[300px] max-w-[500px] p-3">

        <ul class="w-full flex flex-col gap-y-4 max-h-[400px] overflow-scroll">
            <template
                v-for="categoria in (props?.categoriasFiltradas?.length > 0 ? props.categoriasFiltradas : props?.categorias?.data)"
                :key="categoria.id">
                <Categoria :categoria="categoria" />
            </template>
        </ul>
        <div class="mt-2">
            <Paginator :template="{
                '640px': 'PrevPageLink CurrentPageReport NextPageLink',
                '960px': 'FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink',
                '1300px': 'FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink',
                default: 'FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink JumpToPageDropdown JumpToPageInput'
            }" :rows="categorias.per_page" v-on:page="onPage" :totalRecords="categorias.total"
                :rowsPerPageOptions="[5, 10, 15]" ></Paginator>
        </div>
    </div>


</template>
