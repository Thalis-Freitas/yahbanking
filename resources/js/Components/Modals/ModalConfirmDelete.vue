<script setup>

import { router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import SubmitDelete from '@/Components/Buttons/SubmitDelete.vue';

const props = defineProps({
    model: {
        type: Object,
        default: () => ({})
    },
    text: {
        type: String,
        default: ''
    },
    confirmButtonText: {
        type: String,
        default: 'Sim, excluir!'
    },
    statusInfo: {
        type: String,
        default: 'Excluído'
    },
    message: {
        type: String,
        required: true
    },
    routeName: {
        type: String,
        required: true
    }
});

const confirmModalConfig = {
    title: 'Tem certeza?',
    text: props.text,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#0e7490',
    cancelButtonColor: '#d33',
    confirmButtonText: props.confirmButtonText,
    cancelButtonText: 'Cancelar',
};

const destroy = () => {
    Swal.fire(
        confirmModalConfig
    ).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
                props.statusInfo,
                props.message,
                'success'
            );
            router.delete(route(props.routeName, props.model.id));
        }
    });
};

</script>

<template>
    <SubmitDelete @click.prevent="destroy(props.model.id)" />
</template>
