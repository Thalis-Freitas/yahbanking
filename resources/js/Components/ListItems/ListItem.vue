<script setup>

const props = defineProps({
    keyName: {
        type: String,
        default: null
    },
    value: {
        type: String,
        required: true
    },
    roundedOption: {
        type: String,
        default: ''
    }
});

</script>

<template>
    <p :class="`w-full mt-2 p-4 bg-white font-bold ${ roundedOption }`">
        <span v-if="keyName"> {{ keyName }}: </span>
        <span class="font-medium">{{ value }}</span>
    </p>
</template>
