<template>
    <app-layout :title="`${region.region} - Предприятия`">
        <div class="rounded-xl bg-white text-slate-900 dark:bg-slate-800 w-full max-w-screen-2xl p-9">
            <div class="mb-6 flex justify-between items-center align-center">
                <div class="">
                    <div class="font-bold text-3xl dark:text-slate-300">Предприятия региона</div>
                    <Link :href="route('regions.show', region.id)" class="block font-bold text-xl text-indigo-600 dark:text-indigo-500 dark:hover:text-pink-600 hover:text-pink-500 uppercase">{{region.region}}</Link>

                </div>
                <Link class="btn-green ml-auto" :href="route('regions.enterprises.create', region.id)">
                    <span>Новое</span>
                    <span class="hidden md:inline"> предприятие</span>
                </Link>
            </div>

            <div class="mb-6 flex items-center bg-gray-200 dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-full max-w-sm w-full pr-3 h-10">
                <input class="border-none focus:outline-none focus:ring-0 flex-1 h-full w-full p-4 bg-gray-200 dark:bg-slate-700 dark:placeholder:text-slate-400 rounded-full"
                type="text"
                placeholder="Поиск по названию или ИНН" v-model="queryBuilderData.filter.search">
                <i class="fi fi-rr-search pt-1 mr-1"></i>
            </div>
            <div class="overflow-x-auto">
            <table class="w-full whitespace-no-wrap table-fixed">
                <tr class="text-left font-bold">
                    <th @click.prevent="sortBy('name')" class="w-5/12 hover:cursor-pointer hover:bg-gray-600/10 hover:dark:bg-slate-600/80 px-4 py-3 border-b border-gray-200 dark:border-slate-500 bg-gray-200 dark:bg-slate-600 text-gray-500 dark:text-gray-200 text-xs leading-4 font-medium uppercase tracking-wider">Предприятие <span v-html="getSortIcon('name')"></span></th>
                    <th @click.prevent="sortBy('amy')" class="w-2/12 hover:cursor-pointer hover:bg-gray-600/10 hover:dark:bg-slate-600/80 px-4 py-3 border-b border-gray-200 dark:border-slate-500 bg-gray-200 dark:bg-slate-600 text-gray-500 dark:text-gray-200 text-xs leading-4 font-medium uppercase tracking-wider">Средняя ЗП <span v-html="getSortIcon('amy')"></span></th>
                    <th @click.prevent="sortBy('cw')" class="w-2/12 hover:cursor-pointer hover:bg-gray-600/10 hover:dark:bg-slate-600/80 px-4 py-3 border-b border-gray-200 dark:border-slate-500 bg-gray-200 dark:bg-slate-600 text-gray-500 dark:text-gray-200 text-xs leading-4 font-medium uppercase tracking-wider">Работников <span v-html="getSortIcon('cw')"></span></th>
                    <th @click.prevent="sortBy('inn')" class="w-2/12 hover:cursor-pointer hover:bg-gray-600/10 hover:dark:bg-slate-600/80 px-4 py-3 border-b border-gray-200 dark:border-slate-500 bg-gray-200 dark:bg-slate-600 text-gray-500 dark:text-gray-200  text-xs leading-4 font-medium uppercase tracking-wider">ИНН <span v-html="getSortIcon('inn')"></span></th>
                    <th @click.prevent="sortBy('updated_at')" class="w-2/12 hover:cursor-pointer hover:bg-gray-600/10 hover:dark:bg-slate-600/80 px-4 py-3 border-b border-gray-200 dark:border-slate-500 bg-gray-200 dark:bg-slate-600 text-gray-500 dark:text-gray-200 text-xs leading-4 font-medium uppercase tracking-wider">Актуальность <span v-html="getSortIcon('updated_at')"></span></th>
                </tr>
                <tr v-for="item in enterprises.data" :key="item.id" class="hover:bg-slate-400/10 text-gray-500 dark:text-slate-400">
                    <td class="border-t dark:border-slate-500">
                        <Link class="px-5 py-4 flex items-center" :href="route('enterprises.edit', item.id)">
                        <span class="">{{ item.name }}</span>
                        </Link>
                    </td>
                    <td class="border-t dark:border-slate-500">
                        <Link class="px-4 py-4 flex items-center " :href="route('enterprises.edit', item.id)" tabindex="-1">
                        {{ item.amy }}₽
                        </Link>
                    </td>
                    <td class="border-t dark:border-slate-500">
                        <Link class="px-4 py-4 flex items-center " :href="route('enterprises.edit', item.id)" tabindex="-1">
                        {{ item.cw }}
                        </Link>
                    </td>
                    <td class="border-t dark:border-slate-500">
                        <Link class="px-4 py-4 flex items-center " :href="route('enterprises.edit', item.id)" tabindex="-1">
                        {{ item.inn }}
                        </Link>
                    </td>
                    <td class="border-t dark:border-slate-500">
                        <Link class="px-4 py-4 flex items-center " :href="route('enterprises.edit', item.id)" tabindex="-1">
                        {{ item.updated_at ? item.updated_at.replace(/-/g, '-').substr(0, 10) : 'Не актуализирован' }}
                        </Link>
                    </td>

                </tr>
                <tr v-if="enterprises.data.length === 0">
                <td class="border-t px-6 py-4" colspan="4">Ничего нет.</td>
                </tr>
            </table>
            </div>
            <pagination :links="enterprises.links" />
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
            region: Array,
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
                        search : this.queryBuilderProps.search || ""
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
