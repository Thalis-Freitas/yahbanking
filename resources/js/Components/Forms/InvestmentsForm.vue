<script setup>
import ErrorsMessage from '@/Components/Messages/ErrorsMessage.vue';
import FieldError from './FieldError.vue';

const props = defineProps({
    form: {
        type: Object,
        required: true
    },
    errors: {
        type: Object,
        required: true
    },
});

const hasErrors = () => {
    return Object.keys(props.errors).length > 0;
};
</script>

<template>
    <div>
        <ErrorsMessage
            v-if="hasErrors()"
            message="Não foi possível realizar o cadastro."
        />
        <div class="w-full mb-6">
            <label
                class="block text-white mb-2"
                for="abbreviation"
            >Sigla</label>
            <input
                id="abbreviation"
                v-model="form.abbreviation"
                class="w-full rounded-lg"
                type="text"
            >
            <FieldError :error="errors.abbreviation" />
        </div>
        <div class="w-full mb-6">
            <label
                class="block text-white mb-2"
                for="name"
            >Nome Comercial</label>
            <input
                id="name"
                v-model="form.name"
                class="w-full rounded-lg"
                type="text"
            >
            <FieldError :error="errors.name" />
        </div>
        <div class="w-full mb-6">
            <label
                class="block text-white mb-2"
                for="description"
            >Descrição</label>
            <textarea
                id="description"
                v-model="form.description"
                class="w-full rounded-lg"
            />
            <FieldError :error="errors.description" />
        </div>
    </div>
</template>
