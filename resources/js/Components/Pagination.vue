
<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    links: Array
});

</script>

<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap justify-center sm:justify-end">
            <template v-for="(link, key) in links">
                <div
                    v-if="link.url === null"
                    :key="key"
                    class=" px-2 py-2 mb-2 ml-1 text-sm leading-4 border-b-2 border-gray-800 text-gray-700"
                    v-html="link.label"
                />
                <Link
                    v-else :href="link.url" v-html="link.label"
                    class="px-2 py-2 mb-2 ml-1 text-sm leading-4 border-b-2 border-gray-800
                        text-gray-400 transition duration-300 ease-in-out"
                        :class="{'cursor-auto text-gray-800 border-indigo-700 hover:border-indigo-700': link.active,
                                'hover:border-gray-400': !link.active}"
                />
            </template>
        </div>
    </div>
</template>
