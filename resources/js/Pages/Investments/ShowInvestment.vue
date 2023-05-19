<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BottomListItem from '@/Components/ListItems/BottomListItem.vue';
import { computed } from 'vue';
import Container from '@/Components/Container.vue';
import { Head } from '@inertiajs/vue3';
import GenericButton from '@/Components/Buttons/GenericButton.vue';
import HeaderTitle from '@/Components/Titles/HeaderTitle.vue';
import ListItem from '@/Components/ListItems/ListItem.vue';
import ModalConfirmDelete from '@/Components/Modals/ModalConfirmDelete.vue';
import SuccessMessage from '@/Components/Messages/SuccessMessage.vue';
import TopListItem from '@/Components/ListItems/TopListItem.vue';

const props = defineProps({
    investment: {
        type: Object,
        default: () => ({})
    }
});

const abbreviationToUpper = computed(() => {
    return props.investment.abbreviation.toUpperCase();
});

const titleValue = () => {
    return `Investimento ${ abbreviationToUpper.value }`;
};

const headerTitleValue = () => {
    return `Informações sobre ${ abbreviationToUpper.value }`;
};

</script>

<template>
    <Head :title="titleValue()" />

    <AuthenticatedLayout>
        <template #header>
            <HeaderTitle :title="headerTitleValue()" />
        </template>
        <Container #content>
            <SuccessMessage
                v-if="$page.props.flash.msg"
                :message="$page.props.flash.msg"
            />
            <TopListItem
                key-name="Sigla"
                :value="abbreviationToUpper"
            />
            <ListItem
                key-name="Nome Comercial"
                :value="investment.name"
            />
            <BottomListItem
                key-name="Descrição"
                :value="investment.description"
            />
            <div class="mt-8 flex gap-2">
                <GenericButton
                    :href="route('investments.edit', investment.id)"
                    value="Editar"
                />
                <ModalConfirmDelete
                    value="Encerrar"
                    :model="investment"
                    text="Todos os valores aplicados a este investimento serão devolvidos!"
                    confirm-button-text="Sim, encerrar!"
                    status-info="Encerrado"
                    message="Investimento encerrado com sucesso!"
                    route-name="investments.destroy"
                />
            </div>
        </Container>
    </AuthenticatedLayout>
</template>
