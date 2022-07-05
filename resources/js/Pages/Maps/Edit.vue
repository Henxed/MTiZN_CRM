<template>
    <app-layout title="Редактировать район">

<div class="text-slate-900 dark:text-slate-100 w-full max-w-screen-2xl">
        <div class="py-8">
            <Link :href="route('regions.show', region.id)" class="text-3xl text-slate-700 dark:text-slate-200 dark:hover:text-slablu-500 hover:text-slablu-600 uppercase">{{ form.region }}</Link>
            <div class="text-xs text-slate-400 dark:text-slate-500">Последние обновление {{ $moment(region.updated_at).format('LLL') }}  |  <span class="cursor-pointer hover:text-slablu-500" @click.prevent="open=true">История изменений</span> </div>
        </div>
        <form @submit.prevent="submit" class="border-t border-gray-200 dark:border-slate-700 py-6">

            <div class="mt-10 sm:mt-0">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0 sticky top-5">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-slate-200">Общая информация</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-slate-400">Основная информацию по региону, которая отображается везде.</p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">


                        <div class="px-4 py-5 card sm:p-6">

                            <text-input v-model="form.region" :error="errors.region" label="Название ЦЗН района" />
                            <text-input v-model="form.leader" :error="errors.leader" label="Глава района" />
                            <text-input v-model="form.city" :error="errors.city" label="Районый центр" />
                            <text-input v-model="form.population" :error="errors.population" label="Население" type="number" />
                            <text-input v-model="form.area"  @keyup="mask($event, 'area')" :error="errors.area" label="Площадь" />

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
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-slate-200">Учебные заведения</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-slate-400">Количество учебных заведений в данном регионе. Оставьте пустыми поля, если данные заведения отсутствуют.</p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">


                        <div class="px-4 py-5 card sm:p-6">

                            <text-input v-model="form.school" :error="errors.school" label="Школы" type="number" placeholder="Оставьте пустым, если нет школ" min="1"/>
                            <text-input v-model="form.vvuz" :error="errors.vvuz" label="ВУЗы" type="number" placeholder="Оставьте пустым, если нет ВВУЗов" min="1"/>
                            <text-input v-model="form.ssuz" :error="errors.ssuz" label="ССУЗы" type="number" placeholder="Оставьте пустым, если нет ССУЗов" min="1" />
                            <text-input v-model="form.detdom" :error="errors.detdom" label="Детские дома, интернаты" type="number" placeholder="Оставьте пустым, если нет детских домов и интернатов" min="1"/>

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
                    <p class="mt-1 text-sm text-gray-600 dark:text-slate-400">Полная статистика по всем показателям в данном регионе.</p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">


                        <div class="px-4 py-5 card sm:p-6">

                            <text-input v-model="form.amw" @keyup="mask($event, 'amw')"  :error="errors.amw" label="Средняя зарплата" />
<div class="hidden sm:block" aria-hidden="true"> <div class="py-5"> <div class="border-t border-gray-200 dark:border-slate-700" /></div> </div>
                            <text-input v-model="form.commissions_c" @keyup="mask($event, 'commissions_c')" :error="errors.commissions_c" label="Контрольный показатель по снижению неформальной занятости" />
                            <text-input v-model="form.commissions_t" @keyup="mask($event, 'commissions_t')" :error="errors.commissions_t" label="Заключено трудовых договоров в рамках работы по снижению неформальной занятости" />
                            <text-input v-model="commission" :error="errors.commissions" label="Процент исполнения контрольного показателя по снижению неформальной занятости"   disabled />
<div class="hidden sm:block" aria-hidden="true"> <div class="py-5"> <div class="border-t border-gray-200 dark:border-slate-700" /></div> </div>
                            <text-input v-model="form.subject" @keyup="mask($event, 'subject')" disabled :error="errors.subject" label="Число хозяйствующих субъектов" help="Предприятия обновляются автоматически" />
                            <text-input v-model="form.contract" @keyup="mask($event, 'contract')" disabled :error="errors.contract" label="Заключенные коллективные договора"  help="Обновляются автоматически с отдела Охраны труда" />
                            
                            <text-input v-model="form.in_employment" @keyup="mask($event, 'in_employment')" :error="errors.in_employment" label="Количество проведенных территориальных межведомственной комиссии" />
                            <text-input v-model="form.lvl" @keyup="mask($event, 'lvl')" :error="errors.lvl" label="Уровень безработицы" />
                            <text-input v-model="form.unemployed" @keyup="mask($event, 'unemployed')" :error="errors.unemployed" label="Численность зарегистрированных безработных" />
                            <text-input v-model="form.vacancy" @keyup="mask($event, 'vacancy')" :error="errors.vacancy" label="Количество вакансий" />
                            <text-input v-model="form.tension" @keyup="mask($event, 'tension')"  :error="errors.tension" label="Напряженность" />
                            <text-input v-model="form.employed" @keyup="mask($event, 'employed')" :error="errors.employed" label="Трудоустроено" />

                            <div class="sm:grid sm:grid-cols-2 sm:gap-6">
                                <text-input v-model="form.cripple" :error="errors.cripple" label="Численность инвалидов" type="number" />
                                <text-input v-model="form.cripple_worked" :error="errors.cripple_worked" label="из них заняты трудовой деятельностью" />
                            </div>
                        </div>

                </div>
                </div>
            </div>

            <div class="hidden sm:block" aria-hidden="true">
                <div class="py-5">
                <div class="border-t border-gray-200 dark:border-slate-700" /></div>
            </div>

            <div class="mt-6 flex items-center">
                <loading-button :loading="form.processing" class="btn-green mx-auto w-full max-w-xs" type="submit">Обновить</loading-button>
            </div>
        </form>
    </div>
    <history :data="log" v-model:open="open" @open="hasOpen" />
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import LoadingButton from '@/Shared/LoadingButton'
import TextInput from '@/Shared/TextInput'
import { Link } from '@inertiajs/inertia-vue3'
import Treeselect from '@bosquig/vue3-treeselect'
import History from '@/Shared/RegionHistory'

export default {
    components: {
        LoadingButton,
        TextInput,
        Link,
        Treeselect,
        AppLayout,
        History
    },
    props: {
        errors: Object,
        region: Array,
        log: Array
    },
    remember: 'form',
    data() {
        return {
            open: false,
            form: this.$inertia.form({
                id: this.region.id,
                region: this.region.region,
                city: this.region.city,
                leader: this.region.leader,
                population: this.region.population,
                area: this.region.area,
                amw: this.region.amw,
                subject: this.region.subject,
                contract: this.region.contract,
                lvl: this.region.lvl,
                unemployed: this.region.unemployed,
                unemployed_percent: this.region.unemployed_percent,
                vacancy: this.region.vacancy,
                tension: this.region.tension,
                employed: this.region.employed,
                cripple: this.region.cripple,
                cripple_worked: this.region.cripple_worked,
                commissions_c: this.region.commissions_c,
                commissions_t: this.region.commissions_t,
                commissions: this.region.commissions,
                in_employment: this.region.in_employment,

                school: this.region.extra.school,
                vvuz: this.region.extra.vvuz,
                ssuz: this.region.extra.ssuz,
                detdom: this.region.extra.detdom,
                nou: this.region.extra.nou,
                ur: this.region.extra.ur,
                tension: this.region.tension,
                jobs: this.region.extra.jobs,
            }),
        }
    },
    computed: {
        commission() {
            var num = (this.form.commissions_t / this.form.commissions_c) * 100
            this.form.commissions = num.toFixed(2)

            return num != 'Infinity' ? num.toFixed(2) : 0;
        },
    },
    methods: {
        mask (e, model) {
            this.$data['form'][model] = e.target.value.replace(',', '.').replace(/[^\d.]/g, '')
        },
        submit() {
            this.form.put(this.route('regions.update', this.region.id))
        },
        hasOpen(e) {
            this.open = e
        }
    },
}
</script>
