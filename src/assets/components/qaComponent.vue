<template>
  <div class="qa" id="qa">
    <h2>Q&A</h2>
    <div class="qaDiv">
      <div class="questions">
        <details open>
          <summary>Is there a dress code?</summary>
          <p>
            The dress code is Summer Chic. As the ceremony will take place in a church, we kindly
            ask guests to dress respectfully (for example, with shoulders covered). Late June in the
            South of France can be quite warm, and the reception will take place outdoors, so guests
            are encouraged to choose attire that is both stylish and comfortable for summer weather.
          </p>
        </details>
        <details>
          <summary>Can I bring a plus-one?</summary>
          <p>
            Due to venue capacity, we've included all confirmed +1s and children on your invitation.
            Guests with any questions or uncertainties are encouraged to reach out to us.
          </p>
        </details>
        <details>
          <summary>What time should I be where?</summary>
          <p style="margin-bottom: 1rem">
            As mentioned, guests are kindly asked to enter the church no later than 13:45 to ensure
            a timely start to the ceremony. Please allow extra time for traffic and parking, as
            these can be challenging in Monaco.
          </p>
          <p>
            Following the ceremony, there will be a break to allow for travel time. The reception at
            the Château will begin at 18:00. Parking will be available for all guests at the
            Château. Drinks and appetizers will be served upon arrival.
          </p>
        </details>
        <details>
          <summary>Can I get a taxi to my accommodation after the celebration?</summary>
          <p style="margin-bottom: 1rem">
            To ensure all guests return safely after the celebration, we kindly advise against
            driving if planning to drink. A shared taxi service will be arranged to transport guests
            back to their accommodations. Guests wishing to use this service are invited to indicate
            so on their RSVP.
          </p>
          <p>
            When arranging accommodations, please note that the taxi service is available within a
            15-minute radius, as it will be shared among guests. Those using the service are welcome
            to leave their cars overnight and collect them the following day.
          </p>
        </details>
        <details>
          <summary>Can I give you a gift?</summary>
          <p>
            We are deeply grateful that so many guests are traveling from near and far to celebrate
            with us, your presence is the greatest gift we could ask for. For those who wish to
            contribute to our wedding registry or honeymoon fund, offerings may be placed in the
            designated box at the reception, made online via
            <a href="https://paypal.me/maxderuiter7" target="_blank">PayPal</a> or by direct bank
            transfer (contact us for bank details).
          </p>
        </details>

        <details>
          <summary>Would you like to help with entertainment?</summary>
          <p>
            If guests would like to contribute to the entertainment during the reception (such as
            speeches or games), we would be delighted. Please reach out to our best man/maid of
            honor team to coordinate: Niels V, Tim vd D, Tim vd V, Julie Q, Marie P or Vincent S.
          </p>
        </details>
        <details>
          <summary>What is there to do in the region?</summary>
          <p>
            The region surrounding La Motte and Le Muy is filled with scenic vineyards, delightful
            hiking trails, golf courses, charming villages, and, of course, abundant sunshine.
            Guests planning to explore are encouraged invited to reach out for a few of our favorite
            recommendations.
          </p>
        </details>
      </div>
      <div class="image">
        <img src="@/assets/chateau.jpg" alt="Chateau Des Demoiselles" />
      </div>
    </div>
  </div>
</template>
<script setup lang="ts"></script>
<style scoped>
.qa {
  padding: 4rem;
}

.qa h2 {
  margin-bottom: 1rem;
}

.qaDiv {
  display: flex;
  gap: 4rem;
  padding-bottom: 4rem;
}

.questions {
  display: flex;
  justify-content: center;
  flex-direction: column;
  gap: 1rem;
  max-width: 60%;
  min-width: 60%;
}

.image {
  margin: auto 0;
}
.image img {
  width: 100%;
}

.bw-filter {
  filter: grayscale(100%);
}

@media (max-width: 1100px) {
  .qaDiv {
    flex-direction: column;
    align-items: center;
  }
  .questions {
    max-width: 100%;
    min-width: 100%;
  }
}

@media (max-width: 500px) {
  .qa {
    padding: 2rem 2rem 4rem 2rem;
  }
}

@media (max-width: 379px) {
  .qa {
    padding: 2rem 1rem;
  }
  .qaDiv {
    gap: 2rem;
    padding-bottom: 0;
  }
}
</style>
