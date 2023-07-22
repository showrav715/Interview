
import '../sass/app.scss'
import Router from '@/router'
import 'bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler';
const app = createApp({})
app.use(Router)
app.mount('#app')

// app.use(Router)
// app.mount('#app')