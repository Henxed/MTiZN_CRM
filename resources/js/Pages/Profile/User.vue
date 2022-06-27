<template>
    <app-layout title="Профиль">
        <div class="w-full max-w-screen-2xl">
            <div class="grid sm:grid-cols-4 items-center">
                <div class="py-4 sm:col-span-3">
                    <div class="flex items-center">
                        <div v-if="$page.props.jetstream.managesProfilePhotos" class="text-sm rounded-full focus:outline-none transition mr-4">
                            <img class="h-24 w-24 rounded-full object-cover" :src="user_info.profile_photo_url" :alt="user_info.name" />
                        </div>
                        <div class="">
                            <div class="text-3xl dark:text-slate-300 flex items-center">{{ user_info.name }} <span class="ml-2 text-lg text-slate-400">@{{ user_info.username }}</span></div>

                            <div class="text-slate-500">{{ user_info.d_name }}</div>
                        </div>
                    </div>

                </div>

                <div class="dark:text-slate-300">
                Тут личный профиль пользователя. У каждого будет своя информация. Можно сделать микро-блог для новостей коллегам или что-то интересней\полезней. (Достижение, рейтинг и т.д.)</div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout.vue'

    export default {
        props: {
            user_info: Array,
        },

        components: {
            AppLayout
        },
    }
</script>
