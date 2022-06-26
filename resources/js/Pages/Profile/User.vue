<template>
    <app-layout title="Профиль">
        <div class="w-full max-w-screen-2xl">
            <div class="grid sm:grid-cols-4 items-center">
                <div class="py-4 sm:col-span-3">
                    <div class="text-3xl dark:text-slate-300">{{ user_info.name }}</div>
                </div>
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
