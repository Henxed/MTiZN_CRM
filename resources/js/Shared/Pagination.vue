<template>
  <div v-if="links.length > 3">
    <div class="flex flex-wrap -mb-1 mt-3">
      <template v-for="(link, key) in links">
        <div v-if="link.url === null" :key="key" class="mb-1 mr-1 px-4 py-3 text-gray-400/70 w-auto border-gray-400/70 cursor-not-allowed leading-none border rounded-md text-base" v-html="link.label" />
        <Link v-else :key="`link-${key}`" class="mb-1 mr-1 flex items-center justify-center h-10 px-4 rounded-md text-base leading-none border focus:outline-none
        " :class="link.active ? 'border-slate-800 bg-slate-800 text-slate-100 hover:text-slate-100 dark:bg-slate-300 dark:border-slate-300 dark:text-slate-800' : 'border-slate-300 text-slate-400 dark:text-slate-300 dark:border-slate-400 hover:border-slate-500 hover:text-slate-500 '" :href="link.url" v-html="link.label" />
      </template>
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
