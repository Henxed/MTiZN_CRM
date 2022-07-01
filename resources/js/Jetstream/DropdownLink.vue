<template>
    <div>
        <button type="submit" class="block rounded w-full px-2 py-1 text-sm leading-5 text-slate-500 dark:text-slate-200 text-left hover:bg-slate-300 dark:hover:bg-slate-600/40 hover:bg-opacity-50 focus:outline-none focus:bg-gray-100/10 transition" v-if="as == 'button'">
            <slot></slot>
        </button>

        <a :href="href" class="block rounded px-3 py-2 text-sm leading-5 text-slate-500 dark:text-slate-200 dark:hover:bg-slate-600/40 hover:bg-slate-300 hover:bg-opacity-50 focus:outline-none focus:bg-gray-100/10 transition" v-else-if="as =='a'">
            <slot></slot>
        </a>

        <Link :href="href" class="block rounded px-2 py-1 text-sm leading-5 text-slate-500 dark:text-slate-200 hover:bg-slate-300 dark:hover:bg-slate-600/40 hover:bg-opacity-50 focus:outline-none focus:bg-gray-100/10 transition" v-else>
            <slot></slot>
        </Link>
    </div>
</template>

<script>
    import { defineComponent } from 'vue';
    import { Link } from '@inertiajs/inertia-vue3';

    export default defineComponent({
        components: {
            Link,
        },
        props: ['href', 'as']
    })
</script>
