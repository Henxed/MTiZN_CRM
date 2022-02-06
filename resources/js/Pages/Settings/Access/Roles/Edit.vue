<template>
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <h1 class="mb-8 font-bold text-3xl">
      <inertia-link class="text-pink-400 hover:text-pink-600" :href="route('roles.index')">Роли</inertia-link>
      <span class="text-pink-400 font-medium">/</span> {{ form.name }}
    </h1>
    <div class="bg-white rounded shadow max-w-3xl">
      <form @submit.prevent="submit">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <text-input v-model="form.name" :error="errors.name" class="pr-6 pb-1 w-full" label="Название" autocomplete="false" />

          <div class="pr-6 pb-8 w-full lg:w-1/2">
            <label for="permission" class="form-label">Права доступа</label>
            <treeselect v-model="form.permission" :options="permissions" multiple :normalizer="normalizer" placeholder="Права доступа..." id="permission" />

          </div>
        </div>
        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex justify-end items-center">
            <button class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Удалить роль</button>
            <loading-button :loading="sending" class="btn-pink ml-auto" type="submit">Сохранить</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Layout from '@/Layouts/AppLayout'
import LoadingButton from '@/Shared/LoadingButton'
import SelectInput from '@/Shared/SelectInput'
import TextInput from '@/Shared/TextInput'

import Treeselect from 'vue3-treeselect'
import 'vue3-treeselect/dist/vue3-treeselect.css'

export default {
  metaInfo: { title: 'Редактирование роли' },
  layout: Layout,
  components: {
    LoadingButton,
    SelectInput,
    TextInput,
    Treeselect
  },
  props: {
    errors: Object,
    role: Object,
    permissions: Array,
  },
  remember: 'form',
  created(){
      console.log(this.form.permission)
  },
  data() {
    return {
      sending: false,
      form: {
        name: this.role.name,
        permission: this.role.permission
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
      this.$inertia.put(this.route('roles.update', this.role.id), this.form, {
        onStart: () => this.sending = true,
        onFinish: () => this.sending = false,
      })
    },
    destroy() {
      if (confirm('Вы действительно хотите удалить роль?')) {
        this.$inertia.delete(this.route('roles.destroy', this.role.id))
      }
    },
  },
}
</script>
