<template>
    <app-layout title="Редактировать пользователя">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-20">
        <h1 class="mb-8 font-bold text-3xl">
        <Link class="text-pink-400 hover:text-pink-600" :href="route('users.index')">Users</Link>
        <span class="text-pink-400 font-medium">/</span> <span class="dark:text-gray-300">{{ form.name }}</span>
        </h1>
        <div class="bg-white rounded shadow max-w-3xl">
        <form @submit.prevent="submit">
            <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                <text-input v-model="form.name" :error="errors.name" class="pr-6 pb-1 w-full lg:w-1/2" label="Name" />
                <text-input v-model="form.email" :error="errors.email" class="pr-6 pb-1 w-full lg:w-1/2" label="E-mail" type="email" />
                <div class="pr-6 pb-1 w-full lg:w-1/2">
                    <label for="role" class="form-label">Роль</label>
                    <treeselect v-model="form.roles" :options="roles" :normalizer="normalizer" placeholder="Роли..." id="role" />
                </div>
                <!-- <div class="pr-6 pb-1 w-full lg:w-1/2">
                    <label for="permissions" class="form-label">Права доступа</label>
                    <treeselect v-model="form.permissions" :options="permissions" multiple :normalizer="normalizer" placeholder="Права..." id="permissions" />
                </div> -->
                <text-input v-model="form.password" :error="errors.password" class="pr-6 pb-1 w-full lg:w-1/2" label="Password" />
            </div>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center">
            <button class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete the user</button>
            <loading-button :loading="form.processing" class="btn-green ml-auto" type="submit">Save</loading-button>
            </div>
        </form>
        </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import LoadingButton from '@/Shared/LoadingButton'
import SelectInput from '@/Shared/SelectInput'
import TextInput from '@/Shared/TextInput'
import { Link } from '@inertiajs/inertia-vue3'
import Treeselect from 'vue3-treeselect'
import 'vue3-treeselect/dist/vue3-treeselect.css'

export default {
  components: {
    LoadingButton,
    SelectInput,
    TextInput,
    Link,
    Treeselect,
    AppLayout,
  },
  props: {
    errors: Object,
    users: Object,
    roles: Array,
    //permissions: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        _method: 'put',
        name: this.users.name,
        email: this.users.email,
        password: this.users.password,
        roles: this.users.roles.length ? this.users.roles : null,
       // permissions: this.users.permissions.length ? this.users.permissions : null,
      }),
      normalizer(node) {
        return {
            id: node.id,
            label: node.name,
        }
      },
    }
  },
  methods: {
    submit() {
      this.form.post(this.route('users.update', this.users.id), {
        onSuccess: () => this.form.reset('password'),
      })
    },
    destroy() {
      if (confirm('Do you really want to delete the user?')) {
        this.$inertia.delete(this.route('users.destroy', this.users.id))
      }
    },

  },
}
</script>
