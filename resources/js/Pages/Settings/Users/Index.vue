<template>
<app-layout title="Пользователи">
    <setting class="px-9">
            <div class="mb-6 flex justify-between items-center">
                <h1 class="font-bold text-3xl dark:text-slate-300">Пользователи</h1>

                    <div class="flex items-center bg-gray-200 dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-full max-w-sm w-full pr-3 h-10">
                            <input class="border-none focus:outline-none focus:ring-0 flex-1 h-full w-full p-4 bg-gray-200 dark:bg-slate-700 dark:placeholder:text-slate-400 rounded-full"
                            type="text"
                            placeholder="Поиск пользователя..."
                            v-model="form.search">
                            <i class="fi fi-rr-search pt-1 mr-1"></i>
                    </div>
                    <div class="">
                        <Link class="btn-green ml-auto" :href="route('users.create')">
                            <span>Новый</span>
                            <span class="hidden md:inline"> пользователь</span>
                        </Link>
                    </div>

            </div>
            <div class="overflow-x-auto shadow rounded-xl bg-white text-slate-900 dark:bg-slate-800 w-full max-w-screen-2xl">
            <table class="w-full whitespace-no-wrap table-fixed">
                <tr class="text-left font-bold">
                <th class="px-6 py-3 border-b border-gray-200 dark:border-slate-500 bg-gray-200 dark:bg-slate-600 text-gray-500 dark:text-gray-200 text-left text-xs leading-4 font-medium  uppercase tracking-wider">ФИО</th>
                <th class="px-6 py-3 border-b border-gray-200 dark:border-slate-500 bg-gray-200 dark:bg-slate-600 text-gray-500 dark:text-gray-200 text-left text-xs leading-4 font-medium uppercase tracking-wider">E-mail</th>
                <th class="px-6 py-3 border-b border-gray-200 dark:border-slate-500 bg-gray-200 dark:bg-slate-600 text-gray-500 dark:text-gray-200  text-center text-xs leading-4 font-medium uppercase tracking-wider">Роль</th>
                </tr>
                <tr v-for="item in users.data" :key="item.id" class="hover:bg-slate-400/10 text-gray-500 dark:text-slate-400">
                    <td class="border-t dark:border-slate-500">
                        <Link class="px-5 py-4 flex items-center" :href="route('users.edit', item.id)">
                        <span class="truncate">{{ item.name }}</span>
                        </Link>
                    </td>
                    <td class="border-t dark:border-slate-500">
                        <Link class="px-4 py-4 flex items-center " :href="route('users.edit', item.id)" tabindex="-1">
                        {{ item.email }}
                        </Link>
                    </td>
                    <td class="border-t dark:border-slate-500">
                        <Link class="px-1 py-4 flex items-center" :href="route('users.edit', item.id)" tabindex="-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full m-auto bg-gray-100 dark:bg-slate-600 text-gray-800 dark:text-gray-300">{{ item.roles[0] ? item.roles[0].name : 'Без роли' }}</span>
                        </Link>
                    </td>
                </tr>
                <tr v-if="users.data.length === 0">
                <td class="border-t px-6 py-4" colspan="4">Ничего нет.</td>
                </tr>
            </table>

            <pagination :links="users.links" class="px-6" />
            </div>
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
