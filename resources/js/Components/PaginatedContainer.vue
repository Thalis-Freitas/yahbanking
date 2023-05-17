<script setup>
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    links: {
        type: Object,
        required: true
    }
});

</script>

<template>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-700">
                    <slot name="content" />
                </div>
            </div>
            <Pagination
                class="mt-6"
                :links="links"
            />
        </div>
    </div>
</template>
