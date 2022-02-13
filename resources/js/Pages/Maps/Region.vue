<template>
    <app-layout :title="`${region.region} - Регион`">
    <div class="lg:flex w-full">
        <div>
            <div class="lg:sticky lg:top-12 mr-6">
                <div class="dark:text-slate-300 sm:w-96">
                    <div class="mb-5 text-lg text-center uppercase">Регионы</div>
                    <perfect-scrollbar class="h-80 max-h-screen pr-3">
                    <Link class="block mb-3 py-2 px-3 rounded-lg bg-slate-600/10 hover:bg-slate-600/20 dark:bg-slate-400/10 dark:hover:bg-slate-400/20 text-sm"
                            v-for="item in regions" :key="item.id"
                            :class="{ 'bg-gray-300 dark:bg-slate-600 dark:text-slate-300' : item.id === region.id}"
                            :href="route('regions.show', item.id)">
                        {{ item.region }}
                    </Link>
                    </perfect-scrollbar>
                </div>
            </div>
        </div>
        <div class="w-full max-w-screen-xl">

            <div class="text-2xl font-bold dark:text-slate-300 p-5 pb-0 sm:p-0 sm:mb-6 xl:mb-9">{{ region.region }}</div>
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

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
                <div class="bg-slate-600/10 dark:bg-slate-400/10 p-6 rounded-xl">
                    <div class="text-xl font-bold dark:text-slate-300">Информация по учебным заведениям</div>
                    <div class="grid grid-cols-2 2xl:flex gap-6 sm:gap-12 mt-3 dark:text-slate-400 p-5 pt-0 sm:p-0">
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
                            <div class="text-sm"> {{ declOfNum(region.extra.detdom, ['Детский дом\\интернат', 'Детских дома\\интерната', 'Детских домов\\интернатов']) }}</div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-600/10 dark:bg-slate-400/10 p-6 rounded-xl">
                    <div class="text-xl font-bold dark:text-slate-300">Регистрируемый рынок труда</div>
                    <div class="grid grid-cols-2 2xl:flex gap-6 sm:gap-12 mt-3 dark:text-slate-400 p-5 pt-0 sm:p-0">
                        <div>
                            <div class="text-2xl font-semibold">{{ region.unemployed || '-' }}</div>
                            <div class="text-sm">{{ declOfNum(region.unemployed, ['Безработный', 'Безработных', 'Безработных']) }}</div>
                        </div>
                        <div>
                            <div class="text-2xl font-semibold">{{ region.lvl || '-' }}%</div>
                            <div class="text-sm">Уровень безработицы</div>
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
                    <Link class="block text-slate-600 dark:text-slate-300 mb-2 leading-thin" v-for="(item, index) in region.selsoviet" :key="item">
                        {{ index+1 }}. {{ item.name }}
                    </Link>
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
        },
        data() {
            return {
                domain: window.location.host,
            }
        },

        methods: {
            declOfNum: declOfNum,

        }
    })
</script>
<style src="vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.css"/>
