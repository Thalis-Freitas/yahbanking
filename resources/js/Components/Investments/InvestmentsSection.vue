<script setup>
import GenericButton from '@/Components/Buttons/GenericButton.vue';
import SecondaryTitle from '@/Components/Titles/SecondaryTitle.vue';
import ShowButton from '@/Components/Buttons/ShowButton.vue';
import ModalDeleteInvestment from '@/Components/Modals/ModalDeleteInvestment.vue';

const props = defineProps({
    investments: {
        type: Array,
        required: true
    }
});
</script>

<template>
    <section
        v-for="investment in investments"
        :key="investment.id"
        class="bg-white rounded-lg pb-44 md:pb-20 pt-4 px-4 relative"
    >
        <SecondaryTitle :title="investment.abbreviation" />
        <p class="border-b-2 text-gray-600 mb-4">
            Nome Comercial: {{ investment.name }}
        </p>
        <p class="mt-8 text-justify">
            Descrição: {{ investment.description }}
        </p>
        <div class="flex flex-col md:w-28 md:flex-row px-4 absolute bottom-5 left-0 w-full gap-1">
            <ShowButton
                :href="route('investments.show', investment.id)"
                value="Visualizar"
            />
            <GenericButton
                :href="route('investments.edit', investment.id)"
                value="Editar"
            />
            <ModalDeleteInvestment
                :model="investment"
            />
        </div>
    </section>
</template>
