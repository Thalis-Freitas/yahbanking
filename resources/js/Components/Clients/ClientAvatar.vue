<script setup>
const props = defineProps({
    client: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <div v-if="client.avatar">
        <img
            class="rounded-full w-16 h-16"
            :alt="`${client.name} avatar`"
            :src="client.avatar"
        >
    </div>
    <div v-else>
        <img
            class="w-16"
            :alt="`${client.name} avatar`"
            src="/img/avatardefault.svg"
        >
    </div>
</template>
