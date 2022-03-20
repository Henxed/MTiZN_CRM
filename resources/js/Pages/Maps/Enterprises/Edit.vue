<template>
    <app-layout title="Карта регионов">
        <form @submit.prevent="update" class="sm:flex" >

        <div class="grid grid-cols-1 gap-4 rounded-xl bg-white text-slate-900 p-4 sm:p-9 sm:mx-6 dark:bg-slate-800 w-full max-w-screen-2xl">
            <div class="">
                <div class="font-bold text-3xl dark:text-slate-300">{{enterprises.name}}</div>
                <Link :href="route('regions.enterprises', enterprises.area_id)" class="inline-flex font-bold text-sm text-slate-700 dark:text-slate-500 dark:hover:text-pink-600 hover:text-pink-500 uppercase">Вернуться к списку предприятий</Link>

            </div>

            <div class="flex flex-wrap">
                <text-input v-model="form.name" :error="errors.name" class="sm:px-3 pb-1 w-full" label="Наименование" />
                <text-input v-model="form.amy" :error="errors.amy" class="sm:px-3 pb-1 w-full lg:w-1/2" label="Средняя ЗП за текущий год" type='number'/>
                <text-input v-model="form.cw" :error="errors.cw" class="sm:px-3 pb-1 w-full lg:w-1/2" label="Сотрудников" type='number'/>
                <text-input v-model="form.inn" :error="errors.inn" class="sm:px-3 pb-1 w-full lg:w-1/2" label="ИНН" />

            </div>

            <loading-button :loading="form.processing" class="btn-green mx-auto w-full max-w-xs" type="submit">Обновить</loading-button>
            <small class="mx-auto -mt-4">При нажатии, предприятие актулизируется.</small>
            <button @click.prevent="destroy" class="text-red-600/90 dark:text-red-400">Удалить предприятие</button>
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
        enterprises: Array,
        errors: Object,
    },
    data() {
        return {
        status_name: null,
        form: this.$inertia.form({
            _method: 'put',
            name: this.enterprises.name,
            amy: this.enterprises.amy,
            cw: this.enterprises.cw,
            inn: this.enterprises.inn,
            area_id: this.enterprises.area_id,
        })
        }
    },
    methods: {

        update() {
            this.$toast.open({message: 'Обновляю реестр... Ожидайте!', type: 'default'})
            this.form.post(route('enterprises.update', this.enterprises.id))
        },
        destroy() {
            if (confirm('Вы уверены, что хотите удалить это предприятие?')) {
                this.$toast.open({message: 'Удаляю предприятие... Ожидайте!', type: 'warning'})
                this.$inertia.delete(route('enterprises.destroy', this.enterprises.id))
            }
        },

    },
}
</script>
