<template>
<app-layout title="Права доступа">
    <setting class="p-9">

        <div class="mb-6 flex justify-between items-center align-center">
        <h1 class="mb-8 font-bold text-3xl dark:text-slate-300">Права доступа</h1>
        <Link class="btn-green ml-auto" :href="route('permissions.create')">
            <span>Новый</span>
            <span class="hidden md:inline"> доступ</span>
        </Link>
        </div>
        <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full whitespace-no-wrap table-fixed">
            <tr class="text-left font-bold">
            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Название</th>
            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Права доступа</th>
            </tr>
            <tr v-for="item in permission.data" :key="item.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
            <td class="border-t">
                <Link class="px-5 py-4 flex items-center focus:text-pink-500" :href="route('permissions.edit', item.id)">
                <span class="truncate">{{ item.name }}</span>
                </Link>
            </td>
            <td class="border-t">
                <Link class="px-4 py-4 flex items-center" :href="route('permissions.edit', item.id)" tabindex="-1">

                </Link>
            </td>
            </tr>
            <tr v-if="permission.data.length === 0">
            <td class="border-t px-6 py-4" colspan="4">Ничего нет.</td>
            </tr>
        </table>
        </div>
        <pagination :links="permission.links" />

    </setting>
</app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Setting from '@/Pages/Settings/Layout'
import { Link } from '@inertiajs/inertia-vue3'
import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'

export default {
  components: {
    Pagination,
    Link,
    Setting,
    AppLayout
  },
  props: {
    permission: Object,
    filters: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
      },
    }
  },
  watch: {
    form: {
      handler: throttle(function() {
        let query = pickBy(this.form)
        this.$inertia.replace(this.route('permission.index', Object.keys(query).length ? query : { remember: 'forget' }))
      }, 150),
      deep: true,
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
  },
}
</script>
