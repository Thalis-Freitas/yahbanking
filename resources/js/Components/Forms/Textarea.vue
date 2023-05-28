<script setup>
import { ref } from 'vue';

defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    form: {
        type: Object,
        default: () => ({}),
    },
});

defineEmits(['update:modelValue']);

const input = ref(null);

</script>

<template>
    <textarea
        ref="input"
        class="w-full border-gray-700 bg-gray-900 text-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm"
        @input="$emit('update:modelValue', $event.target.value)"
    >{{ modelValue }}</textarea>
</template>
