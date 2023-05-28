<script setup>

const props = defineProps({
    title: {
        type: String,
        default: '',
    }
});

</script>

<template>
    <h2 class="text-2xl font-bold text-gray-700">
        {{ title }}
    </h2>
</template>
