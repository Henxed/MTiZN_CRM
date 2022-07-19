<template>
    <app-layout :title="'Новое предприятие - ' + region.region">

 <div class="text-slate-900 dark:text-slate-100 w-full max-w-screen-2xl">
        <div class="pb-4">
            <div class="text-3xl text-slate-700 dark:text-slate-200 uppercase">Новое предприятие</div>
        </div>
        <breadcrumbs :data="bread" class="my-4"/>
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


                        <div class="px-4 py-5 card sm:p-6">

                            <div class="flex items-center">
                                <text-input v-model="form.inn" :error="errors.inn" :label="$t(`inputs.ent.inn`)" type="number" class="sm:pr-3 w-full lg:w-1/2"/>
                                <div class="w-full lg:w-1/2 sm:mt-4">
                                    <button class="hover:underline decoration-slate-500 decoration-dotted decoration-2 cursor-pointer
                                    disabled:hover:no-underline disabled:opacity-60 disabled:cursor-not-allowed"
                                    @click.prevent="nalog(form.inn)" :disabled="!form.inn || nalog_status">Получить данные с налоговой</button>
                                </div>
                            </div>
                            <ol class="block mb-3 p-3 bg-slate-200 dark:bg-slate-600 rounded-lg" v-if="more_inn.length">
                                <li class="text-slate-800 dark:text-slate-300 leading-none cursor-pointer my-1 rounded-sm hover:bg-slate-300/80 p-2 dark:hover:bg-slate-700/80" v-for="item in more_inn" :key="item" @click="nalog_data(item)">
                                    <div :class="{'line-through' : item.data.state.status !== 'ACTIVE'} ">{{ item.value }} - {{ item.data.inn }}</div>
                                    <div class="text-slate-500">{{ item.data.address.value }}</div>
                                </li>
                            </ol>
                            <text-input v-model="form.name" :error="errors.name" :label="$t(`inputs.ent.name`)" />
                            <text-input v-model="form.ogrn" :error="errors.ogrn" :label="$t(`inputs.ent.ogrn`)" />
                            <text-input v-model="form.rns" :error="errors.rns" :label="$t(`inputs.ent.rns`)" />
                            <text-input v-model="form.address" :error="errors.address" :label="$t(`inputs.ent.address`)" />
                            <text-input v-model="form.okvd" :error="errors.okvd" :label="$t(`inputs.ent.okvd`)" @keyup="mask($event, 'okvd')" required />
                            <text-input v-model="form.okvd_name" :error="errors.okvd_name" :label="$t(`inputs.ent.okvd_name`)" required />

                            <label class="form-label" for="status_id">{{ $t(`inputs.ent.status_id`) }}:</label>
                            <select v-model="form.status_id" :error="errors.status_id" id="status_id" class="form-select">
                                <option :selected="form.status_id === null" disabled>Выбирете статус</option>
                                <option v-for="item in statuses" :key="item" :value="item.code" :selected="item.code === form.status_id">{{ item.name }}</option>
                            </select>
                            <div v-if="errors.status_id" class="form-error">{{ errors.status_id }}</div>
                        </div>


                </div>
                </div>
            </div>

            <div class="hidden sm:block" aria-hidden="true" v-if="$page.props.access.can.includes('safety.partners.edit') || $page.props.access.role.includes('super-admin')">
                <div class="py-5">
                <div class="border-t border-gray-200 dark:border-slate-700" />
                </div>
            </div>

            <div class="mt-10 sm:mt-0" v-if="$page.props.access.can.includes('safety.partners.edit') || $page.props.access.role.includes('super-admin')">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0 sticky top-5">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-slate-200">Партнерская информация</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-slate-400">Дополнительная информация предприятия по договору.</p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">


                        <div class="px-4 py-5 card sm:p-6">

                            <text-input v-model="form.partner.collective_agreement" :error="errors.collective_agreement" :label="$t(`inputs.safety.collective_agreement`)" type="date" />
                            <text-input v-model="form.partner.sum_contractual" :error="errors.sum_contractual" :label="$t(`inputs.safety.sum_contractual`)" type='number' />

                            <div class="text-lg font-bold mt-6 text-slate-500 dark:text-slate-400">{{ $t(`inputs.safety.accidents`) }}</div>
                            <div class="mt-2 bg-slate-200 dark:bg-slate-600/20 rounded-lg p-4">
                                <div class="inline-block font-bold text-base mb-2 -ml-6 pl-6 pr-3 text-slate-500 dark:text-slate-400  rounded-lg bg-slate-300 dark:bg-slate-600">{{ $t(`inputs.safety.group`) }}</div>

                                <div class="grid lg:grid-cols-7" v-for="(input, index) in form.partner.accidents_group_list" :key="`groupInput-${index}`">
                                    <text-input v-model="input.date" :label="$t(`inputs.safety.accidents_group_at`)" type="date" class="w-full lg:pr-2 lg:col-span-3" :required="!!input.count" />
                                    <text-input v-model.number="input.count" :label="$t(`inputs.safety.accidents_group`)" type='number' class="w-full lg:pl-2 lg:col-span-3" :required="!!input.date" placeholder="Оставьте пустым, если нет данных"/>

                                    <div class="w-24 flex items-end justify-center sm:justify-start m-2">
                                        <div class="flex items-center justify-center leading-none mr-2 h-10 px-2 w-10 rounded-md bg-slate-400/20 text-slate-700 hover:bg-slate-300 dark:text-slate-300/80 dark:hover:bg-slate-500 cursor-pointer"
                                        v-tippy='"Удалить"' @click.prevent="removeField(index, form.partner.accidents_group_list)" v-if="form.partner.accidents_group_list.length > 1">
                                            <i class="fi fi-rr-trash"></i>
                                            <span class="ml-2 sm:hidden">Удалить</span>
                                        </div>
                                        <div class="flex items-center justify-center leading-none h-10 px-2 w-10 rounded-md bg-slate-400/20 text-slate-700 hover:bg-slate-300 dark:text-slate-300/80 dark:hover:bg-slate-500 cursor-pointer"
                                        v-tippy='"Добавить"' @click.prevent="addField(input, form.partner.accidents_group_list)" v-if="index+1 === form.partner.accidents_group_list.length">
                                            <i class="fi fi-rr-layer-plus"></i>
                                            <span class="ml-2 sm:hidden">Добавить</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="mt-4 bg-slate-200 dark:bg-slate-600/20 rounded-lg p-4">
                                <div class="inline-block font-bold text-base mb-2 -ml-6 pl-6 pr-3 text-slate-500 dark:text-slate-400  rounded-lg bg-slate-300 dark:bg-slate-600">{{ $t(`inputs.safety.heavy`) }}</div>
                                <div class="grid lg:grid-cols-7" v-for="(input, index) in form.partner.accidents_heavy_list" :key="`groupInput-${index}`">
                                    <text-input v-model="input.date" :label="$t(`inputs.safety.accidents_heavy_at`)" type="date" class="w-full lg:pr-2 lg:col-span-3" :required="!!input.count" />
                                    <text-input v-model.number="input.count" :label="$t(`inputs.safety.accidents_heavy`)" type='number' class="w-full lg:pl-2 lg:col-span-3" :required="!!input.date" placeholder="Оставьте пустым, если нет данных"/>

                                    <div class="w-24 flex items-end justify-center sm:justify-start m-2">
                                        <div class="flex items-center justify-center leading-none mr-2 h-10 px-2 w-10 rounded-md bg-slate-400/20 text-slate-700 hover:bg-slate-300 dark:text-slate-300/80 dark:hover:bg-slate-500 cursor-pointer"
                                        v-tippy='"Удалить"' @click.prevent="removeField(index, form.partner.accidents_heavy_list)" v-if="form.partner.accidents_heavy_list.length > 1">
                                            <i class="fi fi-rr-trash"></i>
                                            <span class="ml-2 sm:hidden">Удалить</span>
                                        </div>
                                        <div class="flex items-center justify-center leading-none h-10 px-2 w-10 rounded-md bg-slate-400/20 text-slate-700 hover:bg-slate-300 dark:text-slate-300/80 dark:hover:bg-slate-500 cursor-pointer"
                                        v-tippy='"Добавить"' @click.prevent="addField(input, form.partner.accidents_heavy_list)" v-if="index+1 === form.partner.accidents_heavy_list.length">
                                            <i class="fi fi-rr-layer-plus"></i>
                                            <span class="ml-2 sm:hidden">Добавить</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 bg-slate-200 dark:bg-slate-600/20 rounded-lg p-4">
                                <div class="inline-block font-bold text-base mb-2 -ml-6 pl-6 pr-3 text-slate-500 dark:text-slate-400 rounded-lg bg-slate-300 dark:bg-slate-600">{{ $t(`inputs.safety.deadly`) }}</div>
                                <div class="grid lg:grid-cols-7" v-for="(input, index) in form.partner.accidents_deadly_list" :key="`groupInput-${index}`">
                                    <text-input v-model="input.date" :label="$t(`inputs.safety.accidents_deadly_at`)" type="date" class="w-full lg:pr-2 lg:col-span-3" :required="!!input.count" />
                                    <text-input v-model.number="input.count" :label="$t(`inputs.safety.accidents_deadly`)" type='number' class="w-full lg:pl-2 lg:col-span-3" :required="!!input.date" placeholder="Оставьте пустым, если нет данных"/>

                                    <div class="w-24 flex items-end justify-center sm:justify-start m-2">
                                        <div class="flex items-center justify-center leading-none mr-2 h-10 px-2 w-10 rounded-md bg-slate-400/20 text-slate-700 hover:bg-slate-300 dark:text-slate-300/80 dark:hover:bg-slate-500 cursor-pointer"
                                        v-tippy='"Удалить"' @click.prevent="removeField(index, form.partner.accidents_deadly_list)" v-if="form.partner.accidents_deadly_list.length > 1">
                                            <i class="fi fi-rr-trash"></i>
                                            <span class="ml-2 sm:hidden">Удалить</span>
                                        </div>
                                        <div class="flex items-center justify-center leading-none h-10 px-2 w-10 rounded-md bg-slate-400/20 text-slate-700 hover:bg-slate-300 dark:text-slate-300/80 dark:hover:bg-slate-500 cursor-pointer"
                                        v-tippy='"Добавить"' @click.prevent="addField(input, form.partner.accidents_deadly_list)" v-if="index+1 === form.partner.accidents_deadly_list.length">
                                            <i class="fi fi-rr-layer-plus"></i>
                                            <span class="ml-2 sm:hidden">Добавить</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-lg mt-6">{{ $t(`inputs.safety.learn_safety`) }}</div>
                            <div class="flex flex-wrap ">
                                <text-input v-model="form.partner.in_total" :error="errors.in_total" :label="$t(`inputs.safety.in_total`)" class="w-full lg:pr-2 lg:w-1/2" type='number' />
                                <text-input v-model="form.partner.start_year" :error="errors.start_year" :label="$t(`inputs.safety.start_year`)" class="w-full lg:pl-2 lg:w-1/2" type='number' />
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


                        <div class="px-4 py-5 card sm:p-6">
                            <text-input v-model="form.amy" :error="errors.amy" :label="$t(`inputs.ent.amy`)" type='number'/>
                            <text-input v-model="form.total_jobs" :error="errors.total_jobs" :label="$t(`inputs.ent.total_jobs`)" type="number"/>
                            <text-input v-model="form.ane" :error="errors.ane" :label="$t(`inputs.ent.ane`)" type='number' />
                            <text-input v-model="form.nde" :error="errors.nde" :label="$t(`inputs.ent.nde`)" type='number' />
                            <text-input v-model="form.factors" :error="errors.factors" :label="$t(`inputs.ent.factors`)" type="number"/>
                            <text-input v-model="form.workplaces_respect" :error="errors.workplaces_respect" :label="$t(`inputs.ent.workplaces_respect`)" type="number"/>
                            <text-input v-model="form.sum_arrears" :error="errors.sum_arrears" :label="$t(`inputs.ent.sum_arrears`)" type='number'/>
                            <text-input v-model="form.employed_public" :error="errors.employed_public" :label="$t(`inputs.ent.employed_public`)" type='number'/>
                            <text-input v-model="form.employed_temporary" :error="errors.employed_temporary" :label="$t(`inputs.ent.employed_temporary`)" type='number'/>
                            <text-input v-model="form.work_part" :error="errors.work_part" :label="$t(`inputs.ent.work_part`)" type='number'/>
                            <text-input v-model="form.idle" :error="errors.idle" :label="$t(`inputs.ent.idle`)" type='number'/>
                            <text-input v-model="form.vacations" :error="errors.vacations" :label="$t(`inputs.ent.vacations`)" type='number'/>
                            <text-input v-model="form.dismissed" :error="errors.dismissed" :label="$t(`inputs.ent.dismissed`)" type='number'/>
                            <text-input v-model="form.remote" :error="errors.remote" :label="$t(`inputs.ent.remote`)" type='number'/>
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
import Breadcrumbs from '@/Shared/Breadcrumbs'

export default {
    components: {
        AppLayout,
        LoadingButton,
        SelectInput,
        TextInput,
        Link,
        Breadcrumbs
    },
    props: {
        region: Array,
        statuses: Array,
        errors: Object,
    },
    data() {
        return {
            bread: [
                {
                    title: 'Районы',
                    url: route('regions.index'),
                },
                {
                    title: this.region.region,
                    url: route('regions.show', this.region.id),
                },
                {
                    title: 'Предприятия',
                    url: route('regions.enterprises.index', this.region.id),
                },
                {
                    title: 'Новое',
                    current: true,
                },
            ],
            form: this.$inertia.form({
                name: '',
                amy: '',
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
                address: '',
                ogrn: '',
                sum_arrears: '',
                employed_public: '',
                employed_temporary: '',
                work_part: '',
                vacations: '',
                dismissed: '',
                remote: '',
                partner: {
                    sum_contractual: '',
                    collective_agreement: '',
                    accidents_group_at: '',
                    accidents_group: '',
                    accidents_group_list: [{date: "", count: null}],
                    accidents_heavy_at: '',
                    accidents_heavy: '',
                    accidents_heavy_list: [{date: "", count: null}],
                    accidents_deadly_at: '',
                    accidents_deadly: '',
                    accidents_deadly_list: [{date: "", count: null}],
                    in_total: '',
                    start_year: ''
                }
            }),
            nalog_status: false,
            token: "7d6bb02e2a8855750be56d3d50f7cc896aabc095",
            more_inn: []
        }
    },
    methods: {
        submit() {
            this.$toast.show('Добавляю... Ожидайте!')
            this.form.post(route('regions.enterprises.store', this.region.id))
        },
        mask (e, model) {
            this.$data['form'][model] = e.target.value.replace(',', '.').replace(/[^\d.]/g, '')
        },
        nalog(query) {
            var url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/party";

            var options = {
                method: "POST",
                mode: "cors",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "Authorization": "Token " + this.token
                },
                body: JSON.stringify({query: query})
            }

            fetch(url, options)
            .then(response => response.text())
            .then(result => {
                this.nalog_status = true

                this.more_inn = JSON.parse(result).suggestions;

            })
            .catch(error => console.log("error", error));
        },
        nalog_data(data){
            this.form.name = data.value;
            this.form.address = data.data.address.value;
            this.form.okvd = data.data.okved;
            this.form.ogrn = data.data.ogrn;
            this.okvd_name_api()
            this.change_status(data.data.state.status)
        },
        change_status(data){
            switch (data) {
                case 'ACTIVE': //действующая
                    this.form.status_id = 201
                    break;
                case 'LIQUIDATING': //ликвидируется
                    this.form.status_id = 207
                    break;
                case 'LIQUIDATED': //ликвидирована
                    this.form.status_id = 201
                    break;
                case 'BANKRUPT': //банкротство
                    this.form.status_id = 211
                    break;
                case 'REORGANIZING': //в процессе присоединения к другому юрлицу, с последующей ликвидацией
                    this.form.status_id = 209
                    break;
                default:
                    break;
            }
        },
        okvd_name_api() {
            var url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/okved2";
            var query = this.form.okvd;

            var options = {
                method: "POST",
                mode: "cors",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "Authorization": "Token " + this.token
                },
                body: JSON.stringify({query: query})
            }

            fetch(url, options)
            .then(response => response.text())
            .then(result => {
                var data = JSON.parse(result).suggestions[0].value
                this.form.okvd_name = data;

            })
            .catch(error => console.log("error", error));

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
