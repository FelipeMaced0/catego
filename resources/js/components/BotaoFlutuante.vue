<script setup lang="ts">
 import { cilPlus } from '@coreui/icons';
 import CIcon from '@coreui/icons-vue';
 
</script>
<template>
    <div @click="$emit('click')" class="absolute bottom-10 right-10 size-10 flex flex-col items-center justify-center bg-blue-600 rounded-md">
        <CIcon class=" hover:cursor-pointer text-white" :icon="cilPlus" width="25" />
    </div>
</template>