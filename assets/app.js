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

document.addEventListener('DOMContentLoaded', function () {
    // Sélectionne tous les popups
    const popups = document.querySelectorAll('.popup');

    popups.forEach((popup) => {
        popup.style.display = 'flex';

        const closeBtn = popup.querySelector('.close-popup');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                popup.style.display = 'none';
            });
        }
    });
});


////////////// POP UP CLICK USER MENU ///////////////

document.addEventListener('DOMContentLoaded', function() {
    const userToggle = document.querySelector('.user-toggle');
    const dropdownMenu = document.querySelector('.dropdown-menu-user');

    if (userToggle && dropdownMenu) {
        userToggle.addEventListener('click', function(event) {
            event.preventDefault();
            dropdownMenu.classList.toggle('show');
        });

        // Optionnel : cliquer ailleurs ferme le menu
        document.addEventListener('click', function(event) {
            if (!userToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    }
});

////////////// AJAX Search ///////////////

document.addEventListener('DOMContentLoaded', function () {
    const searchForm = document.querySelector('#searchBox form');
    const input = searchForm.querySelector('input[name="q"]');

    searchForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const q = input.value;

        fetch(`/ajax/search?q=${encodeURIComponent(q)}`)
            .then(res => res.json())
            .then(data => {
                const container = document.querySelector('#search-results');
                container.innerHTML = '';

                if (data.articles.length === 0) {
                    container.innerHTML = '<p>Aucun article trouvé.</p>';
                    return;
                }

                data.articles.forEach(article => {
                    const card = `
                        <div class="card mb-3">
                            <img src="${article.image}" class="card-img-top" alt="${article.name}">
                            <div class="card-body">
                                <h5 class="card-title">${article.name}</h5>
                                <p class="card-text">${article.price} €</p>
                                <a href="${article.url}" class="btn btn-primary">Voir</a>
                            </div>
                        </div>
                    `;
                    container.innerHTML += card;
                });
            });
    });
});


