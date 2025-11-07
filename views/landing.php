<?php 
$pageTitle = "Catalogue d'Upcycling";
require_once 'header.php'; 
?>
<style>
.hero-section { position: relative; color: white; overflow: hidden; }
.hero-background { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?q=80&w=2070&auto=format&fit=crop'); background-size: cover; background-position: center; z-index: 1; }
.hero-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to top, rgba(44, 62, 80, 0.8), rgba(44, 62, 80, 0.5)); z-index: 2; }
.hero-content { position: relative; z-index: 3; }
.feature-icon-card { text-align: center; padding: 2.5rem 2rem; background: white; border-radius: 1rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #ecf0f1; transition: transform 0.3s ease, box-shadow 0.3s ease; }
.feature-icon-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
.feature-icon { display: inline-flex; align-items: center; justify-content: center; height: 70px; width: 70px; border-radius: 50%; background-color: #e8f5e9; margin-bottom: 1.5rem; transition: background-color 0.3s ease; }
.feature-icon-card:hover .feature-icon { background-color: #27ae60; }
.feature-icon-card:hover .feature-icon svg { stroke: white; }
.feature-icon svg { transition: stroke 0.3s ease; }
.cta-section { background-image: linear-gradient(rgba(44, 62, 80, 0.85), rgba(44, 62, 80, 0.85)), url('https://images.unsplash.com/photo-1542601904-8241f061732d?q=80&w=1924&auto=format&fit=crop'); background-attachment: fixed; background-position: center; background-size: cover; }

/* --- New Carousel Styles --- */
.carousel-container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
}
.carousel-viewport {
    overflow: hidden;
    width: 100%;
    /* Add gradient mask for fading effect on edges */
    -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
    mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
}
.carousel-track {
    display: flex;
    /* Use CSS animation for scrolling */
    animation: scroll var(--scroll-duration, 40s) linear infinite;
    pointer-events: none; /* Disable mouse events on track */
}
/* Pause animation on hover */
.carousel-viewport:hover .carousel-track {
    animation-play-state: paused;
}
.carousel-slide {
    flex: 0 0 100%;
    padding: 0 1rem;
    pointer-events: auto; /* Re-enable mouse events for slides */
}
@media (min-width: 768px) {
    .carousel-slide {
        flex-basis: 50%;
    }
}
@media (min-width: 1024px) {
    .carousel-slide {
        flex-basis: 33.333%;
    }
}

/* Keyframes for the continuous scroll */
@keyframes scroll {
    0% {
        transform: translateX(0);
    }
    100% {
        /* Scroll by the width of the original set of slides */
        transform: translateX(calc(var(--scroll-width, -2000px) * -1));
    }
}
</style>

<div class="hero-section">
    <div class="hero-background"></div>
    <div class="hero-overlay"></div>
    <div class="hero-content container mx-auto px-6 py-24 md:py-32 text-center">
        <h1 class="text-4xl md:text-6xl font-bold leading-tight" style="font-family: 'Ubuntu', sans-serif;">La Créativité est la Nouvelle Richesse.</h1>
        <p class="mt-6 text-lg md:text-xl max-w-3xl mx-auto text-gray-200">Découvrez comment un simple objet du quotidien peut devenir une œuvre d'art, un outil pratique ou un cadeau unique.</p>
        <div class="mt-10">
            <a href="index.php?action=createProject" class="animated-button">
                <svg xmlns="http://www.w3.org/2000/svg" class="arr-2" viewBox="0 0 24 24"><path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"></path></svg>
                <span class="text">Partagez Votre Idée</span>
                <span class="circle"></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="arr-1" viewBox="0 0 24 24"><path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"></path></svg>
            </a>
        </div>
    </div>
</div>

<section class="py-20 bg-white reveal">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16"><h2 class="text-3xl md:text-4xl font-bold">Comment ça marche ?</h2><p class="text-gray-500 mt-2 max-w-2xl mx-auto">Un cycle simple pour un impact maximal. Trouvez, créez, et inspirez.</p></div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="feature-icon-card"><div class="feature-icon"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#27ae60" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></div><h3 class="text-xl font-bold mb-2">1. Trouvez l'Inspiration</h3><p class="text-gray-600">Explorez des centaines d'idées soumises par notre communauté pour transformer des objets que vous possédez déjà.</p></div>
            <div class="feature-icon-card"><div class="feature-icon"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#27ae60" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg></div><h3 class="text-xl font-bold mb-2">2. Créez Votre Œuvre</h3><p class="text-gray-600">Adaptez une idée pour créer quelque chose d'entièrement nouveau et personnel.</p></div>
            <div class="feature-icon-card"><div class="feature-icon"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#27ae60" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg></div><h3 class="text-xl font-bold mb-2">3. Partagez & Inspirez</h3><p class="text-gray-600">Publiez votre tutoriel pour inspirer à votre tour la communauté et contribuer à un monde plus durable.</p></div>
        </div>
    </div>
</section>

<div class="py-20 reveal">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16"><h2 class="text-3xl md:text-4xl font-bold">Idées à la Une</h2><p class="text-gray-500 mt-2 max-w-2xl mx-auto">Découvrez les dernières créations qui font vibrer la communauté ZeroWaste.</p></div>
        
        <?php if (empty($projetsRecents)): ?>
            <div class="text-center text-gray-500 py-12"><p>Aucun projet à afficher pour le moment. Soyez le premier à partager !</p></div>
        <?php else: ?>
            <div class="carousel-container">
                <div class="carousel-viewport">
                    <div id="carousel-track" class="carousel-track">
                        <?php foreach ($projetsRecents as $projet): ?>
                            <div class="carousel-slide">
                                <div class="professional-card w-full h-full">
                                    <div class="card-image-container">
                                        <span class="card-category"><?= htmlspecialchars($projet['categorie_nom']) ?></span>
                                        <a href="index.php?action=showProject&id=<?= htmlspecialchars($projet['id']) ?>">
                                            <img src="uploads/<?= htmlspecialchars($projet['cover_image_url']) ?>" alt="Photo de <?= htmlspecialchars($projet['titre']) ?>">
                                        </a>
                                    </div>
                                    <div class="card-content">
                                        <h3 class="card-title"><?= htmlspecialchars($projet['titre']) ?></h3>
                                        <p class="card-text">Base : <?= htmlspecialchars($projet['materiel_principal']) ?></p>
                                        <a href="index.php?action=showProject&id=<?= htmlspecialchars($projet['id']) ?>" class="card-button">Découvrir le Projet</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <!-- Duplicated slides will be added here by JavaScript -->
                    </div>
                </div>
                <!-- Arrow buttons removed -->
            </div>
            
            <div class="text-center mt-16">
                <a href="index.php?action=listProjects" class="animated-button"><svg xmlns="http://www.w3.org/2000/svg" class="arr-2" viewBox="0 0 24 24"><path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"></path></svg><span class="text">Voir Toutes les Idées</span><span class="circle"></span><svg xmlns="http://www.w3.org/2000/svg" class="arr-1" viewBox="0 0 24 24"><path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"></path></svg></a>
            </div>
        <?php endif; ?>
    </div>
</div>

<section class="cta-section py-20 text-white reveal">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-3xl md:text-4xl font-bold" style="font-family: 'Ubuntu', sans-serif;">Votre Prochaine Idée Brillante Commence Ici.</h2>
        <p class="text-gray-200 mt-4 mb-8 max-w-2xl mx-auto">Devenez un acteur du changement. Partagez votre créativité avec le monde.</p>
        <div class="flex justify-center">
            <a href="index.php?action=createProject" class="animated-button bg-white text-brand-green shadow-none" style="box-shadow: 0 0 0 2px #27ae60;"><svg xmlns="http://www.w3.org/2000/svg" class="arr-2" viewBox="0 0 24 24" fill="#2C3E50"><path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"></path></svg><span class="text" style="color: #2C3E50;">Je Partage ma Création</span><span class="circle" style="background-color: #27ae60;"></span><svg xmlns="http://www.w3.org/2000/svg" class="arr-1" viewBox="0 0 24 24" fill="#2C3E50"><path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"></path></svg></a>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const track = document.getElementById('carousel-track');
    if (!track) return;

    // --- New Auto-Scroll Logic ---
    const slides = Array.from(track.children);
    if (slides.length === 0) return;

    // 1. Duplicate slides for seamless loop
    slides.forEach(slide => {
        const clone = slide.cloneNode(true);
        track.appendChild(clone);
    });

    // 2. Calculate animation variables
    function updateAnimationVariables() {
        const slidesOriginal = Array.from(track.children).slice(0, slides.length);
        let totalWidth = 0;
        slidesOriginal.forEach(slide => {
            totalWidth += slide.offsetWidth;
        });

        // Set CSS variables for the animation
        const secondsPerSlide = 5; 
        const duration = slides.length * secondsPerSlide;
        
        track.style.setProperty('--scroll-width', `${totalWidth}px`);
        track.style.setProperty('--scroll-duration', `${duration}s`);
    }

    // Update on load and resize
    updateAnimationVariables();
    window.addEventListener('resize', updateAnimationVariables);
});
</script>

</body>
</html>