<template>
  <div class="menuDiv">
    <p>Max & Julie</p>
    <div class="menuItems">
      <a v-for="item in items" :key="item.label" :href="item.route">{{ item.label }}</a>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const items = ref([
  {
    label: 'When/Where',
    route: '/',
  },
  {
    label: 'Travel info',
    route: '/projects',
  },
  {
    label: 'RSVP',
    route: '/projects',
  },
  {
    label: 'Q&A',
    route: '/projects',
  },
])
</script>

<style scoped>
.menuDiv {
  position: fixed;
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;

  padding: 2rem 6rem;

  z-index: 1;
}

.menuItems {
  display: flex;
  gap: 2rem;
}
</style>
