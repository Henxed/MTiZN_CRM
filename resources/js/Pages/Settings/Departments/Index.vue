<template>
<app-layout title="Список отделов">
    <setting class="px-9">
            <div class="mb-6 flex justify-between">
                <h1 class="font-bold text-3xl dark:text-slate-300">Отделы</h1>
                <div class="">
                    <Link class="btn-green ml-auto" :href="route('departments.create')">
                        <span>Новый</span>
                        <span class="hidden md:inline"> отдел</span>
                    </Link>
                </div>
            </div>
            <div class="overflow-x-auto shadow rounded-xl bg-white text-slate-900 dark:bg-slate-800 w-full max-w-screen-2xl">
            <table class="w-full whitespace-no-wrap table-fixed">
                <tr class="text-left font-bold">
                    <th class="px-6 py-3 border-b border-gray-200 dark:border-slate-500 bg-gray-200 dark:bg-slate-600 text-gray-500 dark:text-gray-200 text-left text-xs leading-4 font-medium  uppercase tracking-wider">Название</th>
                    <th class="px-6 py-3 border-b border-gray-200 dark:border-slate-500 bg-gray-200 dark:bg-slate-600 text-gray-500 dark:text-gray-200 text-left text-xs leading-4 font-medium uppercase tracking-wider">Описание</th>
                    <th class="px-6 py-3 border-b border-gray-200 dark:border-slate-500 bg-gray-200 dark:bg-slate-600 text-gray-500 dark:text-gray-200  text-center text-xs leading-4 font-medium uppercase tracking-wider">Руководитель</th>
                </tr>
                <tr v-for="item in departments.data" :key="item.id" class="hover:bg-slate-400/10 text-gray-500 dark:text-slate-400">
                    <td class="border-t dark:border-slate-500">
                        <Link class="px-5 py-4 flex items-center" :href="route('departments.edit', item.id)">
                        <span class="truncate">{{ item.name }}</span>
                        </Link>
                    </td>
                    <td class="border-t dark:border-slate-500">
                        <Link class="px-4 py-4 flex items-center " :href="route('departments.edit', item.id)" tabindex="-1">
                        {{ item.description }}
                        </Link>
                    </td>
                    <td class="border-t dark:border-slate-500">
                        <Link class="px-1 py-4 flex items-center" :href="route('departments.edit', item.id)" tabindex="-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full m-auto bg-gray-100 dark:bg-slate-600 text-gray-800 dark:text-gray-300">{{ item.owner ? item.owner.name : 'Без руководителя' }}</span>
                        </Link>
                    </td>
                </tr>
                <tr v-if="departments.data.length === 0">
                <td class="border-t px-6 py-4" colspan="3">Ничего нет.</td>
                </tr>
            </table>

            <pagination :links="departments.links" class="px-6" />
            </div>
    </setting>
</app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Setting from '@/Pages/Settings/Layout'
import { Link } from '@inertiajs/inertia-vue3'
import Pagination from '@/Shared/Pagination'

export default {
  components: {
    Pagination,
    Link,
    Setting,
    AppLayout
  },
  props: {
    departments: Object,
    filters: Object,
  }
}
</script>
