<template>
    <Head title="Восстановление пароля" />

    <jet-authentication-card>


        <div class="max-w-lg">

            <div class="mb-4 text-sm text-gray-600">
                Забыли Ваш пароль? Напишите ваш адрес электронной почты, и мы отправим вам ссылку сброса пароля, которая позволит ввести новый.
            </div>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <jet-validation-errors class="mb-4" />

            <form @submit.prevent="submit">
                <div>
                    <jet-label for="email" value="E-mail" />
                    <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus />
                </div>

                <div class="flex items-center justify-between mt-4">
                    <Link :href="route('login')" class="flex font-medium text-indigo-600 hover:text-indigo-500">
                        Авторизация
                    </Link>
                    <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Сбросить пароль
                    </jet-button>
                </div>
            </form>
        </div>
    </jet-authentication-card>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'

    export default defineComponent({
        components: {
            Head,
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetLabel,
            JetValidationErrors,
            Link
        },

        props: {
            status: String
        },

        data() {
            return {
                form: this.$inertia.form({
                    email: ''
                })
            }
        },

        methods: {
            submit() {
                this.form.post(this.route('password.email'))
            }
        }
    })
</script>
