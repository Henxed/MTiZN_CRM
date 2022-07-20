<template>
    <app-layout :title="'Новые данные по партнеру'">

 <div class="text-slate-900 dark:text-slate-100 w-full max-w-screen-2xl">
        <div class="py-4">
            <div class="text-3xl text-slate-700 dark:text-slate-200 uppercase">Редактировать данные у "{{ enterprise.label }}"</div>
        </div>
        <breadcrumbs :data="bread" class="my-4"/>
        <form @submit.prevent="submit" class="border-t border-gray-200 dark:border-slate-700 py-6">

            <div class="mt-10 sm:mt-0">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0 sticky top-5">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-slate-200">Партнерская информация</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-slate-400">Дополнительная информация предприятия по договору.</p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">


                        <div class="px-4 py-5 card sm:p-6">

                            <div class="mb-2">
                                <label class="form-label" for="enterprise_id" :class="{error : errors.enterprise_id}">{{ $t(`inputs.safety.enterprise_id`) }}:</label>
                                <treeselect v-model="form.enterprise_id" :load-options="loadOptions" :defaultOptions="[enterprise]" :async="true" :class="{error : errors.enterprise_id}" placeholder="Найти другое предприятие..." id="enterprise_id" noResultsText="Нет результата" loadingText="Ищу предприятия..." searchPromptText="Начните вводить название или ИНН" />
                                <div v-if="errors.enterprise_id" class="form-error">{{ errors.enterprise_id }}</div>
                                <small v-if="form.enterprise_id && area_id">Редактировать предприятие в <a :href="route('regions.enterprises.edit', [area_id, form.enterprise_id])" target="_blank" class="underline">новой вкладке</a></small>
                            </div>

                            <text-input v-model="form.collective_agreement" :error="errors.collective_agreement" :label="$t(`inputs.safety.collective_agreement`)" type="date" :required="!!form.sum_contractual"/>
                            <text-input v-model="form.sum_contractual" :error="errors.sum_contractual" :label="$t(`inputs.safety.sum_contractual`)" type='number' :required="!!form.collective_agreement"/>

                            <div class="text-lg font-bold mt-6 text-slate-500 dark:text-slate-400">{{ $t(`inputs.safety.accidents`) }}</div>
                            <div class="mt-2 bg-slate-200 dark:bg-slate-600/20 rounded-lg p-4">
                                <div class="inline-block font-bold text-base mb-2 -ml-6 pl-6 pr-3 text-slate-500 dark:text-slate-400  rounded-lg bg-slate-300 dark:bg-slate-600">{{ $t(`inputs.safety.group`) }}</div>

                                <div class="grid lg:grid-cols-7" v-for="(input, index) in form.accidents_group_list" :key="`groupInput-${index}`">
                                    <text-input v-model="input.date" :label="$t(`inputs.safety.accidents_group_at`)" type="date" class="w-full lg:pr-2 lg:col-span-3" :required="!!input.count" />
                                    <text-input v-model.number="input.count" :label="$t(`inputs.safety.accidents_group`)" type='number' class="w-full lg:pl-2 lg:col-span-3" :required="!!input.date" placeholder="Оставьте пустым, если нет данных"/>

                                    <div class="w-24 flex items-end justify-center sm:justify-start m-2">
                                        <div class="flex items-center justify-center leading-none mr-2 h-10 px-2 w-10 rounded-md bg-slate-400/20 text-slate-700 hover:bg-slate-300 dark:text-slate-300/80 dark:hover:bg-slate-500 cursor-pointer"
                                        v-tippy='"Удалить"' @click.prevent="removeField(index, form.accidents_group_list)" v-if="form.accidents_group_list.length > 1">
                                            <i class="fi fi-rr-trash"></i>
                                            <span class="ml-2 sm:hidden">Удалить</span>
                                        </div>
                                        <div class="flex items-center justify-center leading-none h-10 px-2 w-10 rounded-md bg-slate-400/20 text-slate-700 hover:bg-slate-300 dark:text-slate-300/80 dark:hover:bg-slate-500 cursor-pointer"
                                        v-tippy='"Добавить"' @click.prevent="addField(input, form.accidents_group_list)" v-if="index+1 === form.accidents_group_list.length">
                                            <i class="fi fi-rr-layer-plus"></i>
                                            <span class="ml-2 sm:hidden">Добавить</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="mt-4 bg-slate-200 dark:bg-slate-600/20 rounded-lg p-4">
                                <div class="inline-block font-bold text-base mb-2 -ml-6 pl-6 pr-3 text-slate-500 dark:text-slate-400  rounded-lg bg-slate-300 dark:bg-slate-600">{{ $t(`inputs.safety.heavy`) }}</div>
                                <div class="grid lg:grid-cols-7" v-for="(input, index) in form.accidents_heavy_list" :key="`groupInput-${index}`">
                                    <text-input v-model="input.date" :label="$t(`inputs.safety.accidents_heavy_at`)" type="date" class="w-full lg:pr-2 lg:col-span-3" :required="!!input.count" />
                                    <text-input v-model.number="input.count" :label="$t(`inputs.safety.accidents_heavy`)" type='number' class="w-full lg:pl-2 lg:col-span-3" :required="!!input.date" placeholder="Оставьте пустым, если нет данных"/>

                                    <div class="w-24 flex items-end justify-center sm:justify-start m-2">
                                        <div class="flex items-center justify-center leading-none mr-2 h-10 px-2 w-10 rounded-md bg-slate-400/20 text-slate-700 hover:bg-slate-300 dark:text-slate-300/80 dark:hover:bg-slate-500 cursor-pointer"
                                        v-tippy='"Удалить"' @click.prevent="removeField(index, form.accidents_heavy_list)" v-if="form.accidents_heavy_list.length > 1">
                                            <i class="fi fi-rr-trash"></i>
                                            <span class="ml-2 sm:hidden">Удалить</span>
                                        </div>
                                        <div class="flex items-center justify-center leading-none h-10 px-2 w-10 rounded-md bg-slate-400/20 text-slate-700 hover:bg-slate-300 dark:text-slate-300/80 dark:hover:bg-slate-500 cursor-pointer"
                                        v-tippy='"Добавить"' @click.prevent="addField(input, form.accidents_heavy_list)" v-if="index+1 === form.accidents_heavy_list.length">
                                            <i class="fi fi-rr-layer-plus"></i>
                                            <span class="ml-2 sm:hidden">Добавить</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 bg-slate-200 dark:bg-slate-600/20 rounded-lg p-4">
                                <div class="inline-block font-bold text-base mb-2 -ml-6 pl-6 pr-3 text-slate-500 dark:text-slate-400 rounded-lg bg-slate-300 dark:bg-slate-600">{{ $t(`inputs.safety.deadly`) }}</div>
                                <div class="grid lg:grid-cols-7" v-for="(input, index) in form.accidents_deadly_list" :key="`groupInput-${index}`">
                                    <text-input v-model="input.date" :label="$t(`inputs.safety.accidents_deadly_at`)" type="date" class="w-full lg:pr-2 lg:col-span-3" :required="!!input.count" />
                                    <text-input v-model.number="input.count" :label="$t(`inputs.safety.accidents_deadly`)" type='number' class="w-full lg:pl-2 lg:col-span-3" :required="!!input.date" placeholder="Оставьте пустым, если нет данных"/>

                                    <div class="w-24 flex items-end justify-center sm:justify-start m-2">
                                        <div class="flex items-center justify-center leading-none mr-2 h-10 px-2 w-10 rounded-md bg-slate-400/20 text-slate-700 hover:bg-slate-300 dark:text-slate-300/80 dark:hover:bg-slate-500 cursor-pointer"
                                        v-tippy='"Удалить"' @click.prevent="removeField(index, form.accidents_deadly_list)" v-if="form.accidents_deadly_list.length > 1">
                                            <i class="fi fi-rr-trash"></i>
                                            <span class="ml-2 sm:hidden">Удалить</span>
                                        </div>
                                        <div class="flex items-center justify-center leading-none h-10 px-2 w-10 rounded-md bg-slate-400/20 text-slate-700 hover:bg-slate-300 dark:text-slate-300/80 dark:hover:bg-slate-500 cursor-pointer"
                                        v-tippy='"Добавить"' @click.prevent="addField(input, form.accidents_deadly_list)" v-if="index+1 === form.accidents_deadly_list.length">
                                            <i class="fi fi-rr-layer-plus"></i>
                                            <span class="ml-2 sm:hidden">Добавить</span>
                                        </div>
                                    </div>
                                </div>
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

            <div class="hidden sm:block" aria-hidden="true">
                <div class="py-5">
                <div class="border-t border-gray-200 dark:border-slate-700" />
                </div>
            </div>

            <div class="mt-6 flex flex-col">
                <loading-button :loading="form.processing" class="btn-green mx-auto w-full max-w-xs" type="submit">Обновить</loading-button>
                <button @click.prevent="destroy" class="text-red-600/90 dark:text-red-400 mt-4">Удалить данные</button>
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
import {  ASYNC_SEARCH, Treeselect} from '@bosquig/vue3-treeselect'
import Breadcrumbs from '@/Shared/Breadcrumbs'

export default {
    components: {
        AppLayout,
        LoadingButton,
        SelectInput,
        TextInput,
        Link,
        Treeselect,
        Breadcrumbs
    },
    props: {
        partner: Array,
        enterprise: Array,
        errors: Object,
    },
    data() {
        return {
            bread: [
                {
                    title: 'Охрана труда и социальное партнерство',
                    url: route('safety.partners.index'),
                },
                {
                    title: this.enterprise.label,
                    current: true,
                },
            ],
            list: [],
            area_id: this.enterprise.area_id,
            form: this.$inertia.form({
                enterprise_id: this.partner.enterprise_id,
                sum_contractual: this.partner.sum_contractual,
                collective_agreement: this.partner.collective_agreement,
                accidents_group_at: this.partner.accidents_group_at,
                accidents_group: this.partner.accidents_group,
                accidents_group_list: this.partner.accidents_group_list ? JSON.parse(this.partner.accidents_group_list) : [{date: "", count: null}],
                accidents_heavy_at: this.partner.accidents_heavy_at,
                accidents_heavy: this.partner.accidents_heavy,
                accidents_heavy_list: this.partner.accidents_heavy_list ? JSON.parse(this.partner.accidents_heavy_list) : [{date: "", count: null}],
                accidents_deadly_at: this.partner.accidents_deadly_at,
                accidents_deadly: this.partner.accidents_deadly,
                accidents_deadly_list: this.partner.accidents_deadly_list ? JSON.parse(this.partner.accidents_deadly_list) : [{date: "", count: null}],
                in_total: this.partner.in_total,
                start_year: this.partner.start_year
            }),
        }
    },
    watch: {
        'form.enterprise_id': {
            deep: true,
            handler() {
                let a = this.list.find((e) => e.id === this.form.enterprise_id)
                this.area_id = a ? a.area_id : null
            },
        },
    },
    methods: {
        submit() {
            try{
                this.form.put(route('safety.partners.update', this.partner.id))
            }catch(e) {
                this.$toast.error('Обновити страницу и попробуйте снова.', { title: 'Произошла ошибка' })
            }
        },
        async loadOptions({ action, searchQuery, callback }) {
            if (action === ASYNC_SEARCH) {
                await axios.post(route('enterpises.get.all') + '?filter[search]=' + searchQuery).then((res) => {
                    this.list = res.data.data
                    callback(null, res.data.data)
                })
            }
        },
        destroy() {
            if (confirm('Вы уверены, что хотите удалить эти данные?')) {
                this.$toast.warning('Удаляю... Ожидайте!')
                this.$inertia.delete(route('safety.partners.destroy', this.partner.id))
            }
        },
        addField(value, fieldType) {
            fieldType.push({ date: "", count: null });
        },
        removeField(index, fieldType) {
            fieldType.splice(index, 1);
        }
    },
}
</script>
