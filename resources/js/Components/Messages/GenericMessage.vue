<script setup>

const props = defineProps({
    message: {
        type: String,
        default: ''
    },
    background: {
        type: String,
        default: 'cyan-700'
    }
});

</script>

<template>
    <div :class="`p-4 font-bold bg-${background} text-white rounded-lg mb-6`">
        {{ message }}
    </div>
</template>
