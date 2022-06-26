<template>
    <app-layout :title="`Охрана труда и социальное партнерство`">

        <div class="w-full max-w-screen-2xl">
            <div class="flex justify-between items-center align-center">
                <div class="py-4">
                    <div class="text-3xl uppercase text-slate-600 dark:text-slate-400 p-5 pb-0 sm:p-0">Охрана труда и социальное партнерство</div>
                </div>
            </div>
            <div class="grid rounded-xl shadow bg-white text-slate-900 dark:bg-slate-800 p-4">
                <div class="flex items-center mb-6">
                    <div class="mr-auto flex items-center bg-gray-200 dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-full max-w-sm w-full pr-3 h-10">
                        <input class="border-none focus:outline-none focus:ring-0 flex-1 h-full w-full p-4 bg-gray-200 dark:bg-slate-700 dark:placeholder:text-slate-400 rounded-full"
                        type="text"
                        placeholder="Поиск по предприятию или ИНН" v-model="queryBuilderData.filter.search">
                        <i class="fi fi-rr-search pt-1 mr-1"></i>
                    </div>
                    <div class="">
                        <Link class="btn-green ml-auto" :href="route('safety.partners.create')"
                        v-if="$page.props.access.can.includes('safety.partners.create') || $page.props.access.role.includes('super-admin')">
                            <span>Добавить</span>
                        </Link>
                    </div>
                </div>
                <perfect-scrollbar class="table-container">
                <table class="table-fixed w-full safety-table dark:text-slate-400">
                    <thead>
                    <!-- <tr class="text-left font-bold h-9">
                        <th @click.prevent="sortBy()" class="sticky top-0 z-30 max-w-sm w-full hover:cursor-pointer">
                            <div class="th hover:bg-gray-300 hover:dark:bg-slate-700 px-4 py-3 border-b border-gray-200 dark:border-slate-500 bg-gray-200 dark:bg-slate-600 text-gray-500 dark:text-gray-200  text-xs leading-4 font-medium uppercase tracking-wider"><span v-html="getSortIcon()"></span> 1111 </div>
                        </th>
                    </tr> -->
                    <tr>
                        <th rowspan="3" width="250">{{ $t(`inputs.safety.enterprise_id`) }}</th>
                        <th rowspan="3" width="180">{{ $t(`inputs.ent.region`) }}</th>
                        <th rowspan="3">{{ $t(`inputs.safety.collective_agreement`) }}</th>
                        <th rowspan="3">{{ $t(`inputs.safety.sum_contractual`) }}</th>
                        <th colspan="6">{{ $t(`inputs.safety.accidents`) }}</th>
                        <th colspan="2" width="110">{{ $t(`inputs.safety.learn_safety`) }}</th>
                    </tr>
                    <tr>
                        <th colspan="2">{{ $t(`inputs.safety.group`) }}</th>
                        <th colspan="2">{{ $t(`inputs.safety.heavy`) }}</th>
                        <th colspan="2">{{ $t(`inputs.safety.deadly`) }}</th>
                        <th rowspan="2">{{ $t(`inputs.safety.in_total`) }}</th>
                        <th rowspan="2">{{ $t(`inputs.safety.start_year`) }}</th>
                    </tr>
                    <tr>
                        <th width="160">{{ $t(`inputs.safety.accidents_group_at`) }}</th>
                        <th >{{ $t(`inputs.safety.accidents_group`) }}</th>
                        <th>{{ $t(`inputs.safety.accidents_heavy_at`) }}</th>
                        <th>{{ $t(`inputs.safety.accidents_heavy`) }}</th>
                        <th>{{ $t(`inputs.safety.accidents_deadly_at`) }}</th>
                        <th>{{ $t(`inputs.safety.accidents_deadly`) }}</th>
                    </tr>

                    </thead>
                    <tbody>
                    <tr v-for="item in enterprises.data" :key="item.id" class="text-center hover:bg-slate-400/10 text-gray-500 dark:text-slate-400 text-sm">
                        <td class="border-t dark:border-slate-500">
                            <Link class="px-2 py-4 flex items-center text-left" :href="route('safety.partners.edit', item.id)">
                            {{ item.name }} <br> ИНН: {{ item.inn || "Отсутсвует" }}
                            </Link>
                        </td>
                        <td class="border-t dark:border-slate-500">
                            <Link class="px-2 py-4 flex items-center text-left" :href="route('safety.partners.edit', item.id)">
                            {{ item.region }}
                            </Link>
                        </td>
                        <td class="border-t dark:border-slate-500">
                            <Link class="px-3 py-4" :href="route('safety.partners.edit', item.id)">
                            {{ item.collective_agreement ? item.collective_agreement.split(' ')[0]  : '-'}}
                            </Link>
                        </td>
                        <td class="border-t dark:border-slate-500">
                            <Link class="px-3 py-4" :href="route('safety.partners.edit', item.id)">
                            {{ item.sum_contractual }}
                            </Link>
                        </td>
                        <td class="border-t dark:border-slate-500">
                            <Link class="px-3 py-4" :href="route('safety.partners.edit', item.id)">
                            {{ item.accidents_group_at ? item.accidents_group_at.split(' ')[0] : '-' }}
                            </Link>
                        </td>
                        <td class="border-t dark:border-slate-500">
                            <Link class="px-3 py-4" :href="route('safety.partners.edit', item.id)">
                            {{ item.accidents_group || '-' }}
                            </Link>
                        </td>
                        <td class="border-t dark:border-slate-500">
                            <Link class="px-3 py-4" :href="route('safety.partners.edit', item.id)">
                            {{ item.accidents_heavy_at ? item.accidents_heavy_at.split(' ')[0] : '-' }}
                            </Link>
                        </td>
                        <td class="border-t dark:border-slate-500">
                            <Link class="px-3 py-4" :href="route('safety.partners.edit', item.id)">
                            {{ item.accidents_heavy || '-' }}
                            </Link>
                        </td>
                        <td class="border-t dark:border-slate-500">
                            <Link class="px-3 py-4" :href="route('safety.partners.edit', item.id)">
                            {{ item.accidents_deadly_at ? item.accidents_deadly_at.split(' ')[0] : '-' }}
                            </Link>
                        </td>
                        <td class="border-t dark:border-slate-500">
                            <Link class="px-3 py-4" :href="route('safety.partners.edit', item.id)">
                            {{ item.accidents_deadly || '-' }}
                            </Link>
                        </td>
                        <td class="border-t dark:border-slate-500">
                            <Link class="px-3 py-4" :href="route('safety.partners.edit', item.id)">
                            {{ item.in_total }}
                            </Link>
                        </td>
                        <td class="border-t dark:border-slate-500">
                            <Link class="px-3 py-4" :href="route('safety.partners.edit', item.id)">
                            {{ item.start_year }}
                            </Link>
                        </td>
                    </tr>
                    <tr v-if="enterprises.data.length === 0">
                    <td class="border-t px-6 py-4" colspan="4">Ничего нет.</td>
                    </tr>
                    </tbody>
                </table>
                </perfect-scrollbar>
                <pagination :links="enterprises.links" />
            </div>
        </div>
    </app-layout>
</template>


<script>
    import qs from "qs";
    import { defineComponent } from 'vue';
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import AppLayout from '@/Layouts/AppLayout';
    import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
    import Pagination from '@/Shared/Pagination'
    import pickBy from "lodash/pickBy";

    export default defineComponent({
        components: {
            AppLayout,
            Head,
            Link,
            PerfectScrollbar,
            Pagination
        },
        props: {
            enterprises: Object,
            queryBuilderProps: {
                type: Object,
                required: true,
            },
        },
        data() {
            return {
                queryBuilderData: {
                    page: this.queryBuilderProps.page || 1,
                    sort: this.queryBuilderProps.sort || "",
                    filter: {
                        search : this.queryBuilderProps.filter.search || "",
                    }
                },
            }
        },
        mounted(){

        },
        methods: {
            getSortIcon(name) {
                switch (this.queryBuilderData.sort) {
                    case name:
                        return '<span> ▲</span>';
                    break;
                    case '-'+name:
                        return '<span> ▼</span>';
                    break;
                    default:
                    break;
                }
            },
            sortBy(column) {
                this.queryBuilderData.page = 1;
                this.queryBuilderData.sort =
                this.queryBuilderData.sort === column ? `-${column}` : column;
            }
        },
        computed: {
            queryBuilderString() {
                let query = qs.stringify(this.queryBuilderData, {
                    filter(prefix, value) {
                        if (typeof value === "object" && value !== null) {
                            return pickBy(value);
                        }
                        return value;
                    },
                    encode: false,
                    skipNulls: true,
                    strictNullHandling: true,
                });

                if (!query || query === "page=1") {
                    query = "";
                }

                return query;
            },
        },

        watch: {
            queryBuilderData: {
                deep: true,
                handler() {
                    if (this.$inertia) {
                        const query = this.queryBuilderString;

                        this.$inertia.get(location.pathname + `?${query}`, {}, { replace: true, preserveState: true });
                    }
                },
            },
        },
    })
</script>
<style src="vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.css"/>
<style scoped>
.table-container {
    height: 70vh;
}
.safety-table th{
    border: 1px solid #cbcdd1;
    padding: 7px;
    font-size: 14px;
    font-weight: 400;
}
.dark .safety-table th{
    border: 1px solid #354260;
}

.safety-table td{
    border: 1px solid #cbcdd1;
}
.dark .safety-table td{
    border: 1px solid #354260;
}
</style>
