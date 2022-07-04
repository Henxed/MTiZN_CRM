<template>
    <app-layout title="Главная">
        <div class="card grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 p-8 w-full max-w-screen-2xl">
            <a :href="route('map')" class="flex bg-orange-100 dark:bg-orange-500/30 p-3 rounded-xl overflow-hidden relative h-48 min-w-48 w-full max-w-64">
                <div class="absolute -right-5 -bottom-8 text-orange-200 dark:text-orange-200/30 text-9xl"><i class="fi fi-rr-map"></i></div>
                <div class="text-lg text-orange-400">Карта районов</div>
            </a>

            <a :href="route('regions.index')" class="flex bg-emerald-100 dark:bg-emerald-500/30 p-3 rounded-xl overflow-hidden relative h-48 min-w-48 w-full max-w-64">
                <div class="absolute -right-5 -bottom-8 text-emerald-200 dark:text-emerald-200/30 text-9xl"><i class="fi fi-rr-map-marker"></i></div>
                <div class="text-lg text-emerald-400">Районы</div>
            </a>

            <a :href="route('registry.list', ['npa', 'all'])" class="flex bg-blue-100 dark:bg-blue-500/30 p-3 rounded-xl overflow-hidden relative h-48 min-w-48 w-full max-w-64">
                <div class="absolute -right-5 -bottom-8 text-blue-200 dark:text-blue-200/30 text-9xl"><i class="fi fi-rr-duplicate"></i></div>
                <div class="text-lg text-blue-400">Реестры</div>
            </a>

            <a :href="route('stats.index')" v-if="$page.props.access.can.includes('stats') || $page.props.access.role.includes('super-admin')" class="flex bg-pink-100 dark:bg-pink-500/30 p-3 rounded-xl overflow-hidden relative h-48 min-w-48 w-full max-w-64">
                <div class="absolute -right-5 -bottom-8 text-pink-200 dark:text-pink-200/30 text-9xl"><i class="fi fi-rr-chart-histogram"></i></div>
                <div class="text-lg text-pink-400">Статистика</div>
            </a>

            <a :href="route('safety.partners.index')" v-if="$page.props.access.can.includes('safety') || $page.props.access.role.includes('super-admin')" class="flex bg-lime-100 dark:bg-lime-500/30 p-3 rounded-xl overflow-hidden relative h-48 min-w-48 w-full max-w-64">
                <div class="absolute -right-5 -bottom-8 text-lime-200 dark:text-lime-200/30 text-9xl"><i class="fi fi-rr-shield-check"></i></div>
                <div class="text-lg text-lime-500">Охрана труда</div>
            </a>

            <a :href="route('settings')" v-if="$page.props.access.can.includes('cp') || $page.props.access.role.includes('super-admin')" class="flex bg-gray-100 dark:bg-gray-500/30 p-3 rounded-xl overflow-hidden relative h-48 min-w-48 w-full max-w-64">
                <div class="absolute -right-5 -bottom-8 text-gray-200 dark:text-gray-200/30 text-9xl"><i class="fi fi-rr-settings"></i></div>
                <div class="text-lg text-gray-400">Администрирование системы</div>
            </a>

            <!-- <link-card :href="route('settings')" :color="'lime'" :icon="'settings'">tt</link-card> -->
        </div>
    </app-layout>
</template>


<script>
    import { defineComponent } from 'vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import AppLayout from '@/Layouts/AppLayout'
    // import LinkCard from '@/Shared/LinkCard'


    export default defineComponent({
        components: {
            AppLayout,
            Head,
            Link,
            // LinkCard
        },
        data() {
            return {

            }
        },
    })
</script>

