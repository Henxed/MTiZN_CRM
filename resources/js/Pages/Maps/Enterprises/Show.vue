<template>
    <app-layout :title="`${region.region} - Предприятия`">

        <div class="w-full max-w-screen-2xl">
            <div class="flex justify-between items-center align-center">
                <div class="py-8">
                    <div class="text-3xl dark:text-slate-300">{{ enterprise.name }}</div>
                    <Link :href="route('regions.enterprises', region.id)" v-tippy='"Вернуться к списку предприятий региона"' class="text-xl uppercase text-slate-600 dark:text-slate-400 p-5 pb-0 sm:p-0 sm:mb-6 xl:mb-9">
                            {{ region.region }}
                    </Link>

                </div>
                <Link class="btn-blue ml-auto" :href="route('enterprises.edit', enterprise.id)"
                v-if="$page.props.access.can.includes('enterprise.create') || $page.props.access.role.includes('super-admin') || $page.props.access_region.includes(region.id)">
                    <span>Редактировать</span>
                    <span class="hidden md:inline"> предприятие</span>
                </Link>
            </div>
            <div class="rounded-xl shadow bg-white text-slate-900 dark:text-slate-400 dark:bg-slate-800 p-4">

                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-slate-300">{{ enterprise.okvd_name || "ОКВЭД неизвестен" }}</h3>
                    <p class="mt-1 max-w-2xl text-sm">{{ enterprise.status ? enterprise.status.name : "Статус неизвестен"}}</p>
                </div>
                <div class="border-t border-gray-200 dark:border-slate-600">
                <dl>
                    <div class="bg-white dark:bg-slate-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.inn`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 sm:col-span-2">{{ enterprise.inn || "-"  }}</dd>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.ogrn`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 sm:col-span-2">{{ enterprise.ogrn || "-"  }}</dd>
                    </div>
                    <div class="bg-white dark:bg-slate-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.rns`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 sm:col-span-2">{{ enterprise.rns || "-" }}</dd>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.okvd`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 sm:col-span-2">{{ enterprise.okvd || "-"  }}</dd>
                    </div>
                    <div class="bg-white dark:bg-slate-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.address`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 sm:col-span-2">{{ enterprise.address || "-" }}</dd>
                    </div>
                </dl>
                </div>

                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-slate-300">Статистика</h3>
                </div>
                <div class="border-t border-gray-200 dark:border-slate-600">
                <dl>
                    <div class="bg-gray-50 dark:bg-slate-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.amy`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 sm:col-span-2">{{ enterprise.amy || "-"  }}</dd>
                    </div>
                    <div class="bg-white dark:bg-slate-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.total_jobs`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 sm:col-span-2">{{ enterprise.total_jobs || "-" }}</dd>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.cw`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 sm:col-span-2">{{ enterprise.cw || "-" }}</dd>
                    </div>
                    <div class="bg-white dark:bg-slate-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.ane`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 sm:col-span-2">{{ enterprise.ane || "-"  }}</dd>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.nde`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 sm:col-span-2">{{ enterprise.nde || "-" }}</dd>
                    </div>
                    <div class="bg-white dark:bg-slate-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.factors`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 sm:col-span-2">{{ enterprise.factors || "-"  }}</dd>
                    </div>
                    <div class="bg-white dark:bg-slate-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.workplaces_respect`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 sm:col-span-2">{{ enterprise.workplaces_respect || "-" }}</dd>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.workplaces_three`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 sm:col-span-2">{{ enterprise.workplaces_three || "-"  }}</dd>
                    </div>
                    <div class="bg-white dark:bg-slate-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.workplaces_four`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 sm:col-span-2">{{ enterprise.workplaces_four || "-" }}</dd>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.total_factors`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 sm:col-span-2">{{ enterprise.total_factors || "-"  }}</dd>
                    </div>
                    <div class="bg-white dark:bg-slate-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.start_year_factors`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 sm:col-span-2">{{ enterprise.start_year_factors || "-" }}</dd>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.sum_arrears`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 sm:col-span-2">{{ enterprise.sum_arrears || "-"  }}</dd>
                    </div>
                    <div class="bg-white dark:bg-slate-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.employed_public`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 sm:col-span-2">{{ enterprise.employed_public || "-" }}</dd>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.employed_temporary`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 sm:col-span-2">{{ enterprise.employed_temporary || "-"  }}</dd>
                    </div>
                    <div class="bg-white dark:bg-slate-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.work_part`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 sm:col-span-2">{{ enterprise.work_part || "-" }}</dd>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.vacations`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 sm:col-span-2">{{ enterprise.vacations || "-"  }}</dd>
                    </div>
                    <div class="bg-white dark:bg-slate-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.dismissed`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 sm:col-span-2">{{ enterprise.dismissed || "-" }}</dd>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.remote`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 sm:col-span-2">{{ enterprise.remote || "-"  }}</dd>
                    </div>
                    <div class="bg-white dark:bg-slate-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.start_year_factors`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 sm:col-span-2">{{ enterprise.start_year_factors || "-" }}</dd>
                    </div>


                </dl>
                </div>
            </div>
            </div>

    </app-layout>
</template>


<script>
    import qs from "qs";
    import { defineComponent } from 'vue';
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import AppLayout from '@/Layouts/AppLayout';


    export default defineComponent({
        components: {
            AppLayout,
            Head,
            Link,
        },
        props: {
            region: Array,
            enterprise: Object,
            access_region: Array
        },
        data() {
            return {

            }
        },
        mounted(){

        },
        methods: {

        },

    })
</script>
