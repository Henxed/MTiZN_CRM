<template>
    <app-layout title="Загрузка статистики">
        <setting class="pr-9">
        <div class="flex rounded-xl bg-white text-slate-900 dark:text-slate-300 p-4 dark:bg-slate-800 w-full max-w-screen-2xl">
            <form @submit.prevent="submit">
                <div class="px-3 mb-3">
                    <div class="text-xl">Как сохранить в CSV?</div>
                    <div>Скачайте <a href="../help/csv-preview.csv" class="text-pink-600">пример</a> с ID регионами в .csv, чтобы подготовить к загрузке. Первая строка и строки без региона - игнорируются!</div>
                    <div>
                        <img src="../../../images/csv-save.jpeg" class="shadow-lg">
                    </div>

                    <div class="mt-3">Загрузите <strong>.csv</strong> с регионами</div>
                    <file-input v-model="form.csv" accept=".csv"/>

                </div>

                <loading-button :loading="form.processing" class="btn-green mx-auto w-full max-w-xs" type="submit">Загрузить</loading-button>
            </form>
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
            })
        }
    },
    methods: {
        submit() {
            this.form.post(route('cp.upload.stats'))
        }

    },
}
</script>
