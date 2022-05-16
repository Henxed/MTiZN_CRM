<template>
    <app-layout :title="`${region.region} - Регион`">
    <div class="lg:flex w-full">
        <div class="sidebar max-w-sm">
            <div class="lg:sticky lg:top-12 mr-6">
                <div class="dark:text-slate-300 ">
                    <div class="mb-5 text-lg text-center uppercase">Регионы</div>
                    <perfect-scrollbar class="h-screen-85 max-h-screen pr-3">
                    <Link class="block py-1.5 px-3 mb-1 rounded-lg hover:bg-slate-600/20 dark:hover:bg-slate-400/20 text-13"
                            v-for="item in regions" :key="item.id" :ref="`region_${item.id}`"
                            :class="{ 'bg-gray-300 dark:bg-slate-600 dark:text-slate-300' : item.id === region.id}"
                            :href="route('regions.show', item.id)">
                        {{ item.region }}
                    </Link>
                    </perfect-scrollbar>
                </div>
            </div>
        </div>
        <div class="w-5/6 max-w-screen-xl">

            <div class="text-2xl font-bold dark:text-slate-300 p-5 pb-0 sm:p-0 sm:mb-6 xl:mb-9">
                {{ region.region }}
                <Link :href="route('regions.edit', region.id)"  v-tippy="'Редактировать регион'" class="inline-block align-middle ml-3 text-slate-700 dark:text-slate-400 dark:hover:text-pink-600 hover:text-pink-500" v-if="$page.props.access.can.includes('region.edit') || $page.props.access.role.includes('super-admin') || $page.props.access_region.includes(region.id)">
                    <i class="fi fi-rr-edit"></i>
                </Link>
            </div>
            <div class="bg-slate-600/10 dark:bg-slate-400/10 p-6 rounded-xl grid grid-cols-1 sm:grid-cols-3 gap-4">

                <div class="flex sm:col-span-2 items-center">
                    <div class="rounded-lg text-slate-600 bg-slate-400/60 dark:text-slate-300 w-9 h-9 flex items-center justify-center text-lg leading-none">
                        <i class="fi fi-rr-user"></i>
                    </div>
                    <div class="ml-4 leading-snug">
                        <div class="font-bold text-slate-600 dark:text-slate-400 stroke-current">Глава</div>
                        <div class="text-slate-500">{{ region.leader }}</div>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="rounded-lg text-slate-600 bg-slate-400/60 dark:text-slate-300 w-9 h-9 flex items-center justify-center text-lg leading-none">
                        <i class="fi fi-rr-marker"></i>
                    </div>
                    <div class="ml-4 leading-snug">
                        <div class="font-bold text-slate-600 dark:text-slate-400 stroke-current">Площадь</div>
                        <div class="text-slate-500">{{ region.area }} км<sup>2</sup></div>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="rounded-lg text-slate-600 bg-slate-400/60 dark:text-slate-300 w-9 h-9 flex items-center justify-center text-lg leading-none">
                        ₽
                    </div>
                    <div class="ml-4 leading-snug">
                        <div class="font-bold text-slate-600 dark:text-slate-400 stroke-current">Средняя заработная плата</div>
                        <div class="text-slate-500">{{ region.amw }}₽</div>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="rounded-lg text-slate-600 bg-slate-400/60 dark:text-slate-300 w-9 h-9 flex items-center justify-center text-lg leading-none">
                        <i class="fi fi-rr-school"></i>
                    </div>
                    <div class="ml-4 leading-snug">
                        <div class="font-bold text-slate-600 dark:text-slate-400 stroke-current">Районый центр</div>
                        <div class="text-slate-500">{{ region.city }}</div>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="rounded-lg text-slate-600 bg-slate-400/60 dark:text-slate-300 w-9 h-9 flex items-center justify-center text-lg leading-none">
                        <i class="fi fi-rr-building pt-1"></i>
                    </div>
                    <div class="ml-4 leading-snug">
                        <div class="font-bold text-slate-600 dark:text-slate-400 stroke-current">Население</div>
                        <div class="text-slate-500">{{ region.population }}</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 my-6">
                <div class="bg-slate-600/10 dark:bg-slate-400/10 p-6 rounded-xl">
                    <div class="text-xl font-bold dark:text-slate-300">Информация по учебным заведениям</div>
                    <div class="grid grid-cols-2 sm:flex gap-6 sm:gap-12 mt-3 dark:text-slate-400 p-5 pt-0 sm:p-0">
                        <div v>
                            <div class="text-2xl font-semibold">{{ region.extra.vvuz || '-'}}</div>
                            <div class="text-sm">{{ declOfNum(region.extra.vvuz, ['ВВУЗ', 'ВВУЗа', 'ВВУЗов']) }}</div>
                        </div>
                        <div>
                            <div class="text-2xl font-semibold">{{ region.extra.ssuz || '-' }}</div>
                            <div class="text-sm">{{ declOfNum(region.extra.ssuz, ['ССУЗ', 'ССУЗа', 'ССУЗов']) }}</div>
                        </div>
                        <div>
                            <div class="text-2xl font-semibold">{{ region.extra.school || '-' }}</div>
                            <div class="text-sm">{{ declOfNum(region.extra.school, ['Школа', 'Школы', 'Школ']) }}</div>
                        </div>
                        <div >
                            <div class="text-2xl font-semibold">{{ region.extra.detdom || '-' }}</div>
                            <div class="text-sm"> {{ declOfNum(region.extra.detdom, ['Детский дом, интернат', 'Детских дома, интерната', 'Детских домов, интернатов']) }}</div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-600/10 dark:bg-slate-400/10 p-6 rounded-xl">
                    <div class="text-xl font-bold dark:text-slate-300">Регистрируемый рынок труда</div>
                    <div class="grid grid-cols-2 sm:flex gap-4 2xl:gap-12  mt-3 dark:text-slate-400 p-5 pt-0 sm:p-0">
                        <div>
                            <div class="text-2xl font-semibold">{{ region.unemployed || '-' }}</div>
                            <div class="text-sm">{{ declOfNum(region.unemployed, ['Безработный', 'Безработных', 'Безработных']) }}</div>
                        </div>
                        <div>
                            <div class="text-2xl font-semibold">{{ region.lvl || '-' }}%</div>
                            <div class="text-sm">Безработица</div>
                        </div>
                        <div>
                            <div class="text-2xl font-semibold">{{ region.tension || '-' }}%</div>
                            <div class="text-sm">Напряженность</div>
                        </div>
                        <div >
                            <div class="text-2xl font-semibold">{{ region.vacancy || '-' }}</div>
                            <div class="text-sm"> {{ declOfNum(region.vacancy, ['Вакансия', 'Вакансии', 'Вакансий']) }}</div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-600/10 dark:bg-slate-400/10 p-6 rounded-xl">
                    <div class="text-2xl font-bold dark:text-slate-300">Информация по сельским советам</div>
                    <perfect-scrollbar class="pr-3">
                    <Link class="block text-slate-700 dark:text-slate-400 dark:hover:text-pink-600 hover:text-pink-500 leading-thin" v-for="(item, index) in region.selsoviet" :key="item">
                        {{ index+1 }}. {{ item.name }}
                    </Link>
                    </perfect-scrollbar>
                </div>
                <div class="">
                    <div class="flex bg-slate-600/10 dark:bg-slate-400/10 p-4
                    mb-6 rounded-xl text-lg dark:text-slate-300 leading-tight">
                        <div class="w-4/6">Численность работников под риском увольнения</div>
                        <div class="w-2/6 text-center">
                            <div class="text-2xl font-semibold">{{ sum_people_dismissal || '-' }}</div>
                            <div class="text-sm -mt-2"> {{ declOfNum(sum_people_dismissal, ['человек', 'человека', 'человек']) }}</div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 ">
                        <Link :href="route('regions.enterprises', region.id)" class="flex bg-indigo-200 dark:bg-indigo-500/30 p-3 rounded-xl overflow-hidden relative h-48 min-w-48 w-full max-w-64">
                            <div class="absolute -right-5 -bottom-8 text-indigo-300 dark:text-indigo-300/30 text-9xl"><i class="fi fi-rr-shop"></i></div>
                            <div class="text-lg text-indigo-500">Предприятия</div>
                        </Link>

                        <Link :href="route('registry.list', ['npa', 'all'])" class="flex bg-amber-200 dark:bg-amber-500/30 p-3 rounded-xl overflow-hidden relative h-48 min-w-48 w-full max-w-64">
                            <div class="absolute -right-5 -bottom-8 text-amber-300 dark:text-amber-300/30 text-9xl"><i class="fi fi-rr-document-signed"></i></div>
                            <div class="text-lg text-amber-500">Реестр санкций</div>
                        </Link>
            <!--
                        <Link href="#" class="flex bg-green-200 dark:bg-green-500/30 p-3 rounded-xl overflow-hidden relative h-48 min-w-48 w-full max-w-64">
                            <div class="absolute -right-5 -bottom-10 text-green-300 dark:text-green-300/30 text-9xl"><i class="fi fi-rr-bank"></i></div>
                            <div class="text-lg text-green-500">Сельсоветы</div>
                        </Link> -->
                    </div>
                </div>
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
    import { PerfectScrollbar } from 'vue3-perfect-scrollbar'

    export default defineComponent({
        components: {
            AppLayout,
            Head,
            Link,
            PerfectScrollbar,
        },
        props: {
            regions: Array,
            region: Array,
            access_region: Array,
            sum_people_dismissal: Number
        },
        data() {
            return {
                domain: window.location.host,
            }
        },
        mounted(){

        },
        methods: {
            declOfNum: declOfNum,

        }
    })
</script>
<style src="vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.css"/>
<style scoped>
.sidebar{
    width: 30%;
}
</style>
