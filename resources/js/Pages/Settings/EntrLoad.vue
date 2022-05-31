<template>
    <app-layout title="Загрузка статистики">
        <setting class="pr-9">
        <div class="flex mb-4 rounded-xl bg-white text-slate-900 dark:text-slate-300 p-4 dark:bg-slate-800 w-full max-w-screen-2xl">
            <form @submit.prevent="submit">
                <div class="px-3 mb-3">
                    <div class="text-xl">Загрузка Отдела трудоустройства и специальных программ</div>
                    <div>Скачайте <a href="../help/enterprises-preview.csv" class="text-pink-600">подготовленный файл</a> с ID регионами в .csv, чтобы подготовить к загрузке. Первая строка и строки без региона - игнорируются! Для каждого региона нужно ставить ID, а название района не важно.</div>
                    <div>
                        <img src="../../../images/csv-save.jpeg" class="shadow-lg">
                    </div>

                    <div class="mt-3">Загрузите <strong>.csv</strong> с регионами</div>
                    <file-input v-model="form.csv" accept=".csv"/>

                </div>

                <loading-button :loading="form.processing" class="btn-green mx-auto w-full max-w-xs" type="submit">Загрузить</loading-button>
            </form>
        </div>
        <div class="rounded-xl bg-red-300 text-slate-900 dark:text-slate-300 p-4 dark:bg-red-500/50 w-full max-w-screen-2xl" v-if="this.$page.props.flash.error">
            <div class="text-xl font-bold">Все предприятия обновлены, кроме: (не обновляйте страницу, чтобы не потерять данные)</div>
            <div v-for="(item, i) in this.$page.props.flash.error" :key="item">{{ i+1 }}. <strong>{{ item.inn }}</strong> отсутсвует в районе <strong>{{ item.region }}</strong> </div>

        </div>
        </setting>
    </app-layout>
</template>

<script>

import AppLayout from '@/Layouts/AppLayout'
import LoadingButton from '@/Shared/LoadingButton'
import FileInput from '@/Shared/FileInput'
import TextInput from '@/Shared/TextInput'
import { Link } from '@inertiajs/inertia-vue3'
import Setting from '@/Pages/Settings/Layout'

export default {
    components: {
        AppLayout,
        LoadingButton,
        FileInput,
        TextInput,
        Link,
        Setting
    },
    props: {
        errors: Object,
    },
    data() {
        return {
            form: this.$inertia.form({
                csv: ''
            }),
        }
    },
    methods: {
        submit() {
            this.form.post(route('cp.upload.entr')).then(() => {
                this.form.csv = ''
            })
        },
        mask (e, model) {
            this.$data[model] = e.target.value.replace(',', '.').replace(/[^\d.]/g, '')
        },

    },
}
</script>
