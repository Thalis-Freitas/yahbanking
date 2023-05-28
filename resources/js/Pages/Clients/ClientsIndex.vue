<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ClientsSection from '@/Components/Clients/ClientsSection.vue';
import { Head } from '@inertiajs/vue3';
import HeaderTitle from '@/Components/Titles/HeaderTitle.vue';
import PaginatedContainer from '@/Components/PaginatedContainer.vue';
import RegisterButton from '@/Components/Buttons/RegisterButton.vue';
import SuccessMessage from '@/Components/Messages/SuccessMessage.vue';
import WarnMessage from '@/Components/Messages/WarnMessage.vue';

const props = defineProps({
    clients: {
        type: Object,
        default: () => ({})
    }
});

</script>

<template>
    <Head title="Clientes" />
    <AuthenticatedLayout>
        <template #header>
            <HeaderTitle title="Clientes" />
            <RegisterButton
                :href="route('clients.create')"
                value="Novo Cliente"
            />
        </template>
        <PaginatedContainer
            v-if="clients.data.length > 0"
            :links="clients.links"
        >
            <template #content>
                <SuccessMessage
                    v-if="$page.props.flash.msg"
                    :message="$page.props.flash.msg"
                />
                <div class="flex flex-col gap-6">
                    <ClientsSection :clients="clients.data" />
                </div>
            </template>
        </PaginatedContainer>
        <WarnMessage
            v-else
            class="mt-6 mx-6 sm:mx-12 lg:mx-20"
            message="Nenhum cliente encontrado!"
        />
    </AuthenticatedLayout>
</template>
