<template>
  <div v-if="links.length > 3">
  <div class="flex items-center justify-center pb-10 lg:px-0 sm:px-6 px-4">
        <div class="w-full flex items-center justify-between border-t border-slate-200 dark:border-slate-600/50 leading-none">
            <Link :href="links[0].url" preserve-scroll class="flex items-center pt-3 text-gray-600" :class="links[0].url === null ? 'text-0' : 'hover:text-indigo-700 dark:text-slate-300 dark:hover:text-slate-500 cursor-pointer'">
                <svg width="14" height="8" viewBox="0 0 14 8" fill="none" xmlns="http://www.w3.org/2000/svg" :class="{'hidden' :links[0].url === null}">
                    <path d="M1.1665 4H12.8332" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M1.1665 4L4.49984 7.33333" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M1.1665 4.00002L4.49984 0.666687" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <p class="text-sm ml-3 font-medium leading-none ">Назад</p>
            </Link>
            <div class="sm:flex hidden" >
                <template v-for="(link, key) in links" :key="key">
                    <div v-if="key > 0 && key < links.length-1">
                    <p  class="flex items-center text-sm font-medium leading-none cursor-pointer text-gray-600 hover:text-indigo-700 border-t border-transparent hover:border-indigo-400 pt-3 mr-4 px-2" v-if="link.url === null" v-html="link.label" />

                    <Link v-else :key="`link-${key}`" class="flex items-center text-sm font-medium leading-none cursor-pointer border-t pt-3 mr-4 px-2" :class="link.active ? 'text-indigo-700 border-indigo-400 dark:text-slate-400 dark:border-slate-500':'border-transparent  hover:border-indigo-400 dark:hover:border-slate-500 dark:text-slate-200' " :href="link.url" v-html="link.label" />
                    </div>
                </template>
            </div>
            <Link :href="links[links.length-1].url" preserve-scroll class="flex items-center pt-3 text-gray-600" :class="links[links.length-1].url === null ? 'text-0' : 'hover:text-indigo-700 dark:text-slate-300 dark:hover:text-slate-500 cursor-pointer'">
                <p class="text-sm font-medium leading-none mr-3">Вперед</p>
                <svg width="14" height="8" viewBox="0 0 14 8" fill="none" :class="{'hidden' : links[links.length-1].url === null}" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.1665 4H12.8332" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9.5 7.33333L12.8333 4" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9.5 0.666687L12.8333 4.00002" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
            </Link>
        </div>
    </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
export default {
  components: {
    Link,
  },
  props: {
    links: Array,
  },
}
</script>
