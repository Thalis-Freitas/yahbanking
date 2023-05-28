<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import HeaderTitle from '@/Components/Titles/HeaderTitle.vue';
import InvestmentsForm from '@/Components/Investments/InvestmentsForm.vue';
import Container from '@/Components/Container.vue';
import GenericSubmit from '@/Components/Buttons/GenericSubmit.vue';
import { showModal } from '@/modalError';

const props = defineProps({
    investment: {
        type: Object,
        default: () => ({})
    }
});

const titleValue = computed(() => {
    return `Editar Investimento ${ props.investment.abbreviation.toUpperCase() }`;
});

const form = useForm(props.investment);

const submit = () => {
    form.put(route('investments.update', form.id));
    showModal(form, 'Não foi possível atualizar o cadastro!');
};

</script>

<template>
    <Head :title="titleValue" />

    <AuthenticatedLayout>
        <template #header>
            <HeaderTitle :title="titleValue" />
        </template>
        <Container #content>
            <form @submit.prevent="submit">
                <InvestmentsForm
                    :form="form"
                    :errors="form.errors"
                />
                <GenericSubmit
                    type="submit"
                    value="Editar Investimento"
                    :disabled="form.processing"
                    :href="route('investments.update', form.id)"
                />
            </form>
        </Container>
    </AuthenticatedLayout>
</template>
