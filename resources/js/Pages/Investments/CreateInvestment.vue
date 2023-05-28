<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import HeaderTitle from '@/Components/Titles/HeaderTitle.vue';
import InvestmentsForm from '@/Components/Investments/InvestmentsForm.vue';
import Container from '@/Components/Container.vue';
import SubmitRegister from '@/Components/Buttons/SubmitRegister.vue';
import { showModal } from '@/modalError';

const form = useForm({
    abbreviation: '',
    name: '',
    description: ''
});

const submit = () => {
    form.post(route('investments.store'));
    showModal(form, 'Não foi possível realizar o cadastro!');
};

</script>

<template>
    <Head title="Cadastrar Investimento" />

    <AuthenticatedLayout>
        <template #header>
            <HeaderTitle title="Cadastrar Investimento" />
        </template>
        <Container #content>
            <form @submit.prevent="submit">
                <InvestmentsForm
                    :form="form"
                    :errors="form.errors"
                />
                <SubmitRegister
                    type="submit"
                    value="Cadastrar Investimento"
                    :disabled="form.processing"
                    :href="route('investments.store')"
                />
            </form>
        </Container>
    </AuthenticatedLayout>
</template>
