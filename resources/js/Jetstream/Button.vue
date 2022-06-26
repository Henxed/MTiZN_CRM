<template>
    <button :type="type" class="inline-flex items-center px-4 py-2 bg-slate-600 border border-transparent rounded-md text-xs text-white uppercase tracking-widest hover:bg-slate-700 focus:outline-none disabled:opacity-25 transition">
        <slot></slot>
    </button>
</template>

<script>
    import { defineComponent } from 'vue'

    export default defineComponent({
        props: {
            type: {
                type: String,
                default: 'submit',
            },
        }
    })
</script>
