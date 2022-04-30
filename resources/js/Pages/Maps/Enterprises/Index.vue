<template>
    <app-layout :title="`${region.region} - Предприятия`">

        <div class="w-full max-w-screen-2xl">
            <div class="flex justify-between items-center align-center">
                <div class="py-8">
                    <Link :href="route('regions.show', region.id)" v-tippy='"Вернуться на страницу региона"' class="text-3xl uppercase text-slate-600 dark:text-slate-400 p-5 pb-0 sm:p-0 sm:mb-6 xl:mb-9">
                            {{ region.region }}
                    </Link>
                    <div class="text-2xl dark:text-slate-300">Предприятия региона</div>

                </div>
                <Link class="btn-green ml-auto" :href="route('regions.enterprises.create', region.id)"
                v-if="$page.props.access.can.includes('enterprise.create') || $page.props.access.role.includes('super-admin') || $page.props.access_region.includes(region.id)">
                    <span>Новое</span>
                    <span class="hidden md:inline"> предприятие</span>
                </Link>
            </div>
            <div class="grid rounded-xl shadow bg-white text-slate-900 dark:bg-slate-800 p-4">
                <div class="mb-6 flex items-center bg-gray-200 dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-full max-w-sm w-full pr-3 h-10">
                    <input class="border-none focus:outline-none focus:ring-0 flex-1 h-full w-full p-4 bg-gray-200 dark:bg-slate-700 dark:placeholder:text-slate-400 rounded-full"
                    type="text"
                    placeholder="Поиск по названию или ИНН" v-model="queryBuilderData.filter.search">
                    <i class="fi fi-rr-search pt-1 mr-1"></i>
                </div>
                <perfect-scrollbar :options="{suppressScrollY: true}">
                <table class="table-auto min-w-full">
                    <tr class="text-left font-bold">
                        <th v-for="item in table" :key="item" @click.prevent="sortBy(item)" class="max-w-sm w-full hover:cursor-pointer hover:bg-gray-600/10 hover:dark:bg-slate-600/80 px-4 py-3 border-b border-gray-200 dark:border-slate-500 bg-gray-200 dark:bg-slate-600 text-gray-500 dark:text-gray-200  text-xs leading-4 font-medium uppercase tracking-wider">{{ $t(`inputs.ent.${item}`) }} <span v-html="getSortIcon(item)"></span></th>
                    </tr>
                    <tr v-for="item in enterprises.data" :key="item.id" class="hover:bg-slate-400/10 text-gray-500 dark:text-slate-400 text-sm">

                        <td v-for="t in table" :key="t" class="border-t dark:border-slate-500">
                            <Link class="px-5 py-4 flex items-center min-w-20" :href="route('enterprises.show', item.id)">
                            {{ type(t, item) }}
                            </Link>
                        </td>
                    </tr>
                    <tr v-if="enterprises.data.length === 0">
                    <td class="border-t px-6 py-4" colspan="4">Ничего нет.</td>
                    </tr>
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
            region: Array,
            enterprises: Object,
            access_region: Array,
            table: Array,
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
            },
            type(t, item){
                switch (t) {
                    case 'updated_at':
                        return item['updated_at'] ? item['updated_at'].replace(/-/g, '-').substr(0, 10) : 'Не актуализирован';
                        break;
                    case 'status_id':
                        return  item.status ? item.status.name : 'Без статуса'
                        break;
                    default:
                        return  item[t] ? item[t] : 'Нет данных'
                        break;
                }
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
