<template>
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-20">
    <h1 class="mb-8 font-bold text-3xl">
      <Link class="text-pink-400 hover:text-pink-600" :href="route('users.index')">Users</Link>
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
            <text-input v-model="form.password" :error="errors.password" class="pr-6 pb-1 w-full lg:w-1/2" label="Password" />
        </div>
        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center">
          <loading-button :loading="sending" class="btn-load ml-auto" type="submit">Create</loading-button>
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
import { Link } from '@inertiajs/inertia-vue3'
import Treeselect from 'vue3-treeselect'
import 'vue3-treeselect/dist/vue3-treeselect.css'

export default {
  layout: Layout,
  components: {
    LoadingButton,
    SelectInput,
    Treeselect,
    TextInput,
    Link
  },
  props: {
    errors: Object,
    roles: Array,
  },
  data() {
    return {
      sending: false,
      form: this.$inertia.form({
        name: '',
        email: '',
        password: '',
        roles: null
      })
    }
  },
  methods: {
    submit() {
      this.form.post(this.route('users.store'))
    }

  },
}
</script>
