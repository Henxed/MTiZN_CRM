<template>
    <app-layout :title="`${category_title} - Реестры`">
    <div class="md:flex w-full">
        <div>
            <div class="sticky top-5">
                <div class="bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-300 p-7 rounded-xl md:w-72">
                    <div class="mb-5 text-lg text-center uppercase">Реестры</div>

                    <Link class="block mb-4 p-2 px-4 rounded-xl bg-gray-200/50 dark:bg-slate-600/30"
                            v-for="item in categories" :key="item.id"
                            :class="{ 'bg-gray-300 dark:bg-slate-600 dark:text-slate-300' : item.slug === category}"
                            :href="route('registry.list', [item.slug, 'all'])">
                        {{ item.name }}
                    </Link>
                </div>
                <Link v-if="$page.props.access.can.includes('registry.add') || $page.props.access.role.includes('super-admin')" :href="route('registry.create')" class="block my-4 bg-emerald-400 dark:bg-emerald-600 text-white p-2 px-4 rounded-xl text-center" role="button">Добавить</Link>
            </div>
        </div>
        <div class="rounded-xl bg-white text-slate-900 md:mx-6 dark:bg-slate-800 w-full max-w-screen-2xl">

            <div class="md:sticky top-0 z-40 bg-white dark:bg-slate-800 rounded-xl p-4 md:p-9">
                <div class="text-2xl font-bold dark:text-slate-300 p-5 pb-0 sm:p-0 sm:mb-6 xl:mb-9">{{ category_title }}</div>
                <div class="grid 2xl:flex grid-cols-2 sm:grid-cols-3 xl:grid-cols-7 gap-6 mt-3 dark:text-slate-300 p-5 pt-0 sm:p-0">
                    <Link :href="route('registry.list', [category, 'all'])">
                        <div class="text-2xl font-semibold">{{ count_all }}</div>
                        <div class="uppercase text-xs">{{ declOfNum(count_all, ['Всего реестр', 'Всего реестра', 'Всего реестров']) }}</div>
                    </Link>
                    <Link :href="route('registry.list', [category, 'valid'])">
                        <div class="text-2xl font-semibold">{{ count_true }}</div>
                        <div class="uppercase text-xs break-all">{{ declOfNum(count_true, ['Действителен', 'Действительно', 'Действительно']) }}</div>
                    </Link>
                    <Link :href="route('registry.list', [category, 'change'])">
                        <div class="text-2xl font-semibold">{{ count_change }}</div>
                        <div class="uppercase text-xs">Подлежит изменению</div>
                    </Link>
                    <Link :href="route('registry.list', [category, 'expired'])">
                        <div class="text-2xl font-semibold">{{ count_false }}</div>
                        <div class="uppercase text-xs">{{ declOfNum(count_false, ['Утратил силу', 'Утратили силу', 'Утратило силу']) }}</div>
                    </Link>
                    <Link :href="route('registry.list', [category, 'deadline'])" class="flex-1 ">
                        <div class="text-2xl font-semibold">{{ count_die }}</div>
                        <div class="uppercase text-xs">Истёк срок</div>
                    </Link>
                    <div class="flex items-center xl:col-start-6 xl:col-span-2">
                        <div class="flex items-center bg-gray-200 dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-full max-w-sm w-full pr-3 h-10">
                            <input class="border-none focus:outline-none focus:ring-0 flex-1 h-full w-full p-4 bg-gray-200 dark:bg-slate-700 dark:placeholder:text-slate-400 rounded-full"
                            type="text"
                            placeholder="Поиск реестра..."
                            v-model="form.search">
                            <i class="fi fi-rr-search pt-1 mr-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4 sm:px-9">
                <div class="relative grid grid-cols-1 sm:grid-cols-5 gap-x-4 mb-6 bg-gray-200 dark:bg-slate-600 dark:text-slate-300 p-4 rounded-xl
                    before:absolute before:top-5 before:bottom-5 before:-left-0.5 before:w-1 before:rounded-full hover:cursor-pointer"
                    :class="status_color(item.status.length ? item.status[0].code : null)"
                    v-for="item in regists.data" :key="item" :ref="'reg_'+item.id">
                    <div class="absolute top-0 left-0 h-4/6 w-full sm:w-10/12 sm:h-full" @click="files_open('file_'+item.id)"></div>
                    <div class="sm:col-span-4">
                        <div class="text-base mb-3">{{ item.title }}</div>
                        <div class="text-sm mb-3 text-gray-500 dark:text-slate-400">{{ item.depart }}</div>
                    </div>
                    <div class="flex justify-center sm:justify-end order-last sm:order-none bottom-t-1 mt-3">
                        <Link :href="route('registry.edit', item.id)" class="flex items-center justify-center leading-none h-10 px-2 sm:w-10 rounded-md bg-slate-400/10 text-slate-700 hover:bg-slate-300 dark:text-slate-300/80 dark:hover:bg-slate-500"
                        content="Редактировать" v-tippy='{ placement : "bottom" }'
                        v-if="$page.props.access.can.includes('registry.edit') || $page.props.access.role.includes('super-admin')">
                            <i class="fi fi-rr-edit"></i>
                            <span class="ml-2 sm:hidden">Редактировать</span>
                        </Link>
                        <div class="flex items-center justify-center leading-none mx-2 h-10 px-2 sm:w-10 rounded-md bg-slate-400/10 text-slate-700 hover:bg-slate-300 dark:text-slate-300/80 dark:hover:bg-slate-500"
                        content="Удалить" v-tippy='{ placement : "bottom" }'
                        @click="destroy(item.id)"
                        v-if="$page.props.access.can.includes('registry.delete') || $page.props.access.role.includes('super-admin')">
                            <i class="fi fi-rr-trash"></i>
                            <span class="ml-2 sm:hidden">Удалить</span>
                        </div>
                    </div>

                    <div class="sm:row-start-2 sm:col-span-2 self-end text-xs text-slate-400/70">{{ item.npa }}</div>
                    <div class="sm:row-start-2 sm:col-start-3 sm:col-span-3 self-end sm:text-right text-sm text-slate-400">
                        <span class="mr-4">{{ item.term ? 'До ' + item.term.replace(/-/g, '-').substr(0, 10)  : 'Бессрочный' }}</span>
                        {{ item.status.length ? item.status[0].name : 'Без статуса' }}
                    </div>

                    <div class="relative sm:col-span-5 mt-5 hidden bg-gray-300 dark:bg-slate-500/50 rounded-xl p-6 z-10" :ref="'file_'+item.id">
                        <div class="absolute bg-emerald-400 text-slate-200 dark:text-slate-700 px-4 -top-4 left-auto right-auto rounded-md">ФАЙЛЫ</div>
                        <div class="flex flex-wrap justify-center" v-if="item.files.length">
                            <a class="block text-center hover:bg-slate-400/20 p-3 rounded-lg w-32 z-20" v-for="item in item.files" :key="item" :href="`//${domain}/storage/${item.file}`" download>
                                <div class="text-4xl"><i class="fi fi-rr-file"></i></div>
                                <div class="text-xs h-6 marquee">{{ item.file.split('--&')[1] }}</div>
                                <div class="text-xs text-slate-500/70 dark:text-slate-300/50">{{ item.size ? filesize(item.size) : 'Неизвестно' }}</div>
                            </a>
                        </div>
                        <div class="flex justify-center items-center h-full" v-else>Тут нет файлов</div>
                        <div class="absolute -right-4 -top-4 rounded-lg bg-red-600 text-white w-8 h-8 flex justify-center items-center" @click.stop.prevent="files_close('file_'+item.id)" content="Закрыть" v-tippy='{ placement : "left" }'>X</div>
                    </div>
                </div>

                <div class="my-8 text-center " v-if="regists.data.length === 0">

                    <div v-if="$page.url.split('?')[1] ? ($page.url.split('?')[1].replace(/\=.*/, '') === 'search'? true : false) : false">
                        <div class="text-2xl dark:text-slate-300">Ничего не найдено!</div>
                        <div class="text-lg dark:text-slate-400">Кажется, мы не можем найти никаких реестров по вашему запросу в данной категории.</div>
                        <div class="text-md mt-4 dark:text-slate-400/70">Для лучшего поиска, вы можете ввести номер\реквизит реестра или его наименование.</div>
                    </div>
                    <div v-else>
                        <div class="text-2xl dark:text-slate-300">В данной категории реестров пусто!</div>
                        <div class="text-md break-all dark:text-slate-400">Возможно, следует добавить файлы в данную категорию.</div>
                    </div>
                </div>
                <pagination :links="regists.links" />
            </div>
        </div>
    </div>
    </app-layout>
</template>


<script>
    import { defineComponent } from 'vue';
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import AppLayout from '@/Layouts/AppLayout';
    import {declOfNum} from '@/Functions/DeclOfNum';
    import throttle from 'lodash/throttle'
    import pickBy from 'lodash/pickBy'
    import Pagination from '@/Shared/Pagination'

    export default defineComponent({
        components: {
            AppLayout,
            Head,
            Link,
            Pagination
        },
        props: {
            regists: Object,
            filters: Object,
            categories: Array,
            category_title: String,
            category: String,
            count_all: Number,
            count_true: Number,
            count_change: Number,
            count_false: Number,
            count_die: Number,
        },
        data() {
            return {
                domain: window.location.host,
                form: {
                    search: this.filters.search,
                },
            }
        },
        watch: {
            form: {
                deep: true,
                handler: throttle(function () {
                    this.$inertia.get(this.route('registry', [this.category, this.$page.url.split('/')[3].replace(/\?.*/, '')]), pickBy(this.form), { preserveState: true })
                }, 150),
            },
        },
        methods: {
            declOfNum: declOfNum,
            filesize(size) {
                var i = Math.floor(Math.log(size) / Math.log(1024))
                return (size / Math.pow(1024, i) ).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i]
            },
            files_open: function(e){
                this.regists.data.forEach(element => {
                    if(this.$refs['file_'+element.id][0] !== this.$refs[e][0]){
                        this.$refs['file_'+element.id][0].classList.add('hidden')
                    }
                });
                this.$refs[e][0].classList.toggle('hidden')
            },
            files_close: function(e){
                this.$refs[e][0].classList.add('hidden')
            },
            status_color: function(e){
                switch (e) {
                    case 100:
                        return 'before:bg-emerald-400'
                    break;
                    case 101:
                        return 'before:bg-amber-400'
                    break;
                    case 102:
                        return 'before:bg-pink-400'
                    break;
                    case 103:
                        return 'before:bg-red-500'
                    break;
                    default:
                        return 'before:bg-slate-400'
                        break;
                }
            },
             destroy(e) {
                if (confirm('Вы уверены, что хотите удалить этот реестр?')) {
                    this.$toast.open({message: 'Удаляю реестр... Ожидайте!'})
                    axios.post(route('registry.destroy', e), {_method: 'delete'} ).then(() => {
                        this.$refs['reg_'+e][0].remove()
                        this.$toast.open({message: 'Реестр удален!'})
                    })
                }
            },
        }
    })
</script>

