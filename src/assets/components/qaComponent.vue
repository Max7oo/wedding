<template>
  <div class="qa">
    <div class="qaDiv">
      <h2>Q&A</h2>

      <div class="qaColumns">
        <div>
          <details open>
            <summary>Can I give you a gift?</summary>
            <p>
              Many of you are traveling from far to celebrate with us, and your presence is truly
              the greatest gift we could ask for. However, if you'd like to contribute to our
              wedding registry or honeymoon fund, you can do so via the link below.
            </p>
          </details>
          <details>
            <summary>Is there a dress code?</summary>
            <p>
              There's no specific dress code. That said, the ceremony will take place in a church,
              so we kindly ask for respectful attire. Late June in the South of France can be quite
              warm, and most of the reception will be outdoors, so dress comfortably for summer
              weather.
            </p>
          </details>
          <details>
            <summary>How do I get back to my accommodation after the party?</summary>
            <p>
              We're working on arranging shuttle/taxi services from the château to nearby
              accommodations. If you use this service, you'll be able to leave your car overnight
              and pick it up the next day. Please keep this in mind when booking your stay — we can
              only provide rides within a 15-minute radius, as the service will be shared among
              guests.
            </p>
          </details>
          <details>
            <summary>Can I bring a plus-one?</summary>
            <p>
              Due to venue capacity, we've included all confirmed +1s and children on your
              invitation. If you're unsure or have questions, feel free to reach out.
            </p>
          </details>
          <details>
            <summary>What is there to do in the region?</summary>
            <p>
              The area around La Motte and Le Muy offers beautiful vineyards, hiking, golf, charming
              villages, and of course — plenty of sunshine! We're happy to share a few
              recommendations if you're planning to explore.
            </p>
          </details>
        </div>
        <div class="image">
          <img src="@/assets/chateau.jpg" alt="Chateau Des Demoiselles" />
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts"></script>
<style scoped>
.qa {
  padding: 4rem;
}

.qaDiv {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.qaColumns {
  display: flex;
  gap: 1rem;
}
</style>
