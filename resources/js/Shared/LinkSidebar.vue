<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Link } from '@inertiajs/inertia-vue3';

    export default defineComponent({
        components: {
            Link,
        },
        props: ['href', 'active'],

        computed: {
            classes() {
                return this.active
                    ? 'flex items-center text-slate-900 dark:text-slate-300 px-3 py-2 my-1 rounded-xl bg-gray-200/80 dark:bg-slate-600/30'
                    : 'flex items-center text-slate-900 dark:text-slate-300 slink px-3 py-2 my-1'
            }
        }
    })
</script>

<style scope>
.slink{
    transition: transform .3s ease;
}
.slink:hover {
    transform: translate3d(5px,0,0);
}
</style>
