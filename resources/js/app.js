/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';

/**
 * Import Vue 3
 */
import { createApp } from 'vue';

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Auto-register all Vue components (Vue 3 + Vite version)
// const files = import.meta.glob('./components/**/*.vue', { eager: true })
// Object.entries(files).forEach(([path, module]) => {
//     const name = path.split('/').pop().split('.')[0]
//     createApp().component(name, module.default)
// })

/**
 * Register individual components
 */
import ExampleComponent from './components/ExampleComponent.vue';

/**
 * Create and mount Vue application instance (Vue 3 syntax)
 */
const app = createApp({});

app.component('example-component', ExampleComponent);

app.mount('#app');
