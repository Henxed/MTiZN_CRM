<template>
    <app-layout title="Карта регионов">
        <div class="grid grid-cols-1 gap-6 rounded-xl bg-white text-slate-900 m-2 p-9 sm:m-6 dark:bg-slate-800 w-full max-w-screen-2xl">
            <div class="text-2xl font-bold dark:text-slate-300">Электронный журнал НПА</div>
            <div class="flex mt-3 dark:text-slate-300">
                <div class="mr-6">
                    <div class="text-2xl font-semibold">45</div>
                    <div class="">Действительно</div>
                </div>
                <div class="mr-6">
                    <div class="text-2xl font-semibold">3</div>
                    <div class="">Просрочено</div>
                </div>
                <div class="mr-6">
                    <div class="text-2xl font-semibold">13</div>
                    <div class="">Нужно обновить</div>
                </div>
            </div>
            <div class="bg-gray-200 dark:bg-slate-600 dark:text-slate-300 p-4 rounded-xl" v-for="item in regists.data" :key="item">
                {{ item.title }}
            </div>
        </div>
    </app-layout>
</template>


<script>
    import { defineComponent } from 'vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import AppLayout from '@/Layouts/AppLayout'



    export default defineComponent({
        components: {
            AppLayout,
            Head,
            Link,
        },
        props: {
            regists: Object,
        },
        data() {
            return {

            }
        },
    })
</script>

