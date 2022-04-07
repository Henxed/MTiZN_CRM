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

                            <div class="flex items-center">
                                <text-input v-model="form.inn" :error="errors.inn" :label="$t(`inputs.inn`)" type="number" class="sm:pr-3 w-full lg:w-1/2"/>
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
                            <text-input v-model="form.name" :error="errors.name" :label="$t(`inputs.name`)" />
                            <text-input v-model="form.ogrn" :error="errors.ogrn" :label="$t(`inputs.ogrn`)" />
                            <text-input v-model="form.rns" :error="errors.rns" :label="$t(`inputs.rns`)" />
                            <text-input v-model="form.address" :error="errors.address" :label="$t(`inputs.address`)" />
                            <text-input v-model="form.okvd" :error="errors.okvd" :label="$t(`inputs.okvd`)" @keyup="mask($event, 'okvd')" required />
                            <text-input v-model="form.okvd_name" :error="errors.okvd_name" :label="$t(`inputs.okvd_name`)" required />

                            <label class="form-label" for="status_id">{{ $t(`inputs.status_id`) }}:</label>
                            <select v-model="form.status_id" :error="errors.status_id" id="status_id" class="form-select">
                                <option :selected="form.status_id === null" disabled>Выбирете статус</option>
                                <option v-for="item in statuses" :key="item" :value="item.code" :selected="item.code === form.status_id">{{ item.name }}</option>
                            </select>
                            <div v-if="errors.status_id" class="form-error">{{ errors.status_id }}</div>
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
                            <text-input v-model="form.amy" :error="errors.amy" :label="$t(`inputs.amy`)" type='number'/>
                            <text-input v-model="form.total_jobs" :error="errors.total_jobs" :label="$t(`inputs.total_jobs`)" type="number"/>
                            <text-input v-model="form.cw" :error="errors.cw" :label="$t(`inputs.cw`)" type='number'/>
                            <text-input v-model="form.ane" :error="errors.ane" :label="$t(`inputs.ane`)" type='number' />
                            <text-input v-model="form.nde" :error="errors.nde" :label="$t(`inputs.nde`)" type='number' />
                            <text-input v-model="form.factors" :error="errors.factors" :label="$t(`inputs.factors`)" type="number"/>
                            <text-input v-model="form.workplaces_respect" :error="errors.workplaces_respect" :label="$t(`inputs.workplaces_respect`)" type="number"/>
                            <text-input v-model="form.workplaces_three" :error="errors.workplaces_three" :label="$t(`inputs.workplaces_three`)" type="number"/>
                            <text-input v-model="form.workplaces_four" :error="errors.workplaces_four" :label="$t(`inputs.workplaces_four`)" type="number"/>
                            <text-input v-model="form.total_factors" :error="errors.total_factors" :label="$t(`inputs.total_factors`)" type="number"/>
                            <text-input v-model="form.start_year_factors" :error="errors.start_year_factors" :label="$t(`inputs.start_year_factors`)" type="number"/>

                            <text-input v-model="form.sum_arrears" :error="errors.sum_arrears" :label="$t(`inputs.sum_arrears`)" type='number'/>
                            <text-input v-model="form.employed_public" :error="errors.employed_public" :label="$t(`inputs.employed_public`)" type='number'/>
                            <text-input v-model="form.employed_temporary" :error="errors.employed_temporary" :label="$t(`inputs.employed_temporary`)" type='number'/>
                            <text-input v-model="form.work_part" :error="errors.work_part" :label="$t(`inputs.work_part`)" type='number'/>
                            <text-input v-model="form.idle" :error="errors.idle" :label="$t(`inputs.idle`)" type='number'/>
                            <text-input v-model="form.vacations" :error="errors.vacations" :label="$t(`inputs.vacations`)" type='number'/>
                            <text-input v-model="form.dismissed" :error="errors.dismissed" :label="$t(`inputs.dismissed`)" type='number'/>
                            <text-input v-model="form.remote" :error="errors.remote" :label="$t(`inputs.remote`)" type='number'/>
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
                ogrn: '',
                sum_arrears: '',
                employed_public: '',
                employed_temporary: '',
                work_part: '',
                vacations: '',
                dismissed: '',
                remote: ''
            }),
            nalog_status: false,
            token: "7d6bb02e2a8855750be56d3d50f7cc896aabc095",
            more_inn: []
        }
    },
    methods: {
        submit() {
            this.$toast.open({message: 'Добавляю... Ожидайте!', type: 'default'})
            this.form.post(route('enterprises.store'))
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

                console.log(JSON.parse(result).suggestions);
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

        }
    },
}
</script>
