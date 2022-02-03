<template>
    <button
     @click.prevent="toggleTheme" :content="!theme ? 'Темная тема' : 'Светлая тема'" v-tippy='{ placement : "bottom" }' class="fix-svg-btn">
        <svg class="outline-none" :fill="!theme ? 'none' : 'currentColor'"  stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" width="24" height="24" viewBox="0 0 24 24">
          <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
        </svg>
    </button>
</template>

<script>
export default {
  data() {
      return{
          theme: localStorage.theme === 'dark' ? true : false
      }
  },
  methods: {
    toggleTheme() {
      this.$store.dispatch("toggleTheme");
      if(this.theme){
          this.theme = false
      }else{
          this.theme = true
      }
    },
  }
}
</script>
