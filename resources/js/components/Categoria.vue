<script setup lang="ts">
import { ItemCategoriaType } from '@/types';
import { inject, ref } from 'vue';
import { CIcon } from '@coreui/icons-vue';
import { cilCaretBottom, cilCaretTop, cilPlus, cilTrash, cilPencil } from '@coreui/icons';
import 'vue-toast-notification/dist/theme-sugar.css';
import type {deletarCategoriaType, mudarCategoriaPaiType, mudarCategoriaType, mudarExibirModalType, mudarModoModalType} from './Categorias.vue';

const props = defineProps<{
    categoria: ItemCategoriaType;
}>();


const mostrarFilhos = ref(false);


const abrirCategoria = () => {
    mostrarFilhos.value = !mostrarFilhos.value;
}



const deletarCategoria:deletarCategoriaType = inject('deletarCategoria') ?? (async (id: number) => {});
const mudarExibirModal:mudarExibirModalType = inject('mudarExibirModal') ?? (() => {});
const mudarCategoria:mudarCategoriaType = inject('mudarCategoria') ?? (() => {});
const mudarModoModal:mudarModoModalType = inject('mudarModoModal') ?? (() => {});
const mudarCategoriaPai: mudarCategoriaPaiType = inject('mudarCategoriaPai') ?? (() => {});

const exibirModalCadastrar =  () => {
    mudarModoModal('cadastrar');
    mudarCategoria({pai_id:props.categoria.id, nome:'', descricao:''});
    mudarCategoriaPai(props?.categoria);
    mudarExibirModal();
}


const exibirModalAtualizar =  () => {
    mudarModoModal('atualizar');
    mudarCategoria(props.categoria);
    mudarExibirModal();
}

</script>

<template>
    <li>
        <div class="flex flex-row justify-between px-2 w-68 borderborder-cyan-300">
            <div class="">{{ categoria?.nome }}</div>
            
            <div class="flex flex-row justify-between px-2 w-24  borderborder-red-600">
                <CIcon class="text-green-600 hover:cursor-pointer" :icon="cilPlus" v-on:click="exibirModalCadastrar()" width="15" />
                <CIcon class="text-red-600 hover:cursor-pointer" :icon="cilTrash" v-on:click="deletarCategoria(categoria?.id ?? 0)" width="15" />
                <CIcon class="text-blue-600 hover:cursor-pointer" :icon="cilPencil" v-on:click="exibirModalAtualizar()" width="15" />
                <CIcon class="hover:cursor-pointer" :icon="mostrarFilhos ? cilCaretTop : cilCaretBottom"
                    v-on:click="abrirCategoria" width="15" />
            </div>

        </div>
        <ul class="ml-5" :class="mostrarFilhos ? '' : 'hidden'">
            <template v-for="sub_categoria in categoria?.sub_categorias" :key="sub_categoria.id">
                <Categoria :categoria="sub_categoria" />
            </template>
        </ul>
    </li>

</template>
