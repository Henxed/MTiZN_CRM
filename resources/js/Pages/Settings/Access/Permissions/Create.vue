<template>
<app-layout title="Добавить право доступа">
    <setting class="px-9">

    <div class="px-4 py-5 sm:px-6">
            <h3 class="text-2xl leading-6 font-medium text-gray-900 dark:text-slate-300 ">Добавить право доступа</h3>
        </div>
      <form @submit.prevent="submit" class="p-4 dark:border-slate-500 shadow rounded-xl bg-white text-slate-900 dark:bg-slate-800 w-full max-w-screen-2xl">
        <div class="flex flex-wrap">
            <text-input v-model="form.title" :error="errors.title" class="pr-6 pb-1 w-full" label="Наименование" autocomplete="false" />
            <text-input v-model="form.name" :error="errors.name" class="pr-6 pb-1 w-full" label="Название доступа" autocomplete="false" />
                <div class="pr-6 pb-8 w-full lg:w-1/2">
                    <label for="permission" class="form-label">Роли</label>
                    <treeselect v-model="form.role" :options="roles" multiple :normalizer="normalizer" placeholder="Роли..." id="permission" />
                </div>
                </div>

                <loading-button :loading="sending" class="btn-green" type="submit">Добавить доступ</loading-button>

            </form>


    </setting>
</app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Setting from '@/Pages/Settings/Layout'
import LoadingButton from '@/Shared/LoadingButton'
import TextInput from '@/Shared/TextInput'

import Treeselect from '@bosquig/vue3-treeselect'


export default {

  components: {
    LoadingButton,
    TextInput,
    AppLayout,
    Setting,
    Treeselect
  },
  props: {
    errors: Object,
    roles: Array,
  },
  remember: 'form',
  data() {
    return {
      sending: false,
      form: this.$inertia.form({
            name: '',
            title: '',
            role: [],
        }),
      normalizer(node) {
        return {
            label: node.name
        }
      },
    }
  },
  methods: {
    submit() {
      this.form.post(this.route('permissions.store'))
    },
  },
}
</script>
