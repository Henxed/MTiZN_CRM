<template>
  <button :disabled="loading" class="flex items-center justify-center">

        <div v-if="loading" class="button-spinner mr-2" />
        <slot v-if="!loading" />

  </button>
</template>

<script>
export default {
  props: {
    loading: Boolean,
  },
}
</script>
