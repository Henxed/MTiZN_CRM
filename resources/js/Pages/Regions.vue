<template>
    <app-layout title="Карта регионов">

        <div class="">
            <Map :regions="regions" class="mb-5"/>

            <div class="columns-1 sm:columns-3 bg-white p-8 m-6 dark:bg-slate-800 shadow-lg rounded-lg">
                <div class="text-base my-1" v-for="region in regions" :key="region.id">
                    <Link :href="route('region', region.id)" class="leading-7 flex w-full text-slate-500 dark:text-slate-300 hover:text-purple-700 hover:bg-purple-600/10 py-0.5 px-2 rounded-md">{{ region.region }}</Link>
                </div>
            </div>
    </div>
      <!-- Нельзя -->
    </app-layout>
</template>


<script>
    import { defineComponent } from 'vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import AppLayout from '@/Layouts/AppLayout'
    import Map from '@/Shared/Map'



    export default defineComponent({
        components: {
            AppLayout,
            Head,
            Link,
            Map
        },
        props: {
            regions: Array,
        },
        data() {
            return {

            }
        },
    })
</script>

