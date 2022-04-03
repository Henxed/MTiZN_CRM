<template>
    <Head title="Вход" />

    <jet-authentication-card>



          <div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full">
            <div class="mb-4">
                <logo class=" h-12 w-auto"/>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Войдите в свой аккаунт</h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                Или
                {{ ' ' }}
                <a href="#" class="font-medium text-slate-600 hover:text-slate-500"> запросите доступ у администратора </a>
                </p>
            </div>
            <jet-validation-errors />

            <div v-if="status" class="mt-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>
            <form class="mt-4 space-y-6" @submit.prevent="submit">
                <input type="hidden" name="remember" value="true" />
                <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email-address" class="sr-only">Электронный адрес</label>
                    <input id="email-address" type="email" autocomplete="email" v-model="form.email" required autofocus class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-slate-500 focus:border-slate-500 focus:z-10 sm:text-sm" placeholder="Электронный адрес" />
                </div>
                <div>
                    <label for="password" class="sr-only">Пароль</label>
                    <input id="password" type="password" v-model="form.password" required autocomplete="current-password" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-slate-500 focus:border-slate-500 focus:z-10 sm:text-sm" placeholder="Пароль" />
                </div>
                </div>

                <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" v-model="form.remember" type="checkbox" class="h-4 w-4 text-slate-600 focus:ring-slate-500 border-gray-300 rounded" />
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900"> Запомнить меня </label>
                </div>

                <div class="text-sm">
                    <Link v-if="canResetPassword" :href="route('password.request')" class="font-medium text-slate-600 hover:text-slate-500">
                        Забыли пароль?
                    </Link>
                </div>
                </div>

                <div>
                    <loading-button :loading="form.processing" class="button btn-slate w-full" type="submit">Войти</loading-button>
                </div>
            </form>
            </div>
        </div>
    </jet-authentication-card>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue'
    import Logo from '@/Jetstream/AuthenticationCardLogo.vue'
    import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
    import LoadingButton from '@/Shared/LoadingButton'
    import { Head, Link } from '@inertiajs/inertia-vue3';

    export default defineComponent({
        components: {
            Head,
            JetAuthenticationCard,
            JetValidationErrors,
            Link,
            LoadingButton,
            Logo
        },

        props: {
            canResetPassword: Boolean,
            status: String
        },

        data() {
            return {
                form: this.$inertia.form({
                    email: '',
                    password: '',
                    remember: false
                })
            }
        },

        methods: {
            submit() {
                this.form.post(this.route('login'), {
                    onFinish: () => this.form.reset('password'),
                })
            }
        }
    })
</script>
