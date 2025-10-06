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

/////////////// CARROUSEL NOUVEAUTÉS /////////////////

document.addEventListener("DOMContentLoaded", function () {
    // Initialisation du carrousel simple
    initSimpleCarousel();

    /////////////// PRODUCT CARDS FUNCTIONALITY /////////////////

    // Gestion de l'ajout au panier
    const addToCartButtons = document.querySelectorAll(".add-to-cart");
    const cartBadge = document.querySelector(".cart-badge, .cart-badge-mobile");
    let cartCount = 0;

    addToCartButtons.forEach((button) => {
        button.addEventListener("click", function (e) {
            e.preventDefault();

            const productId = this.getAttribute("data-product");
            const productName =
                this.closest(".product-card").querySelector("h3").textContent;

            // Animation du bouton
            const originalText = this.textContent;
            const originalClass = this.className;

            // Changer l'apparence du bouton
            this.textContent = "Ajouté !";
            this.className = "btn btn-success btn-sm add-to-cart";

            // Incrémenter le compteur du panier
            cartCount++;
            if (cartBadge) {
                cartBadge.textContent = cartCount;
                cartBadge.style.display = "block";
            }

            // Afficher une notification
            showNotification(`${productName} ajouté au panier !`);

            // Réinitialiser le bouton après 2 secondes
            setTimeout(() => {
                this.textContent = originalText;
                this.className = originalClass;
            }, 2000);

            // Simuler l'ajout au panier (à remplacer par un appel AJAX réel)
            console.log(`Produit ajouté au panier: ${productId}`);
        });
    });

    // Fonction pour afficher les notifications
    function showNotification(message) {
        // Créer l'élément de notification
        const notification = document.createElement("div");
        notification.className = "cart-notification";
        notification.innerHTML = `
            <div class="notification-content">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                <span>${message}</span>
                <button type="button" class="btn-close ms-2" aria-label="Fermer"></button>
            </div>
        `;

        // Ajouter les styles
        notification.style.cssText = `
            position: fixed;
            top: 120px;
            right: 20px;
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 12px 16px;
            z-index: 1050;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
            max-width: 300px;
        `;

        // Ajouter au DOM
        document.body.appendChild(notification);

        // Animation d'apparition
        setTimeout(() => {
            notification.style.opacity = "1";
            notification.style.transform = "translateX(0)";
        }, 100);

        // Gestion du bouton fermer
        const closeBtn = notification.querySelector(".btn-close");
        closeBtn.addEventListener("click", () => {
            removeNotification(notification);
        });

        // Auto-fermeture après 4 secondes
        setTimeout(() => {
            if (notification.parentNode) {
                removeNotification(notification);
            }
        }, 4000);
    }

    function removeNotification(notification) {
        notification.style.opacity = "0";
        notification.style.transform = "translateX(100%)";
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }

    // Gestion des favoris
    const favoriteButtons = document.querySelectorAll(".favorite-btn");
    favoriteButtons.forEach((button) => {
        button.addEventListener("click", function (e) {
            e.preventDefault();

            const icon = this.querySelector("i");
            const isActive = icon.classList.contains("text-danger");

            if (isActive) {
                icon.classList.remove("text-danger");
                icon.classList.add("text-muted");
            } else {
                icon.classList.remove("text-muted");
                icon.classList.add("text-danger");
            }
        });
    });
});

// Fonction du carrousel simple
function initSimpleCarousel() {
    console.log("🎠 Initialisation du carrousel...");

    const container = document.getElementById("carouselContainer");
    const prevBtn = document.getElementById("prevBtn");
    const nextBtn = document.getElementById("nextBtn");
    const dotsContainer = document.getElementById("carouselDots");

    console.log("Éléments trouvés:", {
        container: !!container,
        prevBtn: !!prevBtn,
        nextBtn: !!nextBtn,
        dotsContainer: !!dotsContainer,
    });

    if (!container || !prevBtn || !nextBtn || !dotsContainer) {
        console.log("❌ Carrousel non trouvé - éléments manquants");
        return;
    }

    const slides = container.querySelectorAll(".carousel-slide");
    const totalSlides = slides.length;
    let currentSlide = 0;
    let slidesToShow = 4; // Desktop par défaut

    console.log(`📊 ${totalSlides} cartes trouvées`);

    // Déterminer le nombre de slides à afficher selon la taille d'écran
    function updateSlidesToShow() {
        const width = window.innerWidth;
        if (width >= 1200) {
            slidesToShow = 4; // Desktop
        } else if (width >= 992) {
            slidesToShow = 3; // Tablette large
        } else if (width >= 576) {
            slidesToShow = 2; // Tablette
        } else {
            slidesToShow = 1; // Mobile
        }

        const totalPages = Math.ceil(totalSlides / slidesToShow);
        if (currentSlide >= totalPages) {
            currentSlide = totalPages - 1;
        }

        console.log(
            `📱 Affichage: ${slidesToShow} cartes par page (${totalPages} pages)`
        );
        updateCarousel();
        updateDots();
    }

    // Mettre à jour la position du carrousel
    function updateCarousel() {
        const translateX = -(currentSlide * (100 / slidesToShow));
        container.style.transform = `translateX(${translateX}%)`;
        console.log(`🎯 Page ${currentSlide + 1} - Décalage: ${translateX}%`);
    }

    // Créer les points de pagination
    function createDots() {
        dotsContainer.innerHTML = "";
        const totalPages = Math.ceil(totalSlides / slidesToShow);

        for (let i = 0; i < totalPages; i++) {
            const dot = document.createElement("button");
            dot.className = "dot";
            if (i === currentSlide) dot.classList.add("active");
            dot.addEventListener("click", () => goToSlide(i));
            dotsContainer.appendChild(dot);
        }
        console.log(`📍 ${totalPages} points de pagination créés`);
    }

    // Mettre à jour les points actifs
    function updateDots() {
        const dots = dotsContainer.querySelectorAll(".dot");
        dots.forEach((dot, index) => {
            dot.classList.toggle("active", index === currentSlide);
        });
    }

    // Aller à un slide spécifique
    function goToSlide(slideIndex) {
        const totalPages = Math.ceil(totalSlides / slidesToShow);
        currentSlide = Math.max(0, Math.min(slideIndex, totalPages - 1));
        console.log(`🎯 Aller à la page ${currentSlide + 1}`);
        updateCarousel();
        updateDots();
    }

    // Slide suivant
    function nextSlide() {
        const totalPages = Math.ceil(totalSlides / slidesToShow);
        currentSlide = (currentSlide + 1) % totalPages;
        console.log(`➡️ Page suivante: ${currentSlide + 1}`);
        updateCarousel();
        updateDots();
    }

    // Slide précédent
    function prevSlide() {
        const totalPages = Math.ceil(totalSlides / slidesToShow);
        currentSlide = (currentSlide - 1 + totalPages) % totalPages;
        console.log(`⬅️ Page précédente: ${currentSlide + 1}`);
        updateCarousel();
        updateDots();
    }

    // Event listeners
    prevBtn.addEventListener("click", (e) => {
        e.preventDefault();
        prevSlide();
    });

    nextBtn.addEventListener("click", (e) => {
        e.preventDefault();
        nextSlide();
    });

    // Redimensionnement de la fenêtre
    window.addEventListener("resize", updateSlidesToShow);

    // Initialisation
    updateSlidesToShow();
    createDots();
    console.log("✅ Carrousel initialisé avec succès!");

    // Autoplay (optionnel)
    setInterval(nextSlide, 5000);
}
