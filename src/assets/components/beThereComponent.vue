<template>
  <div class="beThere" id="rsvp">
    <h2>I will be there</h2>
    <p>
      Please fill this form to RSVP with any dietary restrictions. A family group can fill one form
      per foyers.
    </p>

    <b v-if="successMessage" class="alert alert-success">
      {{ succesMessage }}
    </b>

    <b v-if="errorMessage" class="alert alert-error">
      {{ errorMessage }}
    </b>
    <form class="form" @submit.prevent="submitForm">
      <div class="formItem">
        <label for="name">Name</label>
        <input
          type="text"
          id="name"
          name="name"
          placeholder="Full name"
          v-model="form.name"
          required
        />
        <p v-if="errors.name" class="error-message">{{ errors.name }}</p>
      </div>

      <div class="formItem">
        <label for="name">Are you coming?</label>
        <div class="radiobuttons">
          <input type="radio" id="yes" name="rsvp" value="yes" required v-model="form.rsvp" />
          <label for="yes">Yes</label>
          <input type="radio" id="no" name="rsvp" value="no" required v-model="form.rsvp" />
          <label for="no">No</label>
        </div>
        <div v-if="errors.rsvp" class="error-message">{{ errors.rsvp }}</div>
      </div>

      <div class="formItem">
        <label for="name">How many people are you with?</label>
        <input
          type="text"
          id="amount"
          name="amount"
          placeholder="Amount"
          v-model="form.amount"
          required
        />
        <p v-if="errors.amount" class="error-message">{{ errors.amount }}</p>
      </div>

      <div class="formItem">
        <label for="name"
          >Is there any allergies we need to know? (Add name if member of your family group)</label
        >
        <input
          type="text"
          id="allergies"
          name="allergies"
          placeholder="Allergies"
          v-model="form.allergies"
          required
        />
      </div>

      <div class="formItem">
        <label for="name">Diet (Vegan/Gluten Free/Vegetarian/Other)</label>
        <input type="text" id="diet" name="diet" placeholder="Diet" v-model="form.diet" required />
        <p v-if="errors.diet" class="error-message">{{ errors.diet }}</p>
      </div>

      <div class="formItem">
        <label for="name">Do you know already your travel details?</label>
        <div class="radiobuttons">
          <input type="radio" id="yes" name="travel" value="yes" required v-model="form.travel" />
          <label for="yes">Yes</label>
          <input type="radio" id="no" name="travel" value="no" required v-model="form.travel" />
          <label for="no">No</label>
        </div>
        <p v-if="errors.travel" class="error-message">{{ errors.travel }}</p>
      </div>

      <div class="formItem">
        <label for="name"
          >If yes, please provide: date of travel, mode of transportation, where do you stay?</label
        >
        <textarea id="details" name="details" placeholder="Travel details" v-model="form.details" />
        <p v-if="errors.details" class="error-message">{{ errors.details }}</p>
      </div>

      <div class="formItem">
        <label for="name">Are you interested in the taxi?</label>
        <div class="radiobuttons">
          <input type="radio" id="yes" name="taxi" value="yes" required v-model="form.taxi" />
          <label for="yes">Yes</label>
          <input type="radio" id="no" name="taxi" value="no" required v-model="form.taxi" />
          <label for="no">No</label>
        </div>
        <p v-if="errors.taxi" class="error-message">{{ errors.taxi }}</p>
      </div>

      <div class="formItem">
        <label for="name">Do you have any questions or remarks?</label>
        <input
          type="text"
          id="questions"
          name="questions"
          placeholder="Questions or Remarks"
          v-model="form.questions"
        />
        <p v-if="errors.questions" class="error-message">{{ errors.questions }}</p>
      </div>

      <div class="formItem">
        <label for="name">Do you have any song request? </label>
        <input
          type="text"
          id="song"
          name="song"
          placeholder="Village people - born to be alive"
          v-model="form.song"
        />
        <p v-if="errors.song" class="error-message">{{ errors.song }}</p>
      </div>

      <button type="submit" :disabled="loading">
        {{ loading ? 'Sending...' : 'I will be there (RSVP)' }}
      </button>
    </form>
  </div>
</template>
<script setup lang="ts">
import { ref, reactive } from 'vue'

const form = reactive({
  name: '',
  rsvp: '',
  amount: '',
  allergies: '',
  diet: '',
  travel: '',
  details: '',
  taxi: '',
  questions: '',
  song: '',
})

const errors = reactive({
  name: '',
  rsvp: '',
  amount: '',
  allergies: '',
  diet: '',
  travel: '',
  details: '',
  taxi: '',
  questions: '',
  song: '',
})
const loading = ref(false)
const successMessage = ref('')
const errorMessage = ref('')
const succesMessage = ref('')

function validateForm() {
  if (!form.name.trim()) {
    errors.name = 'Name is required'
  }

  if (!form.rsvp.trim()) {
    errors.rsvp = 'RSVP is required'
  }

  if (!form.amount.trim()) {
    errors.amount = 'Amount is required'
  }

  if (!form.allergies.trim()) {
    errors.allergies = 'Allergies are required'
  }

  if (!form.diet.trim()) {
    errors.diet = 'Diet is required'
  }

  if (!form.travel.trim()) {
    errors.travel = 'Travel details are required'
  }

  if (!form.taxi.trim()) {
    errors.taxi = 'Taxi information is required'
  }

  return Object.keys(errors).length === 0
}

async function submitForm() {
  successMessage.value = ''
  errorMessage.value = ''

  console.log('Submitting form:', form)
  // if (!validateForm()) {
  //   return
  // }

  loading.value = true

  try {
    const response = await fetch('/contact.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(form),
    })

    const data = await response.json()

    if (data.success) {
      successMessage.value = data.message
      resetForm()
    } else {
      errorMessage.value = data.message || 'Something went wrong. Please try again.'
    }
  } catch (error) {
    errorMessage.value = 'Network error. Please check your connection and try again.'
  } finally {
    loading.value = false
  }
}

function resetForm() {
  form.name = ''
  form.rsvp = ''
  form.amount = ''
  form.allergies = ''
  form.diet = ''
  form.travel = ''
  form.details = ''
  form.taxi = ''
  form.questions = ''
  form.song = ''
}
</script>
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
