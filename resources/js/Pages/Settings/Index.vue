<template>
    <app-layout title="Настройки">
        <setting>
            <div class="p-9">Тут настройки</div>
        </setting>
    </app-layout>
</template>


<script>
    import { defineComponent } from 'vue';
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import AppLayout from '@/Layouts/AppLayout';
    import Setting from '@/Pages/Settings/Layout'

    export default defineComponent({
        components: {
            AppLayout,
            Head,
            Link,
            Setting
        },
        data() {
            return {
                domain: window.location.host,
            }
        }
    })
</script>

