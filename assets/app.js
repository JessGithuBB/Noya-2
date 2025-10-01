/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.scss";

require("bootstrap");
import "bootstrap-icons/font/bootstrap-icons.css";

/////////////  Icon Search ///////////////

const searchToggle = document.getElementById("searchToggle");
const searchInput = document.getElementById("searchInput");

searchToggle.addEventListener("click", () => {
    searchInput.classList.toggle("expanded");
    if (searchInput.classList.contains("expanded")) {
        searchInput.focus();
    }
});

document.addEventListener("click", (e) => {
    if (!document.querySelector(".search-container").contains(e.target)) {
        searchInput.classList.remove("expanded");
    }
});

/////////////// Confirmation et Bienvenue Popups /////////////////

document.addEventListener("DOMContentLoaded", function () {
    // Sélectionne tous les popups
    const popups = document.querySelectorAll(".popup");

    popups.forEach((popup) => {
        popup.style.display = "flex";

        const closeBtn = popup.querySelector(".close-popup");
        if (closeBtn) {
            closeBtn.addEventListener("click", () => {
                popup.style.display = "none";
            });
        }
    });
});

////////////// POP UP CLICK USER MENU ///////////////

document.addEventListener("DOMContentLoaded", function () {
    const userToggle = document.querySelector(".user-toggle");
    const dropdownMenu = document.querySelector(".dropdown-menu-user");

    if (userToggle && dropdownMenu) {
        userToggle.addEventListener("click", function (event) {
            event.preventDefault();
            dropdownMenu.classList.toggle("show");
        });

        // Optionnel : cliquer ailleurs ferme le menu
        document.addEventListener("click", function (event) {
            if (
                !userToggle.contains(event.target) &&
                !dropdownMenu.contains(event.target)
            ) {
                dropdownMenu.classList.remove("show");
            }
        });
    }
});

///// Bannière défilante Noya /////
document.addEventListener("DOMContentLoaded", function () {
    // Ajuster la vitesse d'animation
    const banners = document.querySelectorAll(".simple-banner[data-speed]");

    banners.forEach((banner) => {
        const speed = banner.getAttribute("data-speed");
        const content = banner.querySelector(".banner-content");
        if (content && speed) {
            content.style.animationDuration = speed;
        }
    });

    // Optimisation performance : pause quand invisible
    if ("IntersectionObserver" in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                const content = entry.target.querySelector(".banner-content");
                if (content) {
                    content.style.animationPlayState = entry.isIntersecting
                        ? "running"
                        : "paused";
                }
            });
        });

        banners.forEach((banner) => observer.observe(banner));
    }
});

/// CAROUSEL ARTICLES ///

document.addEventListener("DOMContentLoaded", () => {
    const track = document.querySelector(".custom-carousel-track");
    const items = document.querySelectorAll(".custom-carousel-item");
    const prevBtn = document.querySelector(".carousel-btn.prev");
    const nextBtn = document.querySelector(".carousel-btn.next");

    if (!track || items.length === 0 || !prevBtn || !nextBtn) {
        return; // Pas de carrousel sur cette page
    }

    let index = 0;
    const itemsToShow = 4; // Nombre de cartes visibles à la fois
    const itemWidth = items[0].offsetWidth + 20; // largeur + padding

    function moveCarousel() {
        track.style.transform = `translateX(${-index * itemWidth}px)`;
    }

    nextBtn.addEventListener("click", () => {
        index++;
        if (index > items.length - itemsToShow) {
            index = 0; // reset
        }
        moveCarousel();
    });

    prevBtn.addEventListener("click", () => {
        index--;
        if (index < 0) {
            index = items.length - itemsToShow;
        }
        moveCarousel();
    });
});
