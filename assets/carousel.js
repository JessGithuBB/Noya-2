/**
 * Carrousel JavaScript - Version Autonome et Responsive
 * Fonctionnalités:
 * - Défilement fluide avec les flèches gauche/droite
 * - Arrêt aux extrémités (pas de boucle infinie)
 * - Désactivation des flèches aux bords
 * - Responsive (1 mobile, 2 tablette, 4 desktop)
 * - Autoplay avec pause au survol
 * - Pagination avec dots cliquables
 */

document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("carouselContainer");
    const prevBtn = document.getElementById("prevBtn");
    const nextBtn = document.getElementById("nextBtn");
    const dotsContainer = document.getElementById("carouselDots");
    const carouselWrapper = document.querySelector(".carousel-wrapper");

    if (!container || !prevBtn || !nextBtn || !dotsContainer) {
        console.log("❌ Éléments du carrousel non trouvés");
        return;
    }

    const slides = container.querySelectorAll(".carousel-slide");
    const totalSlides = slides.length;

    if (totalSlides === 0) {
        console.log("❌ Aucune carte trouvée");
        return;
    }

    console.log(`✅ Carrousel: ${totalSlides} cartes trouvées`);

    let currentIndex = 0;
    let slidesToShow = 4;
    let autoplayInterval = null;
    const GAP = 20; // Espace entre les cartes en pixels

    // Obtenir le nombre de cartes visibles selon la taille d'écran
    function getSlidesToShow() {
        const width = window.innerWidth;
        if (width >= 1200) return 4; // Desktop
        if (width >= 992) return 3; // Tablette large
        if (width >= 576) return 2; // Tablette
        return 1; // Mobile
    }

    // Calculer le nombre maximum de positions
    function getMaxIndex() {
        return Math.max(0, totalSlides - slidesToShow);
    }

    // Mettre à jour l'état des boutons
    function updateButtons() {
        const maxIndex = getMaxIndex();

        // Si on a assez de cartes pour faire défiler
        if (totalSlides <= slidesToShow) {
            // Désactiver les deux boutons s'il n'y a pas assez de cartes
            prevBtn.disabled = true;
            nextBtn.disabled = true;
            prevBtn.style.opacity = "0.5";
            nextBtn.style.opacity = "0.5";
            prevBtn.style.cursor = "not-allowed";
            nextBtn.style.cursor = "not-allowed";
            return;
        }

        // Bouton précédent
        if (currentIndex === 0) {
            prevBtn.disabled = true;
            prevBtn.style.opacity = "0.5";
            prevBtn.style.cursor = "not-allowed";
        } else {
            prevBtn.disabled = false;
            prevBtn.style.opacity = "1";
            prevBtn.style.cursor = "pointer";
        }

        // Bouton suivant
        if (currentIndex >= maxIndex) {
            nextBtn.disabled = true;
            nextBtn.style.opacity = "0.5";
            nextBtn.style.cursor = "not-allowed";
        } else {
            nextBtn.disabled = false;
            nextBtn.style.opacity = "1";
            nextBtn.style.cursor = "pointer";
        }
    }

    // Créer les points de pagination
    function createDots() {
        dotsContainer.innerHTML = "";
        const totalPages = getMaxIndex() + 1;

        // Ne créer des dots que s'il y a plus d'une page
        if (totalPages <= 1) return;

        for (let i = 0; i < totalPages; i++) {
            const dot = document.createElement("button");
            dot.className = "dot";
            dot.setAttribute("aria-label", `Page ${i + 1}`);
            if (i === currentIndex) dot.classList.add("active");

            dot.addEventListener("click", () => {
                currentIndex = i;
                updateCarousel();
                stopAutoplay();
            });

            dotsContainer.appendChild(dot);
        }
    }

    // Mettre à jour les dots actifs
    function updateDots() {
        const dots = dotsContainer.querySelectorAll(".dot");
        dots.forEach((dot, index) => {
            dot.classList.toggle("active", index === currentIndex);
        });
    }

    // Mettre à jour la position du carrousel
    function updateCarousel() {
        const slideWidth = slides[0].offsetWidth;
        const offset = -currentIndex * (slideWidth + GAP);

        container.style.transition =
            "transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94)";
        container.style.transform = `translateX(${offset}px)`;

        updateButtons();
        updateDots();

        console.log(`📍 Position: ${currentIndex + 1}/${getMaxIndex() + 1}`);
    }

    // Aller au slide suivant
    function nextSlide() {
        const maxIndex = getMaxIndex();
        if (currentIndex < maxIndex) {
            currentIndex++;
            updateCarousel();
        }
    }

    // Aller au slide précédent
    function prevSlide() {
        if (currentIndex > 0) {
            currentIndex--;
            updateCarousel();
        }
    }

    // Démarrer l'autoplay
    function startAutoplay() {
        stopAutoplay();
        autoplayInterval = setInterval(() => {
            const maxIndex = getMaxIndex();
            if (currentIndex < maxIndex) {
                nextSlide();
            } else {
                // Revenir au début
                currentIndex = 0;
                updateCarousel();
            }
        }, 4000);
    }

    // Arrêter l'autoplay
    function stopAutoplay() {
        if (autoplayInterval) {
            clearInterval(autoplayInterval);
            autoplayInterval = null;
        }
    }

    // Gérer le redimensionnement
    function handleResize() {
        const newSlidesToShow = getSlidesToShow();
        if (newSlidesToShow !== slidesToShow) {
            slidesToShow = newSlidesToShow;
            const maxIndex = getMaxIndex();
            if (currentIndex > maxIndex) {
                currentIndex = maxIndex;
            }
            createDots();
            updateCarousel();
        }
    }

    // Event listeners
    prevBtn.addEventListener("click", (e) => {
        e.preventDefault();
        prevSlide();
        stopAutoplay();
    });

    nextBtn.addEventListener("click", (e) => {
        e.preventDefault();
        nextSlide();
        stopAutoplay();
    });

    // Pause au survol
    if (carouselWrapper) {
        carouselWrapper.addEventListener("mouseenter", stopAutoplay);
        carouselWrapper.addEventListener("mouseleave", startAutoplay);
    }

    // Redimensionnement avec debounce
    let resizeTimer;
    window.addEventListener("resize", () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(handleResize, 250);
    });

    // Initialisation
    slidesToShow = getSlidesToShow();

    // S'assurer que les boutons sont activés au départ
    prevBtn.disabled = false;
    nextBtn.disabled = false;
    prevBtn.style.opacity = "1";
    nextBtn.style.opacity = "1";
    prevBtn.style.cursor = "pointer";
    nextBtn.style.cursor = "pointer";

    createDots();
    updateCarousel();
    startAutoplay();

    console.log("✅ Carrousel initialisé!");
    console.log(
        `📊 Total: ${totalSlides} cartes | Visibles: ${slidesToShow} | Max position: ${getMaxIndex()}`
    );
});
