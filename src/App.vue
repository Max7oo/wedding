<template>
  <Menu />
  <Header />
  <div class="container">
    <WhenwhereComponent />
    <TravelInfoComponent />
    <BeThereComponent />
    <QaComponent />
  </div>
  <FooterComponent />
</template>

<script setup lang="ts">
import Menu from './assets/components/menuComponent.vue'
import Header from './assets/components/headerComponent.vue'
import WhenwhereComponent from './assets/components/whenwhereComponent.vue'
import TravelInfoComponent from './assets/components/travelInfoComponent.vue'
import BeThereComponent from './assets/components/beThereComponent.vue'
import QaComponent from './assets/components/qaComponent.vue'
import FooterComponent from './assets/components/footerComponent.vue'
</script>

<style scoped>
.container {
  max-width: 2000px;
  margin: 0 auto;
}
</style>
