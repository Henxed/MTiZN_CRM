<template>
    <setting>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-20">
            <h1 class="mb-8 font-bold text-3xl dark:text-gray-300">Users</h1>
            <div class="mb-6 flex justify-between items-center">
            <!-- <search-filter v-model.lazy="form.search" class="w-full max-w-md mr-4" @reset="reset"></search-filter> -->
            <Link class="btn-load" :href="route('users.create')">
                <span>New</span>
                <span class="ml-1 hidden md:inline">user</span>
            </Link>
            </div>
            <div class="bg-white rounded shadow overflow-x-auto">
            <table class="w-full whitespace-no-wrap table-fixed">
                <tr class="text-left font-bold">
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">E-mail</th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Role</th>
                </tr>
                <tr v-for="item in users.data" :key="item.id" class="hover:bg-gray-100 focus-within:bg-gray-100 ">
                <td class="border-t">
                    <Link class="px-5 py-4 flex items-center focus:text-pink-500 " :href="route('users.edit', item.id)">
                    <span class="truncate">{{ item.name }}</span>
                    </Link>
                </td>
                <td class="border-t">
                    <Link class="px-4 py-4 flex items-center " :href="route('users.edit', item.id)" tabindex="-1">
                    {{ item.email }}
                    </Link>
                </td>
                <td class="border-t">
                    <Link class="px-1 py-4 flex items-center" :href="route('users.edit', item.id)" tabindex="-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full m-auto bg-gray-100 text-gray-800">{{ item.roles[0] ? item.roles[0].name : 'Без роли' }}</span>
                    </Link>
                </td>
                </tr>
                <tr v-if="users.data.length === 0">
                <td class="border-t px-6 py-4" colspan="4">Ничего нет.</td>
                </tr>
            </table>
            </div>
            <pagination :links="users.links" />
        </div>
    </setting>
</template>

<script>
import Layout from '@/Layouts/AppLayout'
import Setting from '@/Pages/Settings/Layout'
import { Link } from '@inertiajs/inertia-vue3'
import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'

export default {
  metaInfo: { title: 'Пользователи' },
  layout: Layout,
  components: {
    Pagination,
    Link,
    Setting
  },
  props: {
    users: Object,
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
      deep: true,
      handler: throttle(function () {
        this.$inertia.get(this.route('users.index'), pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    }
  },
}
</script>
