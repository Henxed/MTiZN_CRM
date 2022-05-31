<template>

    <div class="md:flex w-full">
        <div class="mb-4 border-l border-l-slate-300 dark:border-l-slate-700 ">
            <div class="sticky top-5">
                <div class=" text-slate-900 dark:text-slate-300 p-4 sm:w-80">
                    <div class="text-slate-400 w-full border-b py-2 uppercase border-slate-300 dark:border-slate-500 mb-4 text-xs font-normal">
                        Общее
                    </div>
                    <slink :href="route('stats-load')" :active="route().current('stats-load')" v-if="$page.props.access.can.includes('cp.stats-load') || $page.props.access.role.includes('super-admin')">
                        Загрузка статистики районов
                    </slink>
                    <slink :href="route('entr-load')" :active="route().current('entr-load')" v-if="$page.props.access.can.includes('cp.entr-load') || $page.props.access.role.includes('super-admin')">
                        Загрузка данных предприятий
                    </slink>
                    <slink :href="route('departments.index')" :active="route().current('departments.*')" v-if="$page.props.access.can.includes('cp.departments') || $page.props.access.role.includes('super-admin')">
                        Отделы
                    </slink>
                    <div class="text-slate-400 w-full border-b py-2 uppercase border-slate-300 dark:border-slate-500 mb-4 text-xs font-normal" v-if="$page.props.access.can.includes('cp.users')">
                        Администрирование
                    </div>
                    <slink :href="route('users.index')" :active="route().current('users.*')" v-if="$page.props.access.can.includes('cp.users') || $page.props.access.role.includes('super-admin')">
                        Пользователи
                    </slink>
                    <slink :href="route('roles.index')" :active="route().current('roles.*')" v-if="$page.props.access.can.includes('cp.roles') || $page.props.access.role.includes('super-admin')">
                        Роли
                    </slink>
                    <slink :href="route('permissions.index')" :active="route().current('permissions.*')" v-if="$page.props.access.can.includes('cp.permission') || $page.props.access.role.includes('super-admin')">
                        Доступы
                    </slink>


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
    import Slink from '@/Shared/LinkSidebar'

    export default defineComponent({
        components: {
            Head,
            Link,
            Slink,
        },
        props: ['class']
    })
</script>

