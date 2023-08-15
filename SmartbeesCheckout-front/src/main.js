import './assets/main.css'
import { VueRecaptchaPlugin } from 'vue-recaptcha/head'

import { createApp } from 'vue'
import App from './App.vue'



const app = createApp(App);
app.use(VueRecaptchaPlugin, {
    v3SiteKey: '6LcqOaknAAAAAEjinNQeht9qBxOCM-25MgPfJx9a',
  })
app.mount('#app');

