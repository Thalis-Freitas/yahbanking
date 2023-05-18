<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import HeaderTitle from '@/Components/Titles/HeaderTitle.vue';
import InvestmentsForm from '@/Components/Forms/InvestmentsForm.vue';
import Container from '@/Components/Container.vue';
import GenericSubmit from '@/Components/Buttons/GenericSubmit.vue';

const props = defineProps({
    investment: {
        type: Object,
        default: () => ({})
    },
    errors: {
        type: Object,
        default: () => ({})
    }
});

const form = useForm(props.investment);

const submit = () => {
    form.put(route('investments.update', form.id));
};

</script>

<template>
    <Head title="Editar Investimento" />

    <AuthenticatedLayout>
        <template #header>
            <HeaderTitle title="Editar Investimento" />
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
