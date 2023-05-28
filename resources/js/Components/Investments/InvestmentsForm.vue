<script setup>
import FieldError from '@/Components/Forms/FieldError.vue';
import InputLabel from '@/Components/Forms/InputLabel.vue';
import Textarea from '@/Components/Forms/Textarea.vue';
import TextInput from '@/Components/Forms/TextInput.vue';

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
</script>

<template>
    <section>
        <div class="w-full mb-6">
            <InputLabel
                classes="block text-white mb-2"
                for="abbreviation"
                value="Sigla"
            />
            <TextInput
                id="abbreviation"
                v-model="form.abbreviation"
                :model-value="form.abbreviation"
                type="text"
                autofocus
            />
            <FieldError :error="errors.abbreviation" />
        </div>
        <div class="w-full mb-6">
            <InputLabel
                classes="block text-white mb-2"
                for="name"
                value="Nome Comercial"
            />
            <TextInput
                id="name"
                v-model="form.name"
                :model-value="form.name"
                type="text"
            />
            <FieldError :error="errors.name" />
        </div>
        <div class="w-full mb-6">
            <InputLabel
                classes="block text-white mb-2"
                for="description"
                value="Descrição"
            />
            <Textarea
                id="description"
                v-model="form.description"
                :model-value="form.description"
            />
            <FieldError :error="errors.description" />
        </div>
    </section>
</template>
