<template>
    <app-layout title="Редактировать пользователя">
        <setting class="px-9">

        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-2xl leading-6 font-medium text-gray-900 dark:text-slate-300 ">{{ this.users.name }}</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Персональная информация о пользователе</p>
        </div>
        <form @submit.prevent="submit" class="dark:border-slate-500 shadow rounded-xl bg-white text-slate-900 dark:bg-slate-800 w-full max-w-screen-2xl">
            <dl>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-md font-medium text-gray-500 dark:text-gray-400">ФИО <p class="mt-1 text-sm opacity-80">Как зовут владельца аккаунта?</p></dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><text-input v-model="form.name" :error="errors.name" /></dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-md font-medium text-gray-500 dark:text-gray-400">Электронная почта <p class="mt-1 text-sm opacity-80">Обновите адрес электронной почты, которая является логином для входа. </p></dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><text-input v-model="form.email" :error="errors.email" type="email" /></dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-md font-medium text-gray-500 dark:text-gray-400">Пароль <p class="mt-1 text-sm opacity-80">Обновите пароль, если пользователь забыл его и не может восстановить в автоматическом режиме. </p></dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><text-input v-model="form.password" :error="errors.password" /><small class="text-sm text-slate-600 dark:text-slate-400/70"><span class="text-sm text-slate-600 dark:text-slate-400/70 hover:opacity-80 cursor-pointer" @click="randomPassword(12)">Сгенерировать</span> | Оставьте пустым данное поле, если не нужно менять пароль</small></dd>
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
        users: Object,
        roles: Array,
        permissions: Array,
        regions: Array,
    },
    remember: 'form',
    data() {
        return {
        form: this.$inertia.form({
            _method: 'put',
            name: this.users.name,
            email: this.users.email,
            password: this.users.password,
            regions: this.users.areas.length ? this.users.areas : null,
            roles: this.users.roles.length ? this.users.roles : null,
            permissions: this.users.permissions.length ? this.users.permissions : null,
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
            this.form.post(this.route('users.update', this.users.id), {
            onSuccess: () => this.form.reset('password'),
        })
        },
        destroy() {
            if (confirm('Вы действительно хотите удалить пользователя?')) {
                this.$inertia.delete(this.route('users.destroy', this.users.id))
            }
        },

    },
}
</script>
