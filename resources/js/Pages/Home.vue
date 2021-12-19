<template>
    <app-layout title="Главная">
        <div class="grid grid-cols-4 gap-6 rounded-xl bg-white p-8 m-6">
            <a href="https://map.henxed.ru" class="flex bg-orange-100 p-3 rounded-xl overflow-hidden relative h-48 w-64 max-w-5xl">
                <div class="absolute -right-5 -bottom-8 text-orange-200 text-9xl"><i class="fi fi-rr-map-marker"></i></div>
                <div class="text-lg text-orange-400">Карта регионов</div>
            </a>

            <a href="https://map.henxed.ru/reg" class="flex bg-blue-100 p-3 rounded-xl overflow-hidden relative h-48 w-64 max-w-5xl">
                <div class="absolute -right-5 -bottom-8 text-blue-200 text-9xl"><i class="fi fi-rr-duplicate"></i></div>
                <div class="text-lg text-blue-400">Реестры</div>
            </a>

            <a href="#" class="flex bg-pink-100 p-3 rounded-xl overflow-hidden relative h-48 w-64 max-w-5xl">
                <div class="absolute -right-5 -bottom-8 text-pink-200 text-9xl"><i class="fi fi-rr-chart-histogram"></i></div>
                <div class="text-lg text-pink-400">Статистика</div>
            </a>
        </div>

        <!-- <Map /> -->

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
        data() {
            return {

            }
        },
    })
</script>

