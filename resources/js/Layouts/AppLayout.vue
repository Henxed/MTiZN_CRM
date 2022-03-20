<template>
    <div>
    <Head :title="title" />

    <jet-banner />

<div class="flex justify-between items-center py-4 px-5">
    <div class="flex grow items-center">
      <div class="menu-fake">
        <button class="menu-side" :class="{'fixed sm:block' : menu}" @click="menu = !menu"><div class="burger" :class="{'is-active' : menu}"></div></button>
      </div>
      <div class="text-sm sm:text-xl ml-2 sm:mx-8 text-slate-800 dark:text-slate-300 font-bold leading-none">
          Информационно аналитическая система
          <div class="text-sm font-normal hidden sm:block text-slate-500">Министерства труда и занятости населения Оренбургской области</div>
      </div>
      <!-- <div class="flex items-center bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 rounded-full max-w-sm w-full pr-3 shadow-md h-10 overflow-hidden">
        <input class="border-none focus:outline-none focus:ring-0 flex-1 h-full w-full p-4 bg-white dark:bg-slate-800  rounded-full" type="text" placeholder="Поиск">
        <i class="fi fi-rr-search pt-1"></i>
      </div> -->
    </div>
    <div class="flex items-center">
        <theme-toggler class="mr-2 flex items-center justify-center leading-none h-10 w-10 rounded-md text-slate-700 hover:bg-slate-300  dark:text-slate-300 dark:hover:bg-slate-600 focus:outline-none"/>
        <notify class="mr-2 flex items-center justify-center leading-none h-10 w-10 rounded-md text-slate-700 hover:bg-slate-300  dark:text-slate-300 dark:hover:bg-slate-600 focus:outline-none"/>


        <jet-dropdown align="right" width="48" class="border-l-2 border-l-slate-300 pl-3">
            <template #trigger>
                <div class="block w-8 pt-1 sm:pt-0 sm:flex items-center relative sm:w-auto">
                    <button v-if="$page.props.jetstream.managesProfilePhotos" class="text-sm rounded-full focus:outline-none transition">
                        <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
                    </button>
                    <button type="button" class="flex font-bold px-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none uppercase">
                        <div class="hidden sm:flex">
                            {{ $page.props.user.email.split('@')[0] }}

                            <svg class="ml-1 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
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


    <main class="flex h-full p-4">
        <div class="py-6 mr-6 flex-col sm:flex" :class="menu ? 'menu-side-open flex' : 'hidden'">
            <a :href="route('home')" class="app-sidebar-link" :class="{'active' : route().current('home')}" v-tippy="{ placement : 'right', content: !menu ? 'Главная' : '' }">
                <i class="fi fi-rr-home"></i>
                <span class="text-slate-800 dark:text-slate-100">Главная</span>
            </a>
            <a :href="route('registry.list', ['npa', 'all'])" class="app-sidebar-link" :class="{'active' : route().current('registry.*')}" v-tippy="{ placement : 'right', content: !menu ? 'Реестры' : '' }">
                <i class="fi fi-rr-document"></i>
                <span class="text-slate-800 dark:text-slate-100">Реестры</span>
            </a>
            <a :href="route('regions.index')" class="app-sidebar-link" :class="{'active' : route().current('regions.*')}" v-tippy="{ placement : 'right', content: !menu ? 'Регионы' : ''}">
                <i class="fi fi-rr-map-marker"></i>
                <span class="text-slate-800 dark:text-slate-100">Регионы</span>
            </a>
            <a v-if="$page.props.access.can.includes('cp') || $page.props.access.role.includes('super-admin')" :href="route('settings')" class="app-sidebar-link" :class="{'active' : 'settings' === $page.url.split('/')[1]}" v-tippy="{ placement : 'right', content: !menu ? 'Настройки' : ''}">
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
    import Notify from '@/Shared/Notify.vue';

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
            ThemeToggler,
            Notify,
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
