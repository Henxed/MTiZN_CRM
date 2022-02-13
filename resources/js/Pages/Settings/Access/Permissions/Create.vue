<template>
<app-layout title="Добавить право доступа">
    <setting class="p-9">


            <form @submit.prevent="submit">
                <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                <text-input v-model="form.name" :error="errors.name" class="pr-6 pb-1 w-full" label="Название" autocomplete="false" />
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
