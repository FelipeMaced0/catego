<script setup lang="ts">
import { ItemCategoriaType } from '@/types';
import { inject, ref } from 'vue';
import { CIcon } from '@coreui/icons-vue';
import { cilCaretBottom, cilCaretTop, cilPlus, cilTrash, cilPencil } from '@coreui/icons';
import 'vue-toast-notification/dist/theme-sugar.css';
import type { deletarCategoriaType, mudarCategoriaPaiType, mudarCategoriaType, mudarExibirModalType, mudarModoModalType } from './Categorias.vue';

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

</script>

<template>
    <li>
        <div class="flex flex-row justify-between px-2 my-2 w-full borderborder-cyan-300">
            <div class="w-full min-w-40">{{ categoria?.nome }}</div>

            <div class="flex flex-row flex-grow justify-between px-1">
                <div class="w-8 h-8 flex justify-center items-center" v-on:click="exibirModalCadastrar()" >
                    <CIcon class="text-green-600 hover:cursor-pointer" :icon="cilPlus"
                        width="15" />
                </div>
                <div class="w-8 h-8 flex justify-center items-center" v-on:click="deletarCategoria(categoria?.id ?? 0)">
                <CIcon class="text-red-600 hover:cursor-pointer" :icon="cilTrash"
                     width="15" />
                </div>
                <div class="w-8 h-8 flex justify-center items-center" v-on:click="exibirModalAtualizar()">
                <CIcon class="text-blue-600 hover:cursor-pointer" :icon="cilPencil" 
                    width="15" />
                </div>
                <div class="w-8 h-8 flex justify-center items-center" v-on:click="abrirCategoria">
                <CIcon class="hover:cursor-pointer" :icon="mostrarFilhos ? cilCaretTop : cilCaretBottom"
                     width="15" />
                </div>
            </div>

        </div>
        <ul class="ml-5" :class="mostrarFilhos ? '' : 'hidden'">
            <template v-for="sub_categoria in categoria?.sub_categorias" :key="sub_categoria.id">
                <Categoria :categoria="sub_categoria" />
            </template>
        </ul>
    </li>

</template>
