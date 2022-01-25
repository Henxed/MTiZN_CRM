<template>
    <button
     @click.prevent="toggleTheme" :content="'Уведомления'" v-tippy='{ placement : "bottom" }' class="fix-svg-btn">
        <!-- <svg class="outline-none" :fill="!theme ? 'none' : 'currentColor'"  stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" width="40" height="40" viewBox="0 0 24 24">
          <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
        </svg> -->
        <svg class="outline-none" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
        </svg>
    </button>
</template>

<script>
export default {
  data() {
      return{
          
      }
  },
  methods: {
    toggleTheme() {

    },
  }
}
</script>
