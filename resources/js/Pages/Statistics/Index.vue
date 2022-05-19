<template>
    <app-layout title="Статистика">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 rounded-xl bg-white p-8 dark:bg-slate-800 w-full max-w-screen-2xl">
            Тут пока ничего нет
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
            Link
        },
        data() {
            return {

            }
        },
    })
</script>

