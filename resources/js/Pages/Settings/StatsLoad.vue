<template>
    <app-layout title="Загрузка статистики">
        <setting class="pr-9">
        <div class="card flex mb-3 p-4 w-full max-w-screen-2xl">
            <form @submit.prevent="setLvl">
                <div class="px-3 mb-3 grid grid-cols-2 items-center">
                    <text-input v-model="lvl" @keyup="mask($event, 'lvl')" label="Уровень безработицы по области" />
                    <div class="mt-5 ml-2">
                        <loading-button :loading="lvlBtn" class="btn-green mx-auto w-full max-w-xs" type="submit">Сохранить</loading-button>
                    </div>
                </div>

            </form>
        </div>
        <div class="card flex p-4 w-full max-w-screen-2xl">
            <form @submit.prevent="submit">
                <div class="px-3 mb-3">
                    <div class="text-xl">Как сохранить в CSV?</div>
                    <div>Скачайте <a href="../help/csv-preview.csv" class="text-pink-600">пример</a> с ID регионами в .csv, чтобы подготовить к загрузке. Первая строка и строки без региона - игнорируются!</div>
                    <div>
                        <img src="../../../images/csv-save.jpeg" class="shadow-lg">
                    </div>

                    <div class="mt-3">Загрузите <strong>.csv</strong> с регионами</div>
                    <file-input v-model="form.csv" accept=".csv" required/>

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
        lvl_all: Array,
    },
    data() {
        return {
            form: this.$inertia.form({
                csv: ''
            }),
            lvl: this.lvl_all.value || 0,
            lvlBtn: false,
        }
    },
    methods: {
        submit() {
            this.form.post(route('cp.upload.stats')).then(() => {
                this.form.csv = ''
            })
        },
        setLvl() {
            this.lvlBtn = !this.lvlBtn
            axios.post(route('set-lvl'), {
                    lvl_all: this.lvl
            }).then((response) => {
                this.lvlBtn = !this.lvlBtn
                this.$toast.success('Новый уровень безработицы установлен!', {
                    title: 'Сохранил',
                })
            }).catch((error) => {
                this.lvlBtn = !this.lvlBtn
                this.$toast.error("Произошла ошибка при сохранение!")
            });
        },
        mask (e, model) {
            this.$data[model] = e.target.value.replace(',', '.').replace(/[^\d.]/g, '')
        },

    },
}
</script>
