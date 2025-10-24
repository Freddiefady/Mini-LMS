// import './bootstrap';
import Alpine from 'alpinejs';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Global dark mode state (persisted in localStorage alternative - using cookies for demo)
document.addEventListener('alpine:init', () => {
    Alpine.store('darkMode', {
        on: false,

        toggle() {
            this.on = !this.on;
            document.cookie = `darkMode=${this.on}; path=/; max-age=31536000`;
        },

        init() {
            // Read from cookie
            const cookies = document.cookie.split(';');
            const darkModeCookie = cookies.find(c => c.trim().startsWith('darkMode='));
            if (darkModeCookie) {
                this.on = darkModeCookie.split('=')[1] === 'true';
            }
        }
    });
});
