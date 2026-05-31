import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

// Mode Sombre Interactif (Option B)
const initThemeToggle = () => {
    const toggleBtn = document.getElementById('theme-toggle');
    if (toggleBtn) {
        // Mettre à jour l'icône du bouton en fonction du thème actif
        const updateIcon = () => {
            const isDark = document.documentElement.classList.contains('dark');
            const iconSpan = toggleBtn.querySelector('.theme-toggle-icon');
            if (iconSpan) {
                iconSpan.textContent = isDark ? '☀️' : '🌙';
            }
        };
        
        // Initialiser l'icône au chargement
        updateIcon();

        // Gérer le clic sur le bouton (réinitialisation propre)
        toggleBtn.onclick = () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateIcon();
        };
    }
};

document.addEventListener('DOMContentLoaded', initThemeToggle);
document.addEventListener('turbo:load', initThemeToggle);


