<template>
    <!-- <Link :href="href" class="flex p-3 rounded-xl overflow-hidden relative h-48 min-w-48 w-full max-w-64"
    :class="`bg-${color}-100 dark:bg-${color}-500/30`">
        <div class="absolute -right-5 -bottom-8 text-9xl" :class="`text-${color}-200 dark:text-${color}-200/30`">
            <i :class="`fi fi-rr-${icon}`"></i>
        </div>
        <div class="text-lg" :class="`text-${color}-500`">
            <slot />
        </div>

    </Link> -->

    <Link :href="href" class="group block max-w-xs mx-auto rounded-lg p-6 bg-white ring-1 ring-slate-900/5 shadow-lg space-y-3 hover:bg-sky-500 overflow-hidden relative ">
    <div class="flex items-center space-x-3 ">
        <div class="absolute -right-5 -bottom-8 text-9xl" :class="`text-${color}-200 dark:text-${color}-200/30`">
            <i :class="`fi fi-rr-${icon}`"></i>
        </div>
        <h3 class="text-slate-900 group-hover:text-white text-sm font-semibold">New project</h3>
    </div>
    <p class="text-slate-500 group-hover:text-white text-sm">Create a new project from a variety of starting templates.</p>
    </Link>
</template>

<script>
    import { Link } from '@inertiajs/inertia-vue3';

    export default {
        components: {
            Link,
        },
        props: ['href', 'color', 'icon'],
    }
</script>

