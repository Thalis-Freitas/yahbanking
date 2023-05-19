<script setup>

import { router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({
    value: {
        type: String,
        default: 'Excluir'
    },
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

const destroy = () => {
    Swal.fire({
        title: 'Tem certeza?',
        text: props.text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0e7490',
        cancelButtonColor: '#d33',
        confirmButtonText: props.confirmButtonText,
        cancelButtonText: 'Cancelar',
    }).then((result) => {
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
    <button
        type="submit"
        class="px-4 py-2 shadow font-bold text-center rounded-md bg-gray-300 text-red-700
        hover:bg-red-600 hover:text-white transition ease-in-out duration-700"
        @click.prevent="destroy(props.model.id)"
    >
        {{ value }}
    </button>
</template>
