<template>
    <div>
    <Head :title="title" />

    <jet-banner />

<div class="flex justify-between items-center py-4 px-5">
    <div class="flex grow items-center">
      <button class="menu-side" @click="menu = !menu"><div class="burger" :class="menu ? 'is-active' : ''"></div></button>
      <div class="text-xl mx-6 text-slate-800 dark:text-slate-100 font-bold">CRM</div>
      <div class="flex items-center bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 rounded-full max-w-sm w-full pr-3 shadow-md h-10 overflow-hidden">
        <input class="border-none focus:outline-none focus:ring-0 flex-1 h-full w-full p-4 bg-white dark:bg-slate-800  rounded-full" type="text" placeholder="Поиск">
        <i class="fi fi-rr-search pt-1"></i>
      </div>
    </div>
    <div class="flex items-center">
        <ThemeToggler class="mr-2 flex items-center justify-center leading-none h-10 w-10 rounded-md text-slate-700 hover:bg-slate-300  dark:text-slate-300 dark:hover:bg-slate-600 focus:outline-none"/>
        <button class="mr-2 flex items-center justify-center leading-none p-3 h-10 w-10 rounded-md text-slate-700 hover:bg-slate-300  dark:text-slate-300 dark:hover:bg-slate-600 focus:outline-none">
            <i class="fi fi-rr-bell icon-size"></i>
        </button>

        <jet-dropdown align="right" width="48" class="border-l-2 border-l-slate-300 pl-3">
            <template #trigger>
                <div class="flex items-center relative">
                    <button v-if="$page.props.jetstream.managesProfilePhotos" class="text-sm rounded-full focus:outline-none transition">
                        <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
                    </button>
                    <button type="button" class="flex font-bold px-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none uppercase">
                        {{ $page.props.user.name }}

                        <svg class="ml-1 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </template>

            <template #content>
                <!-- Account Management -->
                <div class="block px-4 py-2 text-xs text-slate-600 dark:text-slate-200">
                    Настройки аккаунта
                </div>

                <jet-dropdown-link :href="route('profile.show')">
                    Профиль
                </jet-dropdown-link>

                <jet-dropdown-link :href="route('api-tokens.index')" v-if="$page.props.jetstream.hasApiFeatures">
                    API токены
                </jet-dropdown-link>

                <div class="border-t border-slate-400 my-2"></div>

                <!-- Authentication -->
                <form @submit.prevent="logout">
                    <jet-dropdown-link as="button">
                        Выход
                    </jet-dropdown-link>
                </form>
            </template>
        </jet-dropdown>

    </div>
  </div>


    <main class="flex h-full p-4 text-xl">
        <div class="flex py-6 flex-col" :class="menu ? 'menu-side-open' : ''">
            <a :href="route('home')" class="app-sidebar-link" :class="route().current('home') ? 'active': ''" v-tippy="!menu ? 'Главная' : '' ">
                <i class="fi fi-rr-home"></i>
                <span class="text-slate-800 dark:text-slate-100">Главная</span>
            </a>
            <a href="" class="app-sidebar-link" v-tippy="!menu ? 'Электронный журнал НПА' : ''">
                <i class="fi fi-rr-document"></i>
                <span class="text-slate-800 dark:text-slate-100">Электронный журнал НПА</span>
            </a>
            <a href="" class="app-sidebar-link" v-tippy="!menu ? 'Карта' : ''">
                <i class="fi fi-rr-map-marker"></i>
                <span class="text-slate-800 dark:text-slate-100">Карта</span>
            </a>
            <a href="" class="app-sidebar-link" v-tippy="!menu ? 'Настройки' : ''">
                <i class="fi fi-rr-settings"></i>
                <span class="text-slate-800 dark:text-slate-100">Настройки</span>
            </a>
        </div>

            <slot></slot>

    </main>

    <flash />
</div>
</template>

<script>
    import { defineComponent } from 'vue'
    import { mapGetters } from "vuex";
    import JetApplicationMark from '@/Jetstream/ApplicationMark.vue'
    import JetBanner from '@/Jetstream/Banner.vue'
    import JetDropdown from '@/Jetstream/Dropdown.vue'
    import JetDropdownLink from '@/Jetstream/DropdownLink.vue'
    import JetNavLink from '@/Jetstream/NavLink.vue'
    import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import Flash from '@/Shared/Flash.vue'
    import ThemeToggler from "@/Shared/ThemeToggler.vue";

    export default defineComponent({
        props: {
            title: String,
        },

        components: {
            Head,
            JetApplicationMark,
            JetBanner,
            JetDropdown,
            JetDropdownLink,
            JetNavLink,
            JetResponsiveNavLink,
            Link,
            Flash,
            ThemeToggler
        },
        beforeMount() {
            this.$store.dispatch("initTheme");
        },
        data() {
            return {
                showingNavigationDropdown: false,
                menu: false
            }
        },
        computed: {
            ...mapGetters({ theme: "getTheme" }),
        },
        watch: {
            theme(newTheme, oldTheme) {
            newTheme === "light"
                ? document.querySelector("html").classList.remove("dark")
                : document.querySelector("html").classList.add("dark");
            },
        },
        methods: {
            logout() {
                this.$inertia.post(route('logout'));
            }
        }
    })
</script>
