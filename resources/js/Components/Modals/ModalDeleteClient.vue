<script setup>
import ModalConfirmDelete from '@/Components/Modals/ModalConfirmDelete.vue';

</script>

<template>
    <ModalConfirmDelete
        confirm-button-text="Sim, deletar!"
        message="Cliente removido com sucesso!"
        route-name="clients.destroy"
    />
</template>
