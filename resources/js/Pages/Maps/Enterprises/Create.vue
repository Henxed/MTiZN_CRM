<template>
    <app-layout :title="'Новое предприятие - ' + region.region">
        <form @submit.prevent="submit" class="sm:flex" >


        <div class="grid grid-cols-1 gap-4 rounded-xl bg-white text-slate-900 p-4 sm:p-9 sm:mx-6 dark:bg-slate-800 w-full max-w-screen-2xl">
            <div class="px-3">
                <div class="font-bold text-3xl dark:text-slate-300">Новое предприятие</div>
                <Link :href="route('regions.enterprises', region.id)" class="block font-bold text-xl text-indigo-600 dark:text-indigo-500 dark:hover:text-pink-600 hover:text-pink-500 uppercase">{{region.region}}</Link>

            </div>

            <div class="flex flex-wrap">
                <text-input v-model="form.name" :error="errors.name" class="sm:px-3 pb-1 w-full" label="Наименование" />
                <text-input v-model="form.amy" :error="errors.amy" class="sm:px-3 pb-1 w-full lg:w-1/2" label="Средняя ЗП за текущий год" type='number'/>
                <text-input v-model="form.cw" :error="errors.cw" class="sm:px-3 pb-1 w-full lg:w-1/2" label="Сотрудников" type='number'/>
                <text-input v-model="form.inn" :error="errors.inn" class="sm:px-3 pb-1 w-full lg:w-1/2" label="ИНН" />

            </div>

            <loading-button :loading="form.processing" class="btn-green mx-auto w-full max-w-xs" type="submit">Добавить</loading-button>
        </div>

        </form>

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
        errors: Object,
    },
    data() {
        return {
        form: this.$inertia.form({
            name: '',
            amy:'',
            cw: '',
            inn: '',
            area_id: this.region.id,
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
