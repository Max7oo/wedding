<template>
  <div class="whenwhereDiv" id="whenwhere">
    <div class="whenwhereItem">
      <h2>Ceremony</h2>
      <div class="whenwhereItemInfo">
        <a href="https://share.google/HC05IGWSFpACvxZ7X" target="_blank">Église Sainte-Dévote</a>
        <p>1 Rle Sainte-Dévote, 98000 Principality of Monaco</p>
        <p>Starts at 13:45</p>
      </div>
    </div>

    <div class="whenwhereItem">
      <h2>Reception</h2>
      <div class="whenwhereItemInfo">
        <a href="https://share.google/hDrG93R0BviF69Rl7" target="_blank">Château Des Demoiselles</a>
        <p>2040 Rte de Callas 83920 La Motte, France</p>
        <p>From 17:00</p>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts"></script>
<style scoped>
.whenwhereDiv {
  display: flex;
  justify-content: space-between;
  width: 60%;
  gap: 4rem;
  margin: 4rem auto;
}

.whenwhereItem {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.whenwhereItemInfo {
  display: flex;
  flex-direction: column;
  text-align: center;
  gap: 1rem;
  max-width: 300px;
}

@media (max-width: 1320px) {
  .whenwhereDiv {
    width: 80%;
  }
}

@media (max-width: 1100px) {
  .whenwhereDiv {
    flex-direction: column;
    align-items: center;
  }
}

@media (max-width: 379px) {
  .whenwhereDiv {
    margin: 2rem auto;
  }
  .whenwhereItem {
    gap: 1rem;
  }
}
</style>
