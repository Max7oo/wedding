<template>
  <div class="travelInfo" id="travelInfo">
    <div class="travelInfoDiv">
      <h2>Travel info</h2>
      <div class="travelInfoColumns">
        <div>
          <b>Getting there</b>
          <p>
            For those coming from abroad, Nice Airport is the closest international airport to both
            locations. We recommend renting a car to easily reach the ceremony and reception.
          </p>
          <p>
            The church is accessible by train or car. If you're driving, please arrive early to find
            parking at one of the nearby lots:
            <a href="https://maps.app.goo.gl/J2Mta4tuuoBrsAi78" target="_blank"
              >Parking Sainte-Dévote</a
            >,
            <a href="https://maps.app.goo.gl/ZjqZE2Cp8FoYXTBBA" target="_blank">Parking Ostende</a>
            or
            <a href="https://maps.app.goo.gl/piNXBAuuQSGxVibv6" target="_blank"
              >Parking de la Condamine</a
            >.
          </p>
          <p>
            Please note the château is not well connected by public transport and is about a 1h40
            drive from the church. There is plenty of parking available at the end of the driveway.
          </p>
          <p>If you need help with transportation, please contact us.</p>
        </div>
        <div>
          <b>Accommodation</b>
          <p>
            We recommend booking accommodation near the château, as this is where the celebration
            will end. The nearby towns of La Motte and Le Muy (within a 15-minute drive) offer a
            wide range of options, from campsites to villas.
          </p>
          <p>Here are a few suggestions:</p>
          <ul>
            <li>
              <a href="https://lespinsblancs.com/" target="_blank">Les Pins Blancs en Provence</a> -
              Double rooms
            </li>
            <li>
              <a href="https://st-endreol.com/" target="_blank"
                >Domaine de Saint Endréol Golf & Spa Resort</a
              >
              - Double rooms
            </li>
            <li>
              <a href="https://www.booking.com/Share-Jxc8a8" target="_blank">Appartement Le Muy</a>
              - 2-person apartment
            </li>
            <li>
              <a href="https://www.booking.com/Share-Jy14zb" target="_blank"
                >Mobil Home Climatisé</a
              >
              - 6-person house
            </li>
          </ul>
          <p>This is a popular area in summer, so we recommend booking early.</p>
        </div>
      </div>
    </div>

    <div class="images">
      <img src="@/assets/together.jpg" alt="Max & Julie" />
      <img src="@/assets/eglise.jpg" alt="Église Sainte-Dévote" />
    </div>
  </div>
</template>
<script setup lang="ts"></script>
<style scoped>
.travelInfo {
  padding: 4rem;
  background-color: white;
}
.travelInfo h2 {
  text-align: center;
}
.travelInfoDiv {
  display: flex;
  flex-direction: column;
  gap: 2rem;

  padding: 4rem;
  background-color: #a0ba9f;
}
.travelInfoColumns {
  display: flex;
  gap: 4rem;
}
.travelInfoColumns div {
  flex: 1;
}
.travelInfoColumns p {
  margin-bottom: 1rem;
}

.images {
  display: flex;
  gap: 2rem;
  margin-top: 4rem;
  height: 700px;
}
.images img:nth-child(1) {
  width: 40%;
  object-fit: cover;
}
.images img:nth-child(2) {
  width: 60%;
  object-fit: cover;
}
</style>
