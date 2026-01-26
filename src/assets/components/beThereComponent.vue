<template>
  <div class="beThere" id="rsvp">
    <h2>I will be there</h2>
    <p>
      Please fill this form to RSVP with any dietary restrictions. A family group can fill one form
      per foyers.
    </p>
    <form class="form" action="send.php" method="POST">
      <div class="formItem">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" placeholder="Full name" required />
      </div>

      <div class="formItem">
        <label for="name">Are you coming?</label>
        <div class="radiobuttons">
          <input type="radio" id="yes" name="rsvp" value="yes" required />
          <label for="yes">Yes</label>
          <input type="radio" id="no" name="rsvp" value="no" required />
          <label for="no">No</label>
        </div>
      </div>

      <div class="formItem">
        <label for="name">How many people are you with?</label>
        <input type="text" id="amount" name="amount" placeholder="Amount" required />
      </div>

      <div class="formItem">
        <label for="name"
          >Is there any allergies we need to know? (Add name if member of your family group)</label
        >
        <input type="text" id="allergies" name="allergies" placeholder="Allergies" required />
      </div>

      <div class="formItem">
        <label for="name">Diet (Vegan/Gluten Free/Vegetarian/Other)</label>
        <input type="text" id="diet" name="diet" placeholder="Diet" required />
      </div>

      <div class="formItem">
        <label for="name">Do you know already your travel details?</label>
        <div class="radiobuttons">
          <input type="radio" id="yes" name="travel" value="yes" required />
          <label for="yes">Yes</label>
          <input type="radio" id="no" name="travel" value="no" required />
          <label for="no">No</label>
        </div>
      </div>

      <div class="formItem">
        <label for="name"
          >If yes, please provide: date of travel, mode of transportation, where do you stay?</label
        >
        <textarea id="details" name="details" placeholder="Travel details" />
      </div>

      <div class="formItem">
        <label for="name">Are you interested in the taxi?</label>
        <div class="radiobuttons">
          <input type="radio" id="yes" name="taxi" value="yes" required />
          <label for="yes">Yes</label>
          <input type="radio" id="no" name="taxi" value="no" required />
          <label for="no">No</label>
        </div>
      </div>

      <div class="formItem">
        <label for="name">Do you have any questions or remarks?</label>
        <input type="text" id="questions" name="questions" placeholder="Questions or Remarks" />
      </div>

      <div class="formItem">
        <label for="name">Do you have any song request? </label>
        <input type="text" id="song" name="song" placeholder="Village people - born to be alive" />
      </div>

      <button type="submit">I will be there (RSVP)</button>
    </form>
  </div>
</template>
<script setup lang="ts"></script>
<style scoped>
.beThere {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 2rem;
  padding: 4rem;

  background-color: white;
}
.beThere h2,
.beThere p {
  color: #a0ba9f;
}

.form {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 4rem;
}
.formItem {
  display: flex;
  align-items: center;
  margin-bottom: 1rem;
}
.formItem input,
.formItem textarea {
  font-family: 'EB Garamond', Arial, Helvetica, sans-serif;
  font-size: 1.5rem;

  border: #a0ba9f 2px solid;
}

.radiobuttons {
  display: flex;
}

@media (max-width: 768px) {
  .formItem {
    flex-direction: column;
    gap: 1rem;
  }
}

@media (max-width: 500px) {
  .beThere {
    padding: 2rem;
  }
}

@media (max-width: 379px) {
  .beThere {
    padding: 2rem 1rem;
  }
}
</style>
