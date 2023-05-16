<script setup>

const props = defineProps({
    value: {
        type: String,
        default: 'Excluir'
    }
});

</script>

<template>
    <button class="px-4 py-2 shadow font-bold text-center rounded-md bg-gray-300 text-red-700
        hover:bg-red-600 hover:text-white transition ease-in-out duration-700">
        {{ value }}
    </button>
</template>
