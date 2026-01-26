<template>
  <div class="menuWrapper">
    <div class="menuDiv">
      <p class="logo">Max & Julie</p>

      <!-- Hamburger -->
      <button class="burger" @click="isOpen = !isOpen">
        <span :class="{ open: isOpen }"></span>
        <span :class="{ open: isOpen }"></span>
        <span :class="{ open: isOpen }"></span>
      </button>

      <!-- Menu items -->
      <div class="menuItems" :class="{ open: isOpen }">
        <a v-for="item in items" :key="item.label" href="#" @click.prevent="scrollTo(item.route)">
          {{ item.label }}
        </a>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const isOpen = ref(false)

const items = ref([
  { label: 'When/Where', route: 'whenwhere' },
  { label: 'Travel info', route: 'travelInfo' },
  { label: 'RSVP', route: 'rsvp' },
  { label: 'Q&A', route: 'qa' },
])

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

  isOpen.value = false // close mobile menu
}
</script>

<style scoped>
.menuWrapper {
  position: fixed;
  top: 0;
  width: 100%;
  background: #565c3e;
  z-index: 10;
}

/* Centered content */
.menuDiv {
  max-width: 2000px;
  margin: 0 auto;
  padding: 1rem 2rem;

  display: flex;
  justify-content: space-between;
  align-items: center;
}

/* Desktop menu */
.menuItems {
  display: flex;
  gap: 2rem;
}

/* Burger hidden on desktop */
.burger {
  display: none;
  background: none;
  border: none;
  cursor: pointer;
}
button.burger {
  padding: 0 0;
}

/* Burger lines */
.burger span {
  display: block;
  width: 25px;
  height: 2px;
  background: white;
  margin: 5px 0;
  transition: 0.3s;
}

/* Mobile */
@media (max-width: 768px) {
  .burger {
    display: block;
  }

  .menuItems {
    position: absolute;
    top: 100%;
    right: 0;
    background: #565c3e;
    flex-direction: column;
    gap: 1.5rem;
    padding: 2rem;
    transform: translateY(-20px);
    opacity: 0;
    pointer-events: none;
    transition: 0.3s;
  }

  .menuItems.open {
    transform: translateY(0);
    opacity: 1;
    pointer-events: auto;
  }
}
</style>
