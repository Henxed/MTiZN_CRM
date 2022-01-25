<template>
    <app-layout title="Главная">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 rounded-xl bg-white m-2 p-8 sm:m-6 dark:bg-slate-800 w-full max-w-screen-2xl">
            <a href="https://map.henxed.ru" class="flex bg-orange-100 dark:bg-orange-500/30 p-3 rounded-xl overflow-hidden relative h-48 min-w-48 w-full max-w-64">
                <div class="absolute -right-5 -bottom-8 text-orange-200 dark:text-orange-200/30 text-9xl"><i class="fi fi-rr-map-marker"></i></div>
                <div class="text-lg text-orange-400">Карта регионов</div>
            </a>

            <a href="https://map.henxed.ru/reg" class="flex bg-blue-100 dark:bg-blue-500/30 p-3 rounded-xl overflow-hidden relative h-48 min-w-48 w-full max-w-64">
                <div class="absolute -right-5 -bottom-8 text-blue-200 dark:text-blue-200/30 text-9xl"><i class="fi fi-rr-duplicate"></i></div>
                <div class="text-lg text-blue-400">Электронный журнал НПА</div>
            </a>

            <a href="#" class="flex bg-pink-100 dark:bg-pink-500/30 p-3 rounded-xl overflow-hidden relative h-48 min-w-48 w-full max-w-64">
                <div class="absolute -right-5 -bottom-8 text-pink-200 dark:text-pink-200/30 text-9xl"><i class="fi fi-rr-chart-histogram"></i></div>
                <div class="text-lg text-pink-400">Статистика</div>
            </a>

            <a href="https://map.henxed.ru/cp" class="flex bg-gray-100 dark:bg-gray-500/30 p-3 rounded-xl overflow-hidden relative h-48 min-w-48 w-full max-w-64">
                <div class="absolute -right-5 -bottom-8 text-gray-200 dark:text-gray-200/30 text-9xl"><i class="fi fi-rr-apps"></i></div>
                <div class="text-lg text-gray-400">Администрирование системы</div>
            </a>
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

