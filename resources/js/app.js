import './bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';

import { createApp } from 'vue'; // Import Vue
import Echo from 'laravel-echo';
import { createRouter, createWebHistory } from 'vue-router';
import App from './components/App.vue';
import Pusher from 'pusher-js';

import Ticket from './components/TicketComponent.vue';
import CallComponent from './components/CallComponent.vue';
import Dashboard from './components/DashboardComponent.vue';
import Report from './components/ReportComponent.vue';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

window.Echo.channel('tickets')
    .listen('TicketUpdated', (e) => {
        alert(`Nouveau ticket ou mise à jour : ${e.ticket.subject}`);
    });



// Create a new Vue app instance
const app = createApp({});

const routes = [
    { path: '/', component: CallComponent, name: 'home' },
    { path: '/tickets', component: Ticket, name: 'tickets' },
    { path: '/dashboard', component: Dashboard, name: 'dashboard' },
    { path: '/report', component: Report, name: 'report' },
];
const router = createRouter({
    history: createWebHistory(),
    routes,
});
createApp(App)
    .use(router)
    .mount('#app');

