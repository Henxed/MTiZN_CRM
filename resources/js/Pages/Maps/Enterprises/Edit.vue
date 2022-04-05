<template>
    <app-layout :title="'Редактирование предприятие - ' + enterprises.name">
        <div class="text-slate-900 dark:text-slate-100 w-full max-w-screen-2xl">
        <div class="py-8">
            <Link :href="route('regions.enterprises', enterprises.area_id)" class="text-3xl text-slate-700 dark:text-slate-200 dark:hover:text-pink-600 hover:text-pink-500 uppercase">{{ enterprises.name }}</Link>
            <div class="text-lg text-slate-500 dark:text-slate-400">{{ $t(`inputs.inn`) }}: {{ enterprises.inn }}</div>
            <div class="text-xs text-slate-400 dark:text-slate-500">Последние обновление {{ $moment(enterprises.updated_at).format('LLL') }}</div>
        </div>
        <form @submit.prevent="update" class="border-t border-gray-200 dark:border-slate-700 py-6">

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
                                    <button class="text-indigo-700 hover:underline decoration-slate-500 decoration-dotted decoration-2 cursor-pointer
                                    disabled:hover:no-underline disabled:opacity-60 disabled:cursor-not-allowed"
                                    @click.prevent="nalog(form.inn)" :disabled="nalog_status">Получить данные с налоговой</button>
                                </div>
                            </div>
                            <ol class="block mb-3 p-3 bg-slate-200 dark:bg-slate-600 rounded-lg" v-if="more_inn.length">
                                <li class="text-slate-800 dark:text-slate-300 leading-none cursor-pointer my-1 rounded-sm hover:bg-slate-300/80 p-2 dark:hover:bg-slate-700/80" v-for="item in more_inn" :key="item" @click="nalog_data(item)">
                                    <div :class="{'line-through' : item.data.state.status !== 'ACTIVE'} ">{{ item.value }} - {{ item.data.inn }}</div>
                                    <div class="text-slate-500">{{ item.data.address.value }}</div>
                                </li>
                            </ol>

                            <div class="relative">
                                <text-input v-model="form.name" :error="errors.name" :label="$t(`inputs.name`)" />
                                <div class="absolute right-2 bottom-1 text-lg" @click="form.name = this.enterprises.name" v-if="nalog_selected"
                                v-tippy="{placement: 'left', content: 'Вернуть предыдущее название'}"><i class="fi fi-rr-time-past"></i></div>
                            </div>
                            <text-input v-model="form.ogrn" :error="errors.ogrn" :label="$t(`inputs.ogrn`)" />
                            <text-input v-model="form.rns" :error="errors.rns" :label="$t(`inputs.rns`)" />
                            <div class="relative">
                                <text-input v-model="form.address" :error="errors.address" :label="$t(`inputs.address`)" />
                                <div class="absolute right-2 bottom-1 text-lg" @click="form.address = this.enterprises.address" v-if="nalog_selected"
                                v-tippy="{placement: 'left', content: 'Вернуть предыдущий адрес'}"><i class="fi fi-rr-time-past"></i></div>
                            </div>
                            <div class="relative">
                                <text-input v-model="form.okvd" :error="errors.okvd" :label="$t(`inputs.okvd`)" required/>
                                <div class="absolute right-2 bottom-1 text-lg" @click="form.okvd = this.enterprises.okvd" v-if="nalog_selected"
                                v-tippy="{placement: 'left', content: 'Вернуть предыдущий ОКВЭД'}"><i class="fi fi-rr-time-past"></i></div>
                            </div>
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
                <small class="mx-auto">При нажатии, предприятие актулизируется.</small>
                <button @click.prevent="destroy" class="text-red-600/90 dark:text-red-400 mt-4">Удалить предприятие</button>
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
        enterprises: Array,
        statuses: Array,
        errors: Object,
    },
    data() {
        return {
            form: this.$inertia.form({
                name: this.enterprises.name,
                amy: this.enterprises.amy,
                cw: this.enterprises.cw,
                inn: this.enterprises.inn,
                area_id: this.enterprises.area_id,
                rns: this.enterprises.rns,
                status_id: this.enterprises.status_id,
                okvd: this.enterprises.okvd,
                okvd_name: this.enterprises.okvd_name,
                ane: this.enterprises.ane,
                nde: this.enterprises.nde,
                factors: this.enterprises.factors,
                total_jobs: this.enterprises.total_jobs,
                workplaces_respect: this.enterprises.workplaces_respect,
                workplaces_three: this.enterprises.workplaces_three,
                workplaces_four	: this.enterprises.workplaces_four,
                total_factors: this.enterprises.total_factors,
                start_year_factors: this.enterprises.start_year_factors,
                address: this.enterprises.address,
                ogrn: this.enterprises.ogrn
            }),

            nalog_status: false,
            nalog_selected: false,
            token: "7d6bb02e2a8855750be56d3d50f7cc896aabc095",
            more_inn: []
        }
    },
    methods: {

        update() {
            this.$toast.open({message: 'Обновляю предприятие... Ожидайте!', type: 'default'})
            this.form.put(route('enterprises.update', this.enterprises.id))
        },
        destroy() {
            if (confirm('Вы уверены, что хотите удалить это предприятие?')) {
                this.$toast.open({message: 'Удаляю предприятие... Ожидайте!', type: 'warning'})
                this.$inertia.delete(route('enterprises.destroy', this.enterprises.id))
            }
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
            this.nalog_selected = true;
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
