<template>
<app-layout :title="`Редактирование ${form.name}`">
    <setting class="p-9">

      <form @submit.prevent="submit">
        <div class="flex flex-wrap">
          <text-input v-model="form.name" :error="errors.name" class="pr-6 pb-1 w-full" label="Название" autocomplete="false" />
          <div class="pr-6 pb-8 w-full lg:w-1/2">
            <label for="permission" class="form-label">Роли</label>
            <treeselect v-model="form.role" :options="roles" multiple :normalizer="normalizer" placeholder="Роль..." id="permission" />
          </div>
        </div>
        <div class="flex justify-end items-center">
            <button class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Удалить право доступа</button>
            <loading-button :loading="sending" class="btn-pink ml-auto" type="submit">Сохранить</loading-button>
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
