<template>
    <app-layout :title="'Новое предприятие - ' + region.region">

 <div class="text-slate-900 dark:text-slate-100 w-full max-w-screen-2xl">
        <div class="py-8">
            <Link :href="route('regions.enterprises', region.id)" class="text-3xl text-slate-700 dark:text-slate-200 dark:hover:text-pink-600 hover:text-pink-500 uppercase">Новое предприятие</Link>
        </div>
        <form @submit.prevent="submit" class="border-t border-gray-200 dark:border-slate-700 py-6">

            <div class="mt-10 sm:mt-0">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0 sticky top-5">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-slate-200">Общая информация</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-slate-400">Основная информацию предприятия.</p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">

                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white dark:bg-slate-800 sm:p-6">

                            <text-input v-model="form.name" :error="errors.name" label="Наименование" />
                            <text-input v-model="form.inn" :error="errors.inn" label="ИНН" />
                            <text-input v-model="form.rns" :error="errors.rns" label="Регистрационный номер страхователя" />
                            <text-input v-model="form.address" :error="errors.address" label="Адрес" />
                            <text-input v-model="form.okvd" :error="errors.okvd" label="ОКВЭД" required />
                            <text-input v-model="form.okvd_name" :error="errors.okvd_name" label="ОКВЭД наименование" required />
                            <select-input v-model="form.status_id" :error="errors.status_id" label="Статус">
                                <option v-for="item in statuses" :key="item" :value="item.code">{{ item.name }}</option>
                            </select-input>
                        </div>

                    </div>

                </div>
                </div>
            </div>

            <div class="hidden sm:block" aria-hidden="true">
                <div class="py-5">
                <div class="border-t border-gray-200 dark:border-slate-700" />
                </div>
            </div>

            <div class="mt-10 sm:mt-0">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0 sticky top-5">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-slate-200">Статистика</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-slate-400">Статистическая информация предприятия.</p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">

                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white dark:bg-slate-800 sm:p-6">
                            <text-input v-model="form.amy" :error="errors.amy" label="Средняя ЗП за текущий год" type='number'/>
                            <text-input v-model="form.total_jobs" :error="errors.total_jobs" label="Общее количество рабочих мест" type="number"/>
                            <text-input v-model="form.cw" :error="errors.cw" label="Сотрудников на текущую дату" type='number'/>
                            <text-input v-model="form.ane" :error="errors.ane" label="Среднесписочная численность работников" type='number' />
                            <text-input v-model="form.nde" :error="errors.nde" label="Численность работающих инвалидов" type='number' />
                            <text-input v-model="form.factors" :error="errors.factors" label="Численность работников, занятых на работах с вредными и (или) опасными производственными факторами" type="number"/>
                            <text-input v-model="form.workplaces_respect" :error="errors.workplaces_respect" label="Количество рабочих мест, в отношении условий труда на которых проведена специальная оценка условий труда на начало года, всего" type="number"/>
                            <text-input v-model="form.workplaces_three" :error="errors.workplaces_three" label="Количество рабочих мест, в отношении условий труда на которых проведена специальная оценка условий труда на начало года, в том числе отнесенных к вредным и опасным условиям труда 3 класс" type="number"/>
                            <text-input v-model="form.workplaces_four" :error="errors.workplaces_four" label="Количество рабочих мест, в отношении условий труда на которых проведена специальная оценка условий труда на начало года, в том числе отнесенных к вредным и опасным условиям труда 4 класс" type="number"/>
                            <text-input v-model="form.total_factors" :error="errors.total_factors" label="Общее количество работников, занятых на работах с вредными и (или) опасными производственными факторами, подлежащих обязательным предварительным и периодическим медицинским осмотрам (чел.)" type="number"/>
                            <text-input v-model="form.start_year_factors" :error="errors.start_year_factors" label="Количество работников, занятых на работах с вредными и (или) опасными производственными факторами, прошедших обязательные предварительные и периодические медицинские осмотры на начало года (чел.)" type="number"/>
                        </div>

                    </div>

                </div>
                </div>
            </div>

            <div class="hidden sm:block" aria-hidden="true">
                <div class="py-5">
                <div class="border-t border-gray-200 dark:border-slate-700" />
                </div>
            </div>

            <div class="mt-6">
                <loading-button :loading="form.processing" class="btn-green mx-auto w-full max-w-xs" type="submit">Добавить</loading-button>
            </div>
        </form>
    </div>

    </app-layout>
</template>

<script>

import AppLayout from '@/Layouts/AppLayout'
import LoadingButton from '@/Shared/LoadingButton'
import SelectInput from '@/Shared/SelectInput'
import TextInput from '@/Shared/TextInput'
import { Link } from '@inertiajs/inertia-vue3'


export default {
    components: {
        AppLayout,
        LoadingButton,
        SelectInput,
        TextInput,
        Link
    },
    props: {
        region: Array,
        statuses: Array,
        errors: Object,
    },
    data() {
        return {
        form: this.$inertia.form({
           name: '',
            amy: '',
            cw: '',
            inn: '',
            area_id: this.region.id,
            rns: '',
            status_id: '',
            okvd: '',
            okvd_name: '',
            ane: '',
            nde: '',
            factors: '',
            total_jobs: '',
            workplaces_respect: '',
            workplaces_three: '',
            workplaces_four	: '',
            total_factors: '',
            start_year_factors: '',
            address: '',
        })
        }
    },
    methods: {
        submit() {
            this.$toast.open({message: 'Добавляю... Ожидайте!', type: 'default'})
            this.form.post(route('enterprises.store'))
        }

    },
}
</script>
