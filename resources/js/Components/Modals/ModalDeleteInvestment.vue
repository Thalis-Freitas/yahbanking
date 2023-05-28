<script setup>
import ModalConfirmDelete from '@/Components/Modals/ModalConfirmDelete.vue';

</script>

<template>
    <ModalConfirmDelete
        value="Encerrar"
        text="Todos os valores aplicados a este investimento serão devolvidos!"
        confirm-button-text="Sim, encerrar!"
        status-info="Encerrado"
        message="Investimento encerrado com sucesso!"
        route-name="investments.destroy"
    />
</template>
