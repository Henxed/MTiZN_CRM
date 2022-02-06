<template>
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center align-center">
      <h1 class="mb-8 font-bold text-3xl">Права доступа</h1>
      <inertia-link class="btn-pink ml-auto" :href="route('permission.create')">
        <span>Новый</span>
        <span class="hidden md:inline">доступ</span>
      </inertia-link>
    </div>
    <div class="bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-no-wrap table-fixed">
        <tr class="text-left font-bold">
          <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Название</th>
          <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Права доступа</th>
        </tr>
        <tr v-for="item in permission.data" :key="item.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <inertia-link class="px-5 py-4 flex items-center focus:text-pink-500" :href="route('permission.edit', item.id)">
              <span class="truncate">{{ item.name }}</span>
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-4 py-4 flex items-center" :href="route('permission.edit', item.id)" tabindex="-1">

            </inertia-link>
          </td>
        </tr>
        <tr v-if="permission.data.length === 0">
          <td class="border-t px-6 py-4" colspan="4">Ничего нет.</td>
        </tr>
      </table>
    </div>
    <pagination :links="permission.links" />
  </div>
</template>

<script>
import Layout from '@/Layouts/AppLayout'
import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'

export default {
  metaInfo: { title: 'Пользователи' },
  layout: Layout,
  components: {
    Pagination
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
