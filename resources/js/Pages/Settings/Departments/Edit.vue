<template>
    <app-layout title="Редактирование отдела">
        <setting class="px-9">

        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-2xl leading-6 font-medium text-gray-900 dark:text-slate-300 ">{{ this.departments.name }}</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Отделы позволяют собирать людей в группы и делать общие права к разным разделам. Или делиться информаций внутри отдела.</p>
        </div>
        <form @submit.prevent="submit" class="dark:border-slate-500 shadow rounded-xl bg-white text-slate-900 dark:bg-slate-800 w-full max-w-screen-2xl">
            <dl>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-md font-medium text-gray-500 dark:text-gray-400">Название <p class="mt-1 text-sm opacity-80">Краткое название отдела</p></dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><text-input v-model="form.name" :error="errors.name" /></dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-md font-medium text-gray-500 dark:text-gray-400">Описание <p class="mt-1 text-sm opacity-80">За что отвечает или чем занимается данный отдел</p></dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><textarea-input v-model="form.description" :error="errors.description" /></dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-md font-medium text-gray-500 dark:text-gray-400">Руководитель <p class="mt-1 text-sm opacity-80">Руководитель отдела может добавлять или удалять из отдела(группы) работников.</p></dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <treeselect v-model="form.owner" :options="users" :normalizer="normalizer" placeholder="Руководитель..." id="owner" />
                    </dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-md font-medium text-gray-500 dark:text-gray-400">Сотрудники отдела
                        <p class="mt-1 text-sm opacity-80">Сотрудники отдела, которые учавствуют в работе. Они получат доступ к страницам, как и отдел.</p>
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><treeselect v-model="form.workers" :options="users" multiple :normalizer="normalizer" placeholder="Сотрудники..." id="permissions" /></dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-md font-medium text-gray-500 dark:text-gray-400">Права доступа
                        <p class="mt-1 text-sm opacity-80">К чему имеет доступ данный отдел? Права глобальные, поэтому выбирайте с умом!</p>
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><treeselect v-model="form.permissions" :options="permissions" multiple :normalizer="normalizer" placeholder="Права..." id="permissions" /></dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-md font-medium text-gray-500 dark:text-gray-400">Фильтры предприятия
                        <p class="mt-1 text-sm opacity-80">Отображает в таблице "колонки" по умолчанию для данного отдела.</p>
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><treeselect v-model="form.entr_filter" :options="entr_filter" multiple :normalizer="enterprise" placeholder="Колонки предприятий..." id="enterprise" /></dd>
                </div>
            </dl>
            <div class="p-6 flex items-center">
                <button class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Удалить</button>
                <loading-button :loading="form.processing" class="btn-green mx-auto w-full max-w-xs" type="submit">Обновить</loading-button>
            </div>
        </form>
        </setting>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Setting from '@/Pages/Settings/Layout'
import LoadingButton from '@/Shared/LoadingButton'
import TextInput from '@/Shared/TextInput'
import TextareaInput from '@/Shared/TextareaInput'
import { Link } from '@inertiajs/inertia-vue3'
import Treeselect from '@bosquig/vue3-treeselect'


export default {
    components: {
        LoadingButton,
        TextInput,
        Link,
        Treeselect,
        AppLayout,
        Setting,
        TextareaInput
    },
    props: {
        errors: Object,
        permissions: Array,
        users: Array,
        departments: Array,
        entr_filter: Array,
    },
    data() {
        return {
            form: this.$inertia.form({
                name: this.departments.name,
                description: this.departments.description,
                owner: this.departments.owner,
                permissions: this.departments.permissions.length ? this.departments.permissions : null,
                workers: this.departments.workers.length ? this.departments.workers : null,
                entr_filter: this.departments.entr_filter ? JSON.parse(this.departments.entr_filter) : []
            }),
            normalizer(node) {
                return {
                    id: node.id,
                    label: node.title || node.name,
                }
            },
            enterprise(node) {
                return {
                    id: node.id,
                    label: node.name,
                }
            },
        }
    },
    methods: {
        submit() {
            this.form.put(route('departments.update', this.departments.id))
        },
        destroy() {
            if (confirm('Вы действительно хотите удалить отдел?')) {
                this.$inertia.delete(this.route('departments.destroy', this.departments.id))
            }
        },

    },
}
</script>
