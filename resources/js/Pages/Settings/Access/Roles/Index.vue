<template>
<app-layout title="Роли">
    <setting class="p-9">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-center align-center">
            <h1 class="mb-8 font-bold text-3xl">Роли</h1>
            <Link class="btn-pink ml-auto" :href="route('roles.create')">
                <span>Новая</span>
                <span class="hidden md:inline">роль</span>
            </Link>
            </div>
            <div class="bg-white rounded shadow overflow-x-auto">
            <table class="w-full whitespace-no-wrap table-fixed">
                <tr class="text-left font-bold">
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Название</th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Права доступа</th>
                </tr>
                <tr v-for="item in roles.data" :key="item.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                <td class="border-t">
                    <Link class="px-5 py-4 flex items-center focus:text-pink-500" :href="route('roles.edit', item.id)">
                    <span class="truncate">{{ item.name }}</span>
                    </Link>
                </td>
                <td class="border-t">
                    <Link class="px-4 py-4 flex items-center" :href="route('roles.edit', item.id)" tabindex="-1">

                    </Link>
                </td>
                </tr>
                <tr v-if="roles.data.length === 0">
                <td class="border-t px-6 py-4" colspan="4">Ничего нет.</td>
                </tr>
            </table>
            </div>
            <pagination :links="roles.links" />
        </div>
    </setting>
</app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Setting from '@/Pages/Settings/Layout'
import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination'
import { Link } from '@inertiajs/inertia-vue3'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'

export default {
  components: {
    Pagination,
    Link,
    AppLayout,
    Setting
  },
  props: {
    roles: Object,
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
        this.$inertia.replace(this.route('roles', Object.keys(query).length ? query : { remember: 'forget' }))
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
