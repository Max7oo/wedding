<template>
  <div class="headerDiv">
    <h1>Max & Julie</h1>
    <p>
      {{ countdown }}
      <template v-if="countdown !== 'The big day is here!'">
        &nbsp;until the 27th of June, 2026!
      </template>
    </p>
    <button href="#" @click.prevent="scrollTo('rsvp')">I will be there (RSVP)</button>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue'

const targetDate = new Date('2026-06-27T00:00:00')
const countdown = ref('')

function updateCountdown() {
  const now = new Date()
  const diff = targetDate.getTime() - now.getTime()

  if (diff <= 0) {
    countdown.value = 'The big day is here!'
    return
  }

  const days = Math.floor(diff / (1000 * 60 * 60 * 24))
  const hours = Math.floor((diff / (1000 * 60 * 60)) % 24)
  const minutes = Math.floor((diff / (1000 * 60)) % 60)
  const seconds = Math.floor((diff / 1000) % 60)

  countdown.value = `${days} days, ${hours} hours, ${minutes} minutes, ${seconds} seconds`
}

let interval: number

onMounted(() => {
  updateCountdown()
  interval = window.setInterval(updateCountdown, 1000)
})

onBeforeUnmount(() => {
  clearInterval(interval)
})

const scrollTo = (id: string) => {
  const el = document.getElementById(id)
  if (!el) return

  const headerOffset = 100 // adjust to menu height
  const elementPosition = el.getBoundingClientRect().top
  const offsetPosition = elementPosition + window.scrollY - headerOffset

  window.scrollTo({
    top: offsetPosition,
    behavior: 'smooth',
  })
}
</script>

<style scoped>
.headerDiv {
  min-height: 100vh;
  width: 100%;
  background-image: url('@/assets/winery.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;

  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 2rem;
  padding: 2rem;
}

.headerDiv::before {
  content: '';
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
}

.headerDiv > * {
  position: relative;
  z-index: 1;
}

.headerDiv h1,
.headerDiv h2,
.headerDiv p {
  text-align: center;
}

@media (max-width: 481px) {
  .headerDiv {
    padding: 1rem;
  }
}
</style>
