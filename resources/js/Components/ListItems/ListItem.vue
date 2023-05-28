<script setup>

const props = defineProps({
    roundedOption: {
        type: String,
        default: ''
    }
});

</script>

<template>
    <p :class="`w-full mt-2 p-4 bg-white font-bold ${ roundedOption }`">
        <slot />
    </p>
</template>
