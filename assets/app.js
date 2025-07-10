/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

require('bootstrap');
import 'bootstrap-icons/font/bootstrap-icons.css'; 
import './styles/app.scss';





/////////////  Icon Search ///////////////

const searchToggle = document.getElementById('searchToggle');
const searchInput = document.getElementById('searchInput');

searchToggle.addEventListener('click', () => {
  searchInput.classList.toggle('expanded');
  if (searchInput.classList.contains('expanded')) {
    searchInput.focus();
  }
});

document.addEventListener('click', (e) => {
  if (!document.querySelector('.search-container').contains(e.target)) {
    searchInput.classList.remove('expanded');
  }
});

 /////////////// Confirmation et Bienvenue Popups /////////////////
window.closeConfirmationPopup = function () {
    const popup = document.getElementById('confirmation-popup');
    if (popup) popup.style.display = 'none';
};

window.closeWelcomePopup = function () {
    const popup = document.getElementById('welcome-popup');
    if (popup) popup.style.display = 'none';
};
