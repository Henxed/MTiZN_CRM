<template>
    <app-layout :title="`Предприятия всех районов`">

        <div class="w-full max-w-screen-2xl">
            <div class="flex justify-between items-center align-center pb-4">
                <div class="text-3xl uppercase text-slate-600 dark:text-slate-300 p-5 pb-0 sm:p-0">Все предприятия районов</div>
                <div class="icon-list hover:bg-slate-600/50 dark:hover:bg-slate-500/50 cursor-pointer" @click.prevent="open=true" v-tippy="{ placement : 'left', content: 'Аналитика' }">
                    <i class="fi fi-rr-stats"></i>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 items-center">
                <breadcrumbs :data="bread" class="my-4"/>
                <div class="flex items-center justify-self-end text-slate-600 dark:text-slate-400">
                    По области - <span class="ml-2 rounded-xl bg-slate-300 dark:bg-slate-600/50 py-1 px-2">{{ enterprises_count }}</span>
                </div>
            </div>
            <div class="grid card p-4">
                <div class="flex">
                    <div class="mr-auto mb-6 flex items-center bg-gray-200 dark:bg-slate-600/50 text-slate-900 dark:text-slate-100 rounded-full max-w-sm w-full pr-3 h-10">
                        <input class="border-none focus:outline-none focus:ring-0 flex-1 h-full w-full p-4 bg-transparent dark:placeholder:text-slate-400 rounded-full"
                        type="text"
                        placeholder="Поиск по названию или ИНН" v-model="queryBuilderData.filter.search">
                        <i class="fi fi-rr-search pt-1 mr-1"></i>
                    </div>
                    <div class="text-gray-400" v-if="amy_status">
                        от <input type="number" placeholder="мин. ЗП" v-model.lazy="queryBuilderData.filter.min_amy" class="border-none focus:outline-none focus:ring-0 bg-gray-200 dark:bg-slate-600/50 dark:placeholder:text-slate-400 rounded-full">
                        до <input type="number" placeholder="макс. ЗП" v-model.lazy="queryBuilderData.filter.max_amy" class="border-none focus:outline-none focus:ring-0 bg-gray-200 dark:bg-slate-600/50 dark:placeholder:text-slate-400 rounded-full">
                    </div>
                </div>
                <perfect-scrollbar class="table-container">
                <table class="table-auto min-w-full h-3/6 relative">
                    <thead>
                    <tr class="text-left font-bold h-9">
                        <th v-for="item in table" :key="item" @click.prevent="sortBy(item)" class="sticky top-0 z-20 max-w-sm w-full hover:cursor-pointer ">
                            <div class="th hover:bg-gray-300 hover:dark:bg-slate-600 px-4 py-3 border-b border-gray-200 dark:border-slate-500/30 bg-gray-200 dark:bg-slate-700 text-gray-500 dark:text-gray-200 text-xs leading-4 font-medium uppercase tracking-wider"><span v-html="getSortIcon(item)"></span> {{ $t(`inputs.ent.${item}`) }} </div>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item in enterprises.data" :key="item.id" class="hover:bg-slate-400/10 dark:hover:bg-slate-600/30 text-gray-500 dark:text-slate-400 text-sm">
                        <td v-for="t in table" :key="t" class="border-t dark:border-slate-500/30">
                            <Link class="px-5 py-4 flex items-center min-w-20" :href="route('regions.enterprises.show', [item.area_id, item.id])">
                            {{ type(t, item) }}
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
        <analytics v-model:open="open" @open="hasOpen" />
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
    import Breadcrumbs from '@/Shared/Breadcrumbs'
    import Analytics from '@/Shared/AnalyticsEnterprise'

    export default defineComponent({
        components: {
            AppLayout,
            Head,
            Link,
            PerfectScrollbar,
            Pagination,
            Breadcrumbs,
            Analytics
        },
        props: {
            enterprises_count: Number,
            enterprises: Object,
            table: Array,
            queryBuilderProps: {
                type: Object,
                required: true,
            },
        },
        data() {
            return {
                bread: [
                    {
                        title: 'Районы',
                        url: route('regions.index'),
                    },
                    {
                        title: 'Предприятия всей области',
                        current: true,
                    },
                ],
                open: false,
                amy_status: this.table.filter(e => e === 'amy').length ? true : false,
                queryBuilderData: {
                    page: this.queryBuilderProps.page || 1,
                    sort: this.queryBuilderProps.sort || "",
                    filter: {
                        min_amy: this.queryBuilderProps.filter.min_amy || "",
                        max_amy: this.queryBuilderProps.filter.max_amy || "",
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
            },
            type(t, item){
                switch (t) {
                    case 'updated_at':
                        return item['updated_at'] ? item['updated_at'].replace(/-/g, '-').substr(0, 10) : 'Не актуализирован';
                        break;
                    case 'status_id':
                        return  item.status ? item.status.name : 'Без статуса'
                        break;
                    case 'amy':
                        return  item.amy ? this.ruble(item.amy)+'₽' : 'Нет данных'
                        break;
                    default:
                        return  item[t] ? item[t] : 'Нет данных'
                        break;
                }
            },
            ruble(number) {
                return new Intl.NumberFormat('ru-RU', { maximumSignificantDigits: 3 }).format(number)
            },
            hasOpen(e) {
                this.open = e
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
    height: 65vh;
}
.th{
    padding: 10px;
    display: block;
    position: absolute;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 100%;
    top: 0;
    left: 0;
}
.th:hover {
    white-space: normal;
    z-index: 1;
}
</style>
