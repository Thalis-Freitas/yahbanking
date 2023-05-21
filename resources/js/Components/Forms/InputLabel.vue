<script setup>
defineProps({
    value: {
        type: String,
        default: ''
    },
    classes: {
        type: String,
        default: 'block font-medium text-sm text-gray-300'
    }
});
</script>

<template>
    <label :class="classes">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
