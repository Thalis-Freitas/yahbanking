<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import HeaderTitle from '@/Components/Titles/HeaderTitle.vue';
import InvestmentsSection from '@/Components/Investments/InvestmentsSection.vue';
import PaginatedContainer from '@/Components/PaginatedContainer.vue';
import RegisterButton from '@/Components/Buttons/RegisterButton.vue';
import SuccessMessage from '@/Components/Messages/SuccessMessage.vue';
import WarnMessage from '@/Components/Messages/WarnMessage.vue';

const props = defineProps({
    investments: {
        type: Object,
        default: () => ({})
    }
});

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
                :href="route('investments.create')"
                value="Cadastrar Investimento"
            />
        </template>

        <PaginatedContainer
            v-if="investments.data.length > 0"
            :links="investments.links"
        >
            <template #content>
                <SuccessMessage
                    v-if="$page.props.flash.msg"
                    :message="$page.props.flash.msg"
                />
                <div class="grid md:grid-cols-2 gap-6">
                    <InvestmentsSection :investments="computedInvestments" />
                </div>
            </template>
        </PaginatedContainer>
        <WarnMessage
            v-else
            class="mt-6 mx-6 sm:mx-12 lg:mx-20"
            message="Nenhum investimento encontrado!"
        />
    </AuthenticatedLayout>
</template>
