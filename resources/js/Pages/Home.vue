<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed } from 'vue';
import DeleteButton from '@/Components/Buttons/DeleteButton.vue';
import { Head } from '@inertiajs/vue3';
import GenericButton from '@/Components/Buttons/GenericButton.vue';
import HeaderTitle from '@/Components/Titles/HeaderTitle.vue';
import PaginatedContainer from '@/Components/PaginatedContainer.vue';
import RegisterButton from '@/Components/Buttons/RegisterButton.vue';
import SecondaryTitle from '@/Components/Titles/SecondaryTitle.vue';
import ShowButton from '@/Components/Buttons/ShowButton.vue';
import WarnMessage from '@/Components/Messages/WarnMessage.vue';

const props = defineProps({
    investments: {
        type: Object,
        default: () => ({})
    }
});

function destroy(id) {
    if (confirm('Tem certeza que deseja excluir este investimento? Todos os valores aplicados serão devolvidos.')) {
        Inertia.delete(route('investments.destroy', id));
    }
}

const computedInvestments = computed(() => {
    return props.investments.data.map((investment) => ({
        ...investment,
        abbreviation: investment.abbreviation.toUpperCase()
    }));
});

</script>

<template>
    <Head title="Investimentos" />

    <AuthenticatedLayout>
        <template #header>
            <HeaderTitle title="Investimentos" />
            <RegisterButton
                href="route('investments.create')"
                value="Cadastrar Investimento"
            />
        </template>

        <PaginatedContainer
            v-if="investments.data.length > 0"
            :links="investments.links"
        >
            <template #content>
                <div class="grid md:grid-cols-2">
                    <section
                        v-for="investment in computedInvestments"
                        :key="investment.id"
                        class="bg-white rounded-lg pb-44 md:pb-20 pt-4 px-4 m-4 relative"
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
                            <DeleteButton @click="destroy(investment.id)" />
                        </div>
                    </section>
                </div>
            </template>
        </PaginatedContainer>
        <WarnMessage
            v-else
            message="Nenhum investimento encontrado!"
        />
    </AuthenticatedLayout>
</template>
