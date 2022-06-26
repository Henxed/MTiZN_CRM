<template>
    <app-layout :title="'Новые данные по партнеру'">

 <div class="text-slate-900 dark:text-slate-100 w-full max-w-screen-2xl">
        <div class="py-4">
            <div class="text-3xl text-slate-700 dark:text-slate-200 uppercase">Новые данные по партнеру</div>
        </div>

        <form @submit.prevent="submit" class="border-t border-gray-200 dark:border-slate-700 py-6">

            <div class="mt-10 sm:mt-0">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0 sticky top-5">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-slate-200">Партнерская информация</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-slate-400">Дополнительная информацию предприятия по договору.</p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">

                    <div class="shadow sm:rounded-md">
                        <div class="px-4 py-5 bg-white dark:bg-slate-800 sm:p-6">
                            <div class="mb-2">
                                <label class="form-label" for="enterprise_id" :class="{error : errors.enterprise_id}">{{ $t(`inputs.safety.enterprise_id`) }}:</label>
                                <treeselect v-model="form.enterprise_id" :load-options="loadOptions" :class="{error : errors.enterprise_id}" :async="true" :options="options" placeholder="Предприятия..." id="enterprise_id" noResultsText="Нет результата" loadingText="Ищу предприятия..." searchPromptText="Начните вводить название или ИНН" />
                                <div v-if="errors.enterprise_id" class="form-error">{{ errors.enterprise_id }}</div>
                            </div>

                            <text-input v-model="form.collective_agreement" :error="errors.collective_agreement" :label="$t(`inputs.safety.collective_agreement`)" type="date" />
                            <text-input v-model="form.sum_contractual" :error="errors.sum_contractual" :label="$t(`inputs.safety.sum_contractual`)" type='number' />

                            <div class="text-lg text-center mt-6">{{ $t(`inputs.safety.accidents`) }}</div>
                            <div class="flex flex-wrap mt-2 bg-slate-200 dark:bg-slate-600/20 rounded-lg p-4">
                                <div class="text-lg w-full font-bold text-slate-500 dark:text-slate-400">{{ $t(`inputs.safety.group`) }}</div>
                                <text-input v-model="form.accidents_group_at" :error="errors.accidents_group_at" :label="$t(`inputs.safety.accidents_group_at`)" type="date" class="w-full lg:pr-2 lg:w-1/2" :required="!!form.accidents_group" />
                                <text-input v-model="form.accidents_group" :error="errors.accidents_group" :label="$t(`inputs.safety.accidents_group`)" class="w-full lg:pl-2 lg:w-1/2" type='number' :required="!!form.accidents_group_at" placeholder="Оставьте пустым, если нет данных"/>
                            </div>
                            <div class="flex flex-wrap mt-4 bg-slate-200 dark:bg-slate-600/20 rounded-lg p-4">
                                <div class="text-lg w-full font-bold text-slate-500 dark:text-slate-400">{{ $t(`inputs.safety.heavy`) }}</div>
                                <text-input v-model="form.accidents_heavy_at" :error="errors.accidents_heavy_at" :label="$t(`inputs.safety.accidents_heavy_at`)" type="date" class="w-full lg:pr-2 lg:w-1/2" :required="!!form.accidents_heavy" />
                                <text-input v-model="form.accidents_heavy" :error="errors.accidents_heavy" :label="$t(`inputs.safety.accidents_heavy`)" class="w-full lg:pl-2 lg:w-1/2" type='number' :required="!!form.accidents_heavy_at" placeholder="Оставьте пустым, если нет данных"/>
                            </div>
                            <div class="flex flex-wrap mt-4 bg-slate-200 dark:bg-slate-600/20 rounded-lg p-4">
                                <div class="text-lg w-full font-bold text-slate-500 dark:text-slate-400">{{ $t(`inputs.safety.deadly`) }}</div>
                                <text-input v-model="form.accidents_deadly_at" :error="errors.accidents_deadly_at" :label="$t(`inputs.safety.accidents_deadly_at`)" type="date" class="w-full lg:pr-2 lg:w-1/2" :required="!!form.accidents_deadly" />
                                <text-input v-model="form.accidents_deadly" :error="errors.accidents_deadly" :label="$t(`inputs.safety.accidents_deadly`)" class="w-full lg:pl-2 lg:w-1/2" type='number' :required="!!form.accidents_deadly_at" placeholder="Оставьте пустым, если нет данных"/>
                            </div>

                            <div class="text-lg mt-6">{{ $t(`inputs.safety.learn_safety`) }}</div>
                            <div class="flex flex-wrap ">
                                <text-input v-model="form.in_total" :error="errors.in_total" :label="$t(`inputs.safety.in_total`)" class="w-full lg:pr-2 lg:w-1/2" type='number' />
                                <text-input v-model="form.start_year" :error="errors.start_year" :label="$t(`inputs.safety.start_year`)" class="w-full lg:pl-2 lg:w-1/2" type='number' />
                            </div>
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
import {ASYNC_SEARCH, Treeselect} from '@bosquig/vue3-treeselect'


export default {
    components: {
        AppLayout,
        LoadingButton,
        SelectInput,
        TextInput,
        Link,
        Treeselect
    },
    props: {
        errors: Object,
    },
    data() {
        return {
            form: this.$inertia.form({
                enterprise_id: null,
                sum_contractual: '',
                collective_agreement: '',
                accidents_group_at: '',
                accidents_group: '',
                accidents_heavy_at: '',
                accidents_heavy: '',
                accidents_deadly_at: '',
                accidents_deadly: '',
                in_total: '',
                start_year: ''
            }),
            options: []
        }
    },
    methods: {
        submit() {
            this.form.post(route('safety.partners.store'))
        },
        async loadOptions({ action, searchQuery, callback }) {
            if (action === ASYNC_SEARCH) {
                await axios.post(route('enterpises.get.all') + '?filter[search]=' + searchQuery).then((res) => {
                    callback(null, res.data.data)
                    this.options = res.data.data
                })
            }
        },
    },
}
</script>
