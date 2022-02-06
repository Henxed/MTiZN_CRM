<template>

    <div class="md:flex w-full">
        <div>
            <div class="sticky top-5">
                <div class="bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-300 p-7 rounded-xl md:w-72">
                    <div class="mb-5 text-lg text-center uppercase">Меню</div>

                    <Link class="block mb-4 p-2 px-4 rounded-xl bg-gray-200/50 dark:bg-slate-600/30"
                            :class="{ 'bg-gray-300 dark:bg-slate-600 dark:text-slate-300' : route().current('users.index')}"
                            :href="route('users.index')">
                        Пользователи
                    </Link>
                    <Link class="block mb-4 p-2 px-4 rounded-xl bg-gray-200/50 dark:bg-slate-600/30"
                            :class="{ 'bg-gray-300 dark:bg-slate-600 dark:text-slate-300' : route().current('roles.index')}"
                            :href="route('roles.index')">
                        Роли
                    </Link>
                    <Link class="block mb-4 p-2 px-4 rounded-xl bg-gray-200/50 dark:bg-slate-600/30"
                            :class="{ 'bg-gray-300 dark:bg-slate-600 dark:text-slate-300' : route().current('permissions.index')}"
                            :href="route('permissions.index')">
                        Права доступа
                    </Link>
                </div>
            </div>
        </div>
        <div class="rounded-xl bg-white text-slate-900 md:mx-6 dark:bg-slate-800 w-full max-w-screen-2xl">
            <slot></slot>
        </div>
    </div>

</template>


<script>
    import { defineComponent } from 'vue';
    import { Head, Link } from '@inertiajs/inertia-vue3';

    export default defineComponent({
        components: {
            Head,
            Link,
        }
    })
</script>

