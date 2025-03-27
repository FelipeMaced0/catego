<script setup lang="ts">
import { CategoriaItem } from '@/types';
import InputText from 'primevue/inputtext';
import { inject, Ref, ref } from 'vue';


const props = defineProps<{categorias:Array<CategoriaItem>}>();
const timer = ref();


const query = ref('');
const categoriasFiltradas:Ref<Array<CategoriaItem>> = inject('categoriasFiltradas');
    
const textSearch = () => {
    clearTimeout(timer.value)
    timer.value = setTimeout(() => {
        categoriasFiltradas.value = [];
        filtrarCategorias(props.categorias);
    }, 500)
}


const filtrarCategorias = (cats: Array<CategoriaItem>) => {
    if (query.value != "") {
        cats?.forEach((categoria: CategoriaItem) => {
            if (categoria.nome.toLowerCase().includes(query.value.toLowerCase())) {
                categoriasFiltradas.value.push(categoria);
            }

            if (categoria.sub_categorias && categoria.sub_categorias.length > 0) {

                filtrarCategorias(categoria.sub_categorias);
            }
        });
    } 
}
</script>
<template>
    <nav class="w-full relative flex flex-row items-center min-h-16">
        <div class="absolute flex flex-row w-full justify-start">
            <div class="flex flex-row gap-x-3 items-center">
                <img class="w-10 h-10" src="https://i.ibb.co/TqhwNK9b/catego-high-resolution-logo-removebg-preview.png"
                    alt="" srcset="">
                <span class="hidden md:block text-white font-extrabold">
                    Categô
                </span>
            </div>
        </div>
        <div class="absolute flex flex-row w-full justify-center">
            <InputText type="text" v-model="query" v-on:keydown="textSearch" class="w-full max-w-[250px] md:max-w-[500px] lg:max-w-[650px] max-h-10" />
        </div>
    </nav>
</template>