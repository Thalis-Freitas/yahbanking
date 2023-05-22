<script setup>
const props = defineProps({
    client: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <div
        class="p-4 my-4 md:mb-0 rounded-lg bg-gray-300 font-bold text-gray-800
        sm:flex sm:justify-around"
    >
        <p>
            Valor total:<span class="text-cyan-700"> R${{ client.total_value }}</span>
        </p>
        <p>
            Valor não investido: <span class="text-red-700"> R${{ client.uninvested_value }}</span>
        </p>
        <p>
            Valor investido: <span class="text-green-700"> R${{ client.invested_value }}</span>
        </p>
    </div>
</template>
