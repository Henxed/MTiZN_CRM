<template>
    <app-layout title="Редактировать пользователя">
        <setting class="p-9">

        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-2xl leading-6 font-medium text-gray-900 dark:text-slate-300 ">Добавление нового отдела</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Отделы позволяют собирать людей в группы и делать общие права к разным разделам. Или делиться информаций внутри отдела.</p>
        </div>
        <form @submit.prevent="submit" class="border-t border-gray-200 dark:border-slate-500">
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
            </dl>
            <div class="mt-6 flex items-center">
                <loading-button :loading="form.processing" class="btn-green mx-auto w-full max-w-xs" type="submit">Добавить</loading-button>
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
    },
    data() {
        return {
        sending: false,
        form: this.$inertia.form({
            name: '',
            description: '',
            owner: '',
            permissions: null,
            workers: null,
        }),
        normalizer(node) {
            return {
                id: node.id,
                label: node.name,
            }
        },
        owner(node) {
            return {
                id: node.id,
                label: node.name,
            }
        },
        }
    },
    methods: {
        submit() {
            this.form.post(this.route('departments.store'))
        }

    },
}
</script>
