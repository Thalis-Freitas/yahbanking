<script setup>
const props = defineProps({
    error: {
        type: String,
        default: ''
    }
});

</script>

<template>
    <span
        v-if="error"
        class="text-red-600"
    >
        {{ error }}
    </span>
</template>
