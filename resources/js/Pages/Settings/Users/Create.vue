<template>
    <app-layout title="Редактировать пользователя">
        <setting class="p-9">

        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-2xl leading-6 font-medium text-gray-900 dark:text-slate-300 ">Добавление нового пользователя</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Персональная информация о пользователе</p>
        </div>
        <form @submit.prevent="submit" class="border-t border-gray-200 dark:border-slate-500">
            <dl>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-md font-medium text-gray-500 dark:text-gray-400">ФИО <p class="mt-1 text-sm opacity-80">Как зовут владельца аккаунта?</p></dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><text-input v-model="form.name" :error="errors.name" /></dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-md font-medium text-gray-500 dark:text-gray-400">Электронная почта <p class="mt-1 text-sm opacity-80">Является логином для входа и отправки уведомлений, если потеряется доступ к аккаунту.</p></dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><text-input v-model="form.email" @input="nickname" :error="errors.email" type="email" /></dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-md font-medium text-gray-500 dark:text-gray-400">Никнейм (Псевдоним) <p class="mt-1 text-sm opacity-80">Уникальное сетевое имя, по которому можно найти пользователя в системе.</p></dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><text-input v-model="form.username" :error="errors.username" /></dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-md font-medium text-gray-500 dark:text-gray-400">Пароль <p class="mt-1 text-sm opacity-80">Защищает аккаунт от несанкционированного доступа. </p></dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><text-input v-model="form.password" :error="errors.password" />
                    <small class="text-sm text-slate-600 dark:text-slate-400/70 hover:opacity-80 cursor-pointer" @click="randomPassword(12)">Сгенерировать</small></dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-md font-medium text-gray-500 dark:text-gray-400">Доступ к районам
                        <p class="mt-1 text-sm opacity-80">Если пользователь должен редактировать регионы.</p>
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><treeselect v-model="form.regions" :options="regions" multiple :normalizer="normal_a" placeholder="Регионы..." id="role" /></dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-md font-medium text-gray-500 dark:text-gray-400">Роль
                        <p class="mt-1 text-sm opacity-80">Одна роль даёт сразу ко многим правам доступа.</p>
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><treeselect v-model="form.roles" :options="roles" :normalizer="normalizer" placeholder="Роли..." id="role" /></dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-md font-medium text-gray-500 dark:text-gray-400">Дополнительные права доступа
                        <p class="mt-1 text-sm opacity-80">Если в роли нет каких-то прав доступа, то можно добавить отдельно через это меню.</p>
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
import { Link } from '@inertiajs/inertia-vue3'
import Treeselect from '@bosquig/vue3-treeselect'


export default {
    components: {
        LoadingButton,
        TextInput,
        Link,
        Treeselect,
        AppLayout,
        Setting
    },
    props: {
        errors: Object,
        roles: Array,
        permissions: Array,
        regions: Array,
    },
    data() {
        return {
        sending: false,
        form: this.$inertia.form({
            name: '',
            username: '',
            email: '',
            password: '',
            regions: null,
            roles: null,
            permissions: null,
        }),
        normalizer(node) {
            return {
                id: node.id,
                label: node.name,
            }
        },
        normal_a(node) {
            return {
                id: node.id,
                label: node.region,
            }
        },
        }
    },
    methods: {
        nickname(event) {
            let email = event.target.value;
            this.form.username = email.substring(0, email.lastIndexOf("@")) ? email.substring(0, email.lastIndexOf("@")) : email
        },
        randomPassword(length) {
            var chars = "%!*#$abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOP1234567890";
            var pass = "";
            for (var x = 0; x < length; x++) {
                var i = Math.floor(Math.random() * chars.length);
                pass += chars.charAt(i);
            }
            this.form.password = pass;
        },
        submit() {
            this.form.post(this.route('users.store'))
        }

    },
}
</script>
