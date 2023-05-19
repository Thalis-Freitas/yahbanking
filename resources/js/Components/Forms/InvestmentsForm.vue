<script setup>
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
</script>

<template>
    <div>
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
