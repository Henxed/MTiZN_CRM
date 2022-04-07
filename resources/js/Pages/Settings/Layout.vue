<template>

    <div class="md:flex w-full">
        <div class="mb-4">
            <div class="sticky top-5">
                <div class="bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-300 p-5 shadow-lg rounded-2xl md:w-72">

                    <div class="text-slate-400 w-full border-b-2 pb-2 uppercase border-gray-100 dark:border-slate-500 mb-4 text-md font-normal">
                        Меню
                    </div>

                    <Link class="block mb-4 p-2 px-4 rounded-xl bg-gray-200/50 dark:bg-slate-600/30"
                            :class="{ 'bg-gray-300 dark:bg-slate-600 dark:text-slate-300' : route().current('users.*')}"
                            :href="route('users.index')">
                        Пользователи
                    </Link>
                    <Link class="block mb-4 p-2 px-4 rounded-xl bg-gray-200/50 dark:bg-slate-600/30"
                            :class="{ 'bg-gray-300 dark:bg-slate-600 dark:text-slate-300' : route().current('roles.*')}"
                            :href="route('roles.index')">
                        Роли
                    </Link>
                    <Link class="block mb-4 p-2 px-4 rounded-xl bg-gray-200/50 dark:bg-slate-600/30"
                            :class="{ 'bg-gray-300 dark:bg-slate-600 dark:text-slate-300' : route().current('permissions.*')}"
                            :href="route('permissions.index')">
                        Права доступа
                    </Link>
                </div>
            </div>
        </div>
        <div :class="class">
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
        },
        props: ['class']
    })
</script>

