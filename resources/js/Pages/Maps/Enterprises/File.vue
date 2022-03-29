<template>
    <app-layout>
        <form @submit.prevent="submit" class="sm:flex" >


        <div class="grid grid-cols-1 gap-4 rounded-xl bg-white text-slate-900 p-4 sm:p-9 sm:mx-6 dark:bg-slate-800 w-full max-w-screen-2xl">
            <div class="px-3">

            </div>

            <div class="flex flex-wrap">
                <file-input v-model="form.csv" />

            </div>

            <loading-button :loading="form.processing" class="btn-green mx-auto w-full max-w-xs" type="submit">Добавить</loading-button>
        </div>

        </form>

    </app-layout>
</template>

<script>

import AppLayout from '@/Layouts/AppLayout'
import LoadingButton from '@/Shared/LoadingButton'
import FileInput from '@/Shared/FileInput'
import TextInput from '@/Shared/TextInput'
import { Link } from '@inertiajs/inertia-vue3'


export default {
    components: {
        AppLayout,
        LoadingButton,
        FileInput,
        TextInput,
        Link
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
            this.form.post(route('csv_u'))
        }

    },
}
</script>
