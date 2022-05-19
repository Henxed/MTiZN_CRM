<template>
<app-layout :title="`Редактирование ${this.permission.title}`">
    <setting class="px-9">

    <div class="px-4 py-5 sm:px-6">
            <h3 class="text-2xl leading-6 font-medium text-gray-900 dark:text-slate-300 ">{{ this.permission.title }}</h3>
        </div>
      <form @submit.prevent="submit" class="p-4 dark:border-slate-500 shadow rounded-xl bg-white text-slate-900 dark:bg-slate-800 w-full max-w-screen-2xl">
        <div class="flex flex-wrap">
            <text-input v-model="form.title" :error="errors.title" class="pr-6 pb-1 w-full" label="Наименование" autocomplete="false" />
            <text-input v-model="form.name" :error="errors.name" class="pr-6 pb-1 w-full" label="Название доступа" autocomplete="false" />
            <div class="pr-6 pb-8 w-full lg:w-1/2">
                <label for="permission" class="form-label">Роли</label>
                <treeselect v-model="form.role" :options="roles" multiple :normalizer="normalizer" placeholder="Роль..." id="permission" />
            </div>
        </div>
        <div class="flex justify-end items-center">
            <button class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Удалить право доступа</button>
            <loading-button :loading="sending" class="btn-green ml-auto" type="submit">Сохранить</loading-button>
        </div>
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
    Treeselect,
    AppLayout,
    Setting
  },
  props: {
    errors: Object,
    permission: Object,
    roles: Array,
  },
  remember: 'form',
  data() {
    return {
      sending: false,
      form: {
        name: this.permission.name,
        title: this.permission.title,
        role: this.permission.role
      },
      normalizer(node) {
        return {
            id: node.id,
            label: node.name
        }
      },
    }
  },
  methods: {
    submit() {
      this.$inertia.put(this.route('permissions.update', this.permission.id), this.form, {
        onStart: () => this.sending = true,
        onFinish: () => this.sending = false,
      })
    },
    destroy() {
      if (confirm('Вы действительно хотите удалить право доступа?')) {
        this.$inertia.delete(this.route('permission.destroy', this.permission.id))
      }
    },
  },
}
</script>
