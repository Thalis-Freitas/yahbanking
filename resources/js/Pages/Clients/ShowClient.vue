<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BottomListItem from '@/Components/ListItems/BottomListItem.vue';
import ClientAvatar from '@/Components/Clients/ClientAvatar.vue';
import { computed } from 'vue';
import Container from '@/Components/Container.vue';
import GenericButton from '@/Components/Buttons/GenericButton.vue';
import { Head } from '@inertiajs/vue3';
import HeaderTitle from '@/Components/Titles/HeaderTitle.vue';
import ListItem from '@/Components/ListItems/ListItem.vue';
import ModalDeleteClient from '@/Components/Modals/ModalDeleteClient.vue';
import SuccessMessage from '@/Components/Messages/SuccessMessage.vue';
import TopListItem from '@/Components/ListItems/TopListItem.vue';

const props = defineProps({
    client: {
        type: Object,
        default: () => ({})
    }
});

const fullName = computed(() => {
    return `${props.client.name} ${props.client.last_name}`;
});

const titleValue = () => {
    return `Cliente ${ fullName.value }`;
};

const headerTitleValue = () => {
    return `Informações sobre ${ fullName.value }`;
};

</script>

<template>
    <Head :title="titleValue()" />

    <AuthenticatedLayout>
        <template #header>
            <HeaderTitle
                class="max-w-[70%]"
                :title="headerTitleValue()"
            />
            <ClientAvatar :client="client" />
        </template>
        <Container #content>
            <SuccessMessage
                v-if="$page.props.flash.msg"
                :message="$page.props.flash.msg"
            />
            <div
                class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg
                        flex flex-col gap-2 justify-between sm:flex-row"
            >
                <section class="sm:min-w-[48%]">
                    <h2 class="text-2xl text-gray-400 font-bold">
                        Dados cadastrais
                    </h2>
                    <div class="pt-6 text-gray-900 font-bold">
                        <TopListItem>
                            Nome: <span class="font-medium">{{ client.name }} </span>
                        </TopListItem>
                        <ListItem>
                            Sobrenome: <span class="font-medium">{{ client.last_name }} </span>
                        </ListItem>
                        <BottomListItem>
                            E-mail: <span class="font-medium">{{ client.email }}</span>
                        </BottomListItem>
                        <div class="mt-8 flex gap-2">
                            <GenericButton
                                :href="route('clients.edit', client.id)"
                                value="Editar"
                            />
                            <ModalDeleteClient
                                :model="client"
                            />
                        </div>
                    </div>
                </section>
                <section class="sm:min-w-[48%]">
                    <h2 class="text-2xl text-gray-400 font-bold">
                        Valores
                    </h2>
                    <div class="pt-6 text-gray-900 font-bold">
                        <TopListItem>
                            Valor total: <span class="text-cyan-700"> R${{ client.total_value }}</span>
                        </TopListItem>
                        <ListItem>
                            Valor não investido: <span class="text-red-700"> R${{ client.uninvested_value }}</span>
                        </ListItem>
                        <BottomListItem>
                            Valor investido: <span class="text-green-700"> R${{ client.invested_value }}</span>
                        </BottomListItem>
                    </div>
                </section>
            </div>
        </Container>
    </AuthenticatedLayout>
</template>
