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
                    ? 'flex px-1 text-sm font-medium text-center text-pink-400 hover:text-yellow-400 focus:outline-none p-4 sm:p-0 mb-4 sm:mb-0 bg-white bg-opacity-20 sm:bg-transparent'
                    : 'flex px-1 text-sm font-medium text-center text-white hover:text-yellow-400 focus:outline-none p-4 sm:p-0 mb-4 sm:mb-0 hover:bg-white hover:bg-opacity-40 hover:sm:bg-transparent '
            }
        }
    })
</script>
