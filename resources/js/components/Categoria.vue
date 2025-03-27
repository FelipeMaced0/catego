<script setup lang="ts">
import { ItemCategoriaType } from '@/types';
import { inject, ref } from 'vue';
import 'vue-toast-notification/dist/theme-sugar.css';
import type { deletarCategoriaType, mudarCategoriaPaiType, mudarCategoriaType, mudarExibirModalType, mudarModoModalType } from '../pages/Home.vue';
import SpeedDial from 'primevue/speeddial';

const props = defineProps<{
    categoria: ItemCategoriaType;
}>();


const mostrarFilhos = ref(false);


const abrirCategoria = () => {
    mostrarFilhos.value = !mostrarFilhos.value;
}



const deletarCategoria: deletarCategoriaType = inject('deletarCategoria') ?? (async (id: number) => { });
const mudarExibirModal: mudarExibirModalType = inject('mudarExibirModal') ?? (() => { });
const mudarCategoria: mudarCategoriaType = inject('mudarCategoria') ?? (() => { });
const mudarModoModal: mudarModoModalType = inject('mudarModoModal') ?? (() => { });
const mudarCategoriaPai: mudarCategoriaPaiType = inject('mudarCategoriaPai') ?? (() => { });


const exibirModalCadastrar = () => {
    mudarModoModal('cadastrar');
    mudarCategoria({ pai_id: props.categoria.id, nome: '', descricao: '' });
    mudarCategoriaPai(props?.categoria);
    mudarExibirModal();
}


const exibirModalAtualizar = () => {
    mudarModoModal('atualizar');
    mudarCategoria(props.categoria);
    mudarExibirModal();
}

const items = ref([
    {
        label: 'Cadastrar',
        icon: 'pi pi-pencil',
        command: () => {
            exibirModalCadastrar()
        }
    },
    {
        label: 'Atualizar',
        icon: 'pi pi-file-edit',
        command: () => {
            exibirModalAtualizar()
        }
    },
    {
        label: 'Delete',
        icon: 'pi pi-trash',
        command: () => {
            deletarCategoria(props.categoria?.id ?? 0)
        }
    }
])
</script>

<template>
    <li class="w-full">
        <div class="w-full flex flex-row justify-between px-2 my-2  borderborder-cyan-300">
            <div class="flex flex-row gap-x-3">
                <div class="w-8 h-8 flex justify-center items-center" v-on:click="abrirCategoria">
                    <i v-if="mostrarFilhos" class="hover:cursor-pointer pi pi-chevron-right"></i>
                    <i v-else class="hover:cursor-pointer pi pi-chevron-down"></i>
                </div>
                <div class="w-full min-w-40 overflow-hidden">{{ categoria?.nome }}</div>
            </div>

            <SpeedDial :model="items" direction="left" :button-props="{ style: { width: '25px', height: '25px', background:'black', border:'black', borderRadius:'15px' } }"/>

        </div>
        <ul class="ml-5" :class="mostrarFilhos ? '' : 'hidden'">
            <template v-for="sub_categoria in categoria?.sub_categorias" :key="sub_categoria.id">
                <Categoria :categoria="sub_categoria" />
            </template>
        </ul>
    </li>

</template>
