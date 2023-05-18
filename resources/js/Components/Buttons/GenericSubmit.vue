<script setup>

const props = defineProps({
    value: {
        type: String,
        default: ''
    },
    background: {
        type: String,
        default: 'cyan'
    }
});

</script>

<template>
    <button
        :class="`px-4 py-2 shadow text-white font-bold rounded-md text-center
        bg-${background}-700 hover:bg-${background}-900 transition ease-in-out duration-700`"
    >
        {{ value }}
    </button>
</template>
