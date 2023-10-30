<script setup>
import {reactive, ref} from 'vue';

import Menu from 'primevue/menu';

const inputs = {
}
const data = reactive(inputs);
const height = ref(window.innerHeight)

const menu = ref();

const items = ref([
    {
        label: 'Basic',
        items: [
            {
                label: 'Dashboard',
                icon: 'fa-solid fa-users-line',
                to: "/"
            },
            {
                label: 'Blogs',
                icon: 'fa-solid fa-tarp-droplet',
                to: "/blogs"
            },
        ]
    },
]);

</script>
<template>

    <div v-if="height">
        <Menu :model="items"  class="w-full" />
    </div>

</template>


