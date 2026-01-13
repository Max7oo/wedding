import { createApp } from 'vue'
import PrimeVue from 'primevue/config'
import App from './App.vue'
import './App.css'

const app = createApp(App)

app.use(PrimeVue)

app.mount('#app')
