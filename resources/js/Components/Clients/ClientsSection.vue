<script setup>
import ClientAvatar from '@/Components/Clients/ClientAvatar.vue';
import ClientValues from '@/Components/Clients/ClientValues.vue';
import GenericButton from '@/Components/Buttons/GenericButton.vue';
import SecondaryTitle from '@/Components/Titles/SecondaryTitle.vue';
import ShowButton from '@/Components/Buttons/ShowButton.vue';
import ModalDeleteClient from '@/Components/Modals/ModalDeleteClient.vue';

const props = defineProps({
    clients: {
        type: Array,
        required: true
    }
});

const fullName = (name, lastName) => {
    return `${name} ${lastName}`;
};
</script>

<template>
    <section
        v-for="client in clients"
        :key="client.id"
        class="bg-white rounded-lg p-4 md:relative"
    >
        <div class="flex items-center">
            <ClientAvatar :client="client" />
            <div class="ms-2 md:max-w-[35%] lg:max-w-[50%] xl:max-w-[60%]">
                <SecondaryTitle :title="fullName(client.name, client.last_name)" />
                <p class="-mt-1 text-justify text-gray-600 break-all">
                    {{ client.email }}
                </p>
            </div>
        </div>
        <ClientValues :client="client" />
        <div
            class="flex flex-col sm:w-full sm:flex-row gap-1
                sm:justify-end md:absolute top-4 right-4"
        >
            <ShowButton
                :href="route('clients.show', client.id)"
                value="Investimento"
            />
            <GenericButton
                :href="route('clients.edit', client.id)"
                value="Editar"
            />
            <ModalDeleteClient
                :model="client"
            />
        </div>
    </section>
</template>
