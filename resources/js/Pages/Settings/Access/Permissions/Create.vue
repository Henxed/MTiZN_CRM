<template>
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <h1 class="mb-8 font-bold text-3xl">
      <inertia-link class="text-pink-400 hover:text-pink-600" :href="route('permission.index')">Права доступа</inertia-link>
      <span class="text-pink-400 font-medium">/</span> Новая
    </h1>
    <div class="bg-white rounded shadow max-w-3xl">
      <form @submit.prevent="submit">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <text-input v-model="form.name" :error="errors.name" class="pr-6 pb-1 w-full" label="Название" autocomplete="false" />
          <div class="pr-6 pb-8 w-full lg:w-1/2">
            <label for="permission" class="form-label">Роли</label>
            <treeselect v-model="form.role" :options="roles" multiple :normalizer="normalizer" placeholder="Роли..." id="permission" />
          </div>
        </div>
        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex justify-end items-center">
          <loading-button :loading="sending" class="btn-pink" type="submit">Добавить доступ</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Layout from '@/Layouts/AppLayout'
import LoadingButton from '@/Shared/LoadingButton'
import TextInput from '@/Shared/TextInput'

import Treeselect from 'vue3-treeselect'
import 'vue3-treeselect/dist/vue3-treeselect.css'

export default {
  metaInfo: { title: 'Новая роль' },
  layout: Layout,
  components: {
    LoadingButton,
    TextInput,
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
      form: {
        name: '',
        role: []
      },
      normalizer(node) {
        return {
            label: node.name
        }
      },
    }
  },
  methods: {
    submit() {
      this.$inertia.post(this.route('permission.store'), this.form, {
        onStart: () => this.sending = true,
        onFinish: () => this.sending = false,
      })
    },
  },
}
</script>
