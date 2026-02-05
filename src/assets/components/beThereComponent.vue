<template>
  <div class="beThere" id="rsvp">
    <h2>I will be there</h2>
    <p>
      Guests are kindly asked to complete this form to RSVP and indicate any dietary restrictions. A
      single form may be submitted per household.
    </p>

    <b v-if="successMessage" class="success-message">
      {{ successMessage }}
    </b>

    <b v-if="errorMessage" class="error-message">
      {{ errorMessage }}
    </b>

    <form class="form" @submit.prevent="submitForm" v-if="!successMessage">
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
        <div class="formItemRadio">
          <div class="radiobuttons">
            <input type="radio" id="yes" name="rsvp" value="yes" required v-model="form.rsvp" />
            <label for="yes">Yes</label>
            <input type="radio" id="no" name="rsvp" value="no" required v-model="form.rsvp" />
            <label for="no">No</label>
          </div>
        </div>
        <div v-if="errors.rsvp" class="error-message">{{ errors.rsvp }}</div>
      </div>

      <div class="formItem" v-if="isComing">
        <label for="name">How many people in total (including you)?</label>
        <input
          type="number"
          id="amount"
          name="amount"
          placeholder="Total number of people"
          v-model="form.amount"
          min="1"
          required
        />
        <p v-if="errors.amount" class="error-message">{{ errors.amount }}</p>
      </div>

      <!-- per-person name and allergy rows (first row is submitter) -->
      <div class="formItem align-top" v-if="isComing && amountSelected && amountNumber > 0">
        <label>Names of people, please specify allergies and diets</label>
        <div class="names-list">
          <div v-for="(_, index) in guestSlots" :key="index" class="name-item">
            <input
              v-if="index === 0"
              type="text"
              :id="`guest-${index}`"
              :name="`guest-${index}`"
              placeholder="Your name"
              v-model="form.name"
              required
            />
            <input
              v-else
              type="text"
              :id="`guest-${index}`"
              :name="`guest-${index}`"
              :placeholder="`Guest ${index + 1} name`"
              v-model="form.names[index]"
              required
            />

            <div class="guest-allergy-checkboxes">
              <label
                ><input
                  type="checkbox"
                  :checked="inArray(form.guestAllergies[index]?.types, 'none')"
                  @change="
                    (e: Event) =>
                      toggleAllergy(index, 'none', (e.target as HTMLInputElement).checked)
                  "
                />
                No allergies</label
              >
              <label
                ><input
                  type="checkbox"
                  :checked="inArray(form.guestAllergies[index]?.types, 'gluten-free')"
                  @change="
                    (e: Event) =>
                      toggleAllergy(index, 'gluten-free', (e.target as HTMLInputElement).checked)
                  "
                />
                Gluten-free</label
              >
              <label
                ><input
                  type="checkbox"
                  :checked="inArray(form.guestAllergies[index]?.types, 'lactose-intolerant')"
                  @change="
                    (e: Event) =>
                      toggleAllergy(
                        index,
                        'lactose-intolerant',
                        (e.target as HTMLInputElement).checked,
                      )
                  "
                />
                Lactose intolerant</label
              >
              <label
                ><input
                  type="checkbox"
                  :checked="inArray(form.guestAllergies[index]?.types, 'nut-allergy')"
                  @change="
                    (e: Event) =>
                      toggleAllergy(index, 'nut-allergy', (e.target as HTMLInputElement).checked)
                  "
                />
                Nut allergy</label
              >
              <label
                ><input
                  type="checkbox"
                  :checked="inArray(form.guestAllergies[index]?.types, 'seafood-allergy')"
                  @change="
                    (e: Event) =>
                      toggleAllergy(
                        index,
                        'seafood-allergy',
                        (e.target as HTMLInputElement).checked,
                      )
                  "
                />
                Seafood allergy</label
              >
              <label
                ><input
                  type="checkbox"
                  :checked="inArray(form.guestAllergies[index]?.types, 'other')"
                  @change="
                    (e: Event) =>
                      toggleAllergy(index, 'other', (e.target as HTMLInputElement).checked)
                  "
                />
                Other</label
              >
            </div>

            <input
              v-if="
                form.guestAllergies[index] &&
                form.guestAllergies[index].types &&
                form.guestAllergies[index].types.includes('other')
              "
              type="text"
              :id="`guest-allergy-other-${index}`"
              :name="`guest-allergy-other-${index}`"
              placeholder="Specify allergy"
              :value="form.guestAllergies[index] ? form.guestAllergies[index].other : ''"
              @input="
                (e: Event) => {
                  const val = (e.target as HTMLInputElement).value
                  if (!form.guestAllergies[index]) {
                    form.guestAllergies[index] = { types: ['other'], other: val }
                  } else {
                    form.guestAllergies[index].other = val
                  }
                }
              "
            />

            <!-- per-person diet select -->
            <select
              :id="`guest-diet-${index}`"
              :name="`guest-diet-${index}`"
              :value="form.guestDiets[index] ? form.guestDiets[index].type : ''"
              @change="
                (e: Event) => {
                  const val = (e.target as HTMLSelectElement).value
                  if (!form.guestDiets[index]) {
                    form.guestDiets[index] = { type: val, other: '' }
                  } else {
                    form.guestDiets[index].type = val
                  }
                }
              "
            >
              <option value="">No preference</option>
              <option value="vegan">Vegan</option>
              <option value="vegetarian">Vegetarian</option>
              <option value="other">Other</option>
            </select>

            <input
              v-if="form.guestDiets[index] && form.guestDiets[index].type === 'other'"
              type="text"
              :id="`guest-diet-other-${index}`"
              :name="`guest-diet-other-${index}`"
              placeholder="Specify diet"
              :value="form.guestDiets[index] ? form.guestDiets[index].other : ''"
              @input="
                (e: Event) => {
                  const val = (e.target as HTMLInputElement).value
                  if (!form.guestDiets[index]) {
                    form.guestDiets[index] = { type: 'other', other: val }
                  } else {
                    form.guestDiets[index].other = val
                  }
                }
              "
            />
          </div>
        </div>
        <p v-if="errors.names" class="error-message">{{ errors.names }}</p>
        <p v-if="errors.guestAllergies" class="error-message">{{ errors.guestAllergies }}</p>
      </div>

      <div class="formItem" v-if="isComing">
        <label for="name"
          >Do you already know your travel details? (only if you need to travel far)</label
        >
        <div class="formItemRadio">
          <div class="radiobuttons">
            <input type="radio" id="yes" name="travel" value="yes" required v-model="form.travel" />
            <label for="yes">Yes</label>
            <input type="radio" id="no" name="travel" value="no" required v-model="form.travel" />
            <label for="no">No</label>
          </div>
        </div>
        <p v-if="errors.travel" class="error-message">{{ errors.travel }}</p>
      </div>

      <div class="formItem" v-if="isComing && form.travel === 'yes'">
        <label for="arrivalDate">Arrival date</label>
        <input type="date" id="arrivalDate" name="arrivalDate" v-model="form.arrivalDate" />
        <p v-if="errors.arrivalDate" class="error-message">{{ errors.arrivalDate }}</p>
      </div>

      <div class="formItem" v-if="isComing && form.travel === 'yes'">
        <label for="leavingDate">Leaving date</label>
        <input type="date" id="leavingDate" name="leavingDate" v-model="form.leavingDate" />
        <p v-if="errors.leavingDate" class="error-message">{{ errors.leavingDate }}</p>
      </div>

      <div class="formItem" v-if="isComing && form.travel === 'yes'">
        <label>Will you have/use a car?</label>
        <div class="formItemRadio">
          <div class="radiobuttons">
            <input type="radio" id="car-yes" name="car" value="yes" v-model="form.car" />
            <label for="car-yes">Yes</label>
            <input type="radio" id="car-no" name="car" value="no" v-model="form.car" />
            <label for="car-no">No</label>
          </div>
        </div>
        <p v-if="errors.car" class="error-message">{{ errors.car }}</p>
      </div>

      <div class="formItem" v-if="isComing && form.travel === 'yes'">
        <label for="lodging">Hotel/Airbnb name or adres</label>
        <input
          type="text"
          id="lodging"
          name="lodging"
          placeholder="Hotel/Airbnb address"
          v-model="form.lodging"
        />
        <p v-if="errors.lodging" class="error-message">{{ errors.lodging }}</p>
      </div>

      <div class="formItem" v-if="isComing">
        <label for="name">Would you like to make use of the taxi service?</label>
        <div class="formItemRadio">
          <div class="radiobuttons">
            <input type="radio" id="yes" name="taxi" value="yes" required v-model="form.taxi" />
            <label for="yes">Yes</label>
            <input type="radio" id="no" name="taxi" value="no" required v-model="form.taxi" />
            <label for="no">No</label>
          </div>
        </div>
        <p v-if="errors.taxi" class="error-message">{{ errors.taxi }}</p>
      </div>

      <div class="formItem">
        <label for="name">Do you have any questions or remarks?</label>
        <textarea
          id="questions"
          name="questions"
          placeholder="Questions or remarks"
          v-model="form.questions"
        />
        <p v-if="errors.questions" class="error-message">{{ errors.questions }}</p>
      </div>

      <div class="formItem" v-if="isComing">
        <label for="name">Last one, do you have any song request? </label>
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
        {{ loading ? 'Sending...' : 'Confirm your presence (RSVP)' }}
      </button>
    </form>
  </div>
</template>
<script setup lang="ts">
import { ref, reactive, watch, computed, onUnmounted } from 'vue'

type GuestAllergy = { types: string[]; other: string }
type GuestDiet = { type: string; other: string }

const form = reactive({
  name: '',
  rsvp: '',
  amount: '',
  names: [] as string[],
  guestAllergies: [] as GuestAllergy[], // array of { type, other } objects
  guestDiets: [] as GuestDiet[],
  travel: '',
  arrivalDate: '',
  leavingDate: '',
  car: '',
  lodging: '',
  details: '',
  taxi: '',
  questions: '',
  song: '',
})

const errors = reactive({
  name: '',
  rsvp: '',
  amount: '',
  names: '',
  guestAllergies: '',
  guestDiets: '',
  travel: '',
  arrivalDate: '',
  leavingDate: '',
  car: '',
  lodging: '',
  details: '',
  taxi: '',
  questions: '',
  song: '',
})
const loading = ref(false)
const successMessage = ref('')
const errorMessage = ref('')
// auto-hide timer id for success message
const autoHideTimer = ref<number | null>(null)

const amountNumber = computed(() => {
  const n = parseInt(String(form.amount), 10)
  return isNaN(n) ? 0 : n
})

const amountSelected = computed(() => String(form.amount).trim() !== '')
const totalGuests = computed(() => Math.max(amountNumber.value, 0))
const guestSlots = computed(() => Array.from({ length: totalGuests.value }))
const isComing = computed(() => form.rsvp !== 'no')

// helper to check inclusion
function inArray(arr: string[] | undefined, val: string) {
  return Array.isArray(arr) && arr.includes(val)
}

// toggle a checkbox selection for guest allergies
function toggleAllergy(index: number, value: string, checked: boolean) {
  if (!form.guestAllergies[index]) form.guestAllergies[index] = { types: [], other: '' }
  if (value === 'none') {
    form.guestAllergies[index].types = checked ? ['none'] : []
    return
  }

  // when a non-none option is selected we must remove 'none'
  const current = form.guestAllergies[index].types || []
  if (checked) {
    const set = new Set(current.filter((t) => t !== 'none'))
    set.add(value)
    form.guestAllergies[index].types = Array.from(set)
  } else {
    form.guestAllergies[index].types = current.filter((t) => t !== value)
  }
  // ensure 'none' isn't present with other options
  if (
    (form.guestAllergies[index].types || []).includes('none') &&
    (form.guestAllergies[index].types || []).length > 1
  ) {
    form.guestAllergies[index].types = form.guestAllergies[index].types!.filter((t) => t !== 'none')
  }
}

// keep `form.names` and `form.guestAllergies` in sync with the numeric amount (total guests)
watch(
  () => form.amount,
  () => {
    // when amount is cleared, remove per-person entries (leave empty arrays until amount selected)
    if (!amountSelected.value) {
      form.names = []
      form.guestAllergies = []
      return
    }

    const n = totalGuests.value
    if (n > form.names.length) {
      for (let i = form.names.length; i < n; i++) {
        // index 0 is submitter; for the rest default to empty
        form.names.push(i === 0 ? form.name : '')
        form.guestAllergies.push({ types: ['none'], other: '' })
        form.guestDiets.push({ type: '', other: '' })
      }
    } else if (n < form.names.length) {
      form.names.splice(n)
      form.guestAllergies.splice(n)
      form.guestDiets.splice(n)
    }

    // ensure submitter name sync
    if (form.names.length > 0) form.names[0] = form.name
  },
)

// keep names[0] synced with the main submitter `form.name`
watch(
  () => form.name,
  (val) => {
    if (!form.names[0] || form.names[0] !== val) form.names[0] = val
  },
)
function validateForm() {
  // reset errors
  Object.keys(errors).forEach((k) => {
    errors[k as keyof typeof errors] = ''
  })

  if (!form.name.trim()) {
    errors.name = 'Name is required'
  }

  if (!form.rsvp.trim()) {
    errors.rsvp = 'RSVP is required'
  }

  // If the user is not coming, only name and RSVP are required — allow submit with notes
  if (form.rsvp === 'no') {
    const hasBasicErrors = !!errors.name || !!errors.rsvp
    return !hasBasicErrors
  }

  if (String(form.amount).trim() === '') {
    errors.amount = 'Amount is required'
  } else if (amountNumber.value < 1) {
    errors.amount = 'Please enter a valid number (at least 1)'
  }

  if (!form.travel.trim()) {
    errors.travel = 'Travel selection is required'
  } else if (form.travel === 'yes') {
    if (!form.arrivalDate || String(form.arrivalDate).trim() === '') {
      errors.arrivalDate = 'Arrival date is required'
    }
    if (!form.leavingDate || String(form.leavingDate).trim() === '') {
      errors.leavingDate = 'Leaving date is required'
    }
    // chronological check
    if (form.arrivalDate && form.leavingDate) {
      const a = new Date(String(form.arrivalDate))
      const l = new Date(String(form.leavingDate))
      if (l < a) {
        errors.leavingDate = 'Leaving date must be on or after arrival date'
      }
    }
    if (!form.car || (form.car !== 'yes' && form.car !== 'no')) {
      errors.car = 'Please indicate if you will have/use a car'
    }
    if (!form.lodging || !form.lodging.trim()) {
      errors.lodging = 'Accommodation address is required'
    }
  }

  if (!form.taxi.trim()) {
    errors.taxi = 'Taxi information is required'
  }

  // ensure names are provided for each person (submitter + guests)
  const n = totalGuests.value
  if (n > 0) {
    if (
      form.names.length !== n ||
      form.names.some((nm, idx) => (idx === 0 ? !form.name.trim() : !nm || !nm.trim()))
    ) {
      errors.names = 'Please enter the name for each person (including yourself)'
    }

    // Per-guest allergy selections are optional. Only validate when 'other' is selected.
    for (let i = 0; i < n; i++) {
      const g = form.guestAllergies?.[i] ? form.guestAllergies[i] : { types: ['none'], other: '' }
      if (g && g.types && g.types.includes('other') && (!g.other || !g.other.trim())) {
        errors.guestAllergies = 'Please specify allergy details for persons marked as Other'
        break
      }
    }
  }

  const hasErrors = Object.values(errors).some((v) => v && String(v).length > 0)
  return !hasErrors
}

async function submitForm() {
  successMessage.value = ''
  errorMessage.value = ''

  if (!validateForm()) {
    return
  }

  if (isComing.value) {
    // ensure submitter is first in the names array
    form.names[0] = form.name
    // ensure names array length matches totalGuests before sending
    while (form.names.length < totalGuests.value) form.names.push('')
    while (form.guestAllergies.length < totalGuests.value)
      form.guestAllergies.push({ types: ['none'], other: '' })
    // ensure guestDiets array is present for each person (empty means 'No preference')
    while (form.guestDiets.length < totalGuests.value) form.guestDiets.push({ type: '', other: '' })
  } else {
    // minimal payload for non-attendees
    form.amount = '0'
    form.names = []
    form.guestAllergies = []
    form.guestDiets = []
    form.travel = ''
    form.arrivalDate = ''
    form.leavingDate = ''
    form.car = ''
    form.lodging = ''
    form.taxi = ''
    form.song = ''
  }

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
      // clear any existing auto-hide timer
      if (autoHideTimer.value) {
        clearTimeout(autoHideTimer.value)
        autoHideTimer.value = null
      }

      // use a concise message for non-attendees
      successMessage.value =
        form.rsvp === 'no'
          ? 'Thank you for your RSVP.'
          : 'Thank you for your RSVP! We look forward to seeing you and celebrating with you.'

      resetForm()

      // auto-hide the success message after 10 seconds
      autoHideTimer.value = window.setTimeout(() => {
        successMessage.value = ''
        autoHideTimer.value = null
      }, 10000)
    } else {
      errorMessage.value = data.message || 'Something went wrong. Please try again.'
    }
  } catch {
    errorMessage.value = 'Network error. Please check your connection and try again.'
  } finally {
    loading.value = false
  }
}

function resetForm() {
  form.name = ''
  form.rsvp = ''
  form.amount = ''
  form.names = []
  form.guestAllergies = []
  form.guestDiets = []
  form.travel = ''
  form.arrivalDate = ''
  form.leavingDate = ''
  form.car = ''
  form.lodging = ''
  form.details = ''
  form.taxi = ''
  form.questions = ''
  form.song = ''
}

onUnmounted(() => {
  if (autoHideTimer.value) {
    clearTimeout(autoHideTimer.value)
    autoHideTimer.value = null
  }
})
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
.beThere b {
  color: black;
}

.form {
  display: flex;
  flex-direction: column;
  margin-bottom: 4rem;
  width: 100%;
}
.form label {
  width: 25%;
  margin-right: 0;
}
.formItem {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1rem;
  gap: 1rem;
}
.formItem input,
.formItem textarea,
.formItem select {
  font-family: 'EB Garamond', Arial, Helvetica, sans-serif;
  font-size: 1.5rem;

  border: #a0ba9f 2px solid;
  width: 30%;
  background-color: white;
  padding: 0.4rem 0.6rem;
}

.names-list {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  width: 75%;
}
.name-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  border: #a0ba9f 2px dotted;
}

.name-item > div,
.name-item input,
.name-item select {
  flex: 1;
}

.guest-allergy-checkboxes {
  flex: 1;
}

.name-item select {
  height: 100%;
}
.guest-allergy-checkboxes {
  display: flex;
  flex-direction: column;
}
.guest-allergy-checkboxes label {
  width: 100%;
}
.guest-allergy-checkboxes input[type='checkbox'] {
  max-width: 15px;
}

.formItemRadio {
  width: 30%;
}
.radiobuttons {
  display: flex;
}
.radiobuttons input[type='radio'] {
  max-width: 15px;
}

.form button {
  align-self: center;
}

.success-message {
  color: green !important;
  margin-bottom: 4rem;
}
.error-message {
  color: red !important;
}

@media (max-width: 1150px) {
  .names-list,
  .formItem input,
  .formItem textarea,
  .formItem select,
  .formItemRadio {
    width: 50%;
  }
  .name-item {
    flex-direction: column;
    align-items: flex-start;
  }
  .name-item input[type='text'],
  .name-item select,
  .guest-allergy-checkboxes {
    width: 100%;
  }
}

@media (max-width: 768px) {
  .formItem {
    gap: 1rem;
  }
  .form label {
    width: 40%;
  }
  .names-list,
  .formItem input,
  .formItem textarea,
  .formItemRadio {
    width: 60%;
  }
  .guest-allergy-checkboxes label {
    width: auto;
  }
  .align-top {
    align-items: flex-start;
  }
}

@media (max-width: 500px) {
  .beThere {
    padding: 2rem;
  }
}
@media (max-width: 481px) {
  .formItem input,
  .formItem textarea,
  .formItem select {
    font-size: 1.2rem;
  }
}

@media (max-width: 379px) {
  .beThere {
    padding: 2rem 1rem;
  }

  .formItem {
    flex-direction: column;
    align-items: flex-start;
  }

  .form label {
    width: 100%;
  }
  .names-list,
  .formItem input,
  .formItem textarea,
  .formItemRadio {
    width: 100%;
  }
  .success-message {
    margin-bottom: 6rem;
  }
}
</style>
