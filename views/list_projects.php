<?php 
$pageTitle = "Catalogue d'Upcycling";
require_once 'header.php'; 
?>
<style>
.filter-btn {
    padding: 0.5rem 1.25rem;
    border-radius: 99px;
    border: 1px solid #27ae60;
    color: #27ae60;
    background-color: white;
    font-weight: 500;
    transition: all 0.2s ease;
    cursor: pointer;
}
.filter-btn:hover {
    background-color: #e8f5e9;
    transform: translateY(-2px);
}
.filter-btn.active {
    background-color: #27ae60;
    color: white;
    box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
}
.project-card {
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1), 
                opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1),
                max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    max-height: 1000px;
    overflow: hidden;
}
.project-card.hiding {
    transform: scale(0.85);
    opacity: 0;
    max-height: 0;
    margin: 0;
    padding: 0;
}
.project-card.hidden {
    display: none;
}
.project-card.showing {
    animation: fadeInScale 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}
@keyframes fadeInScale {
    0% {
        opacity: 0;
        transform: scale(0.85) translateY(20px);
    }
    100% {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}
</style>

<div class="container mx-auto px-6 py-12">

    <header class="text-center mb-12 reveal">
        <h1 class="text-4xl md:text-5xl font-bold text-brand-green">Explorer les Idées</h1>
        <p class="mt-4 text-lg text-gray-600">Trouvez l'inspiration parmi les créations durables de notre communauté.</p>
    </header>

    <div class="bg-white p-6 rounded-xl shadow-md mb-8 max-w-4xl mx-auto reveal">
        <form action="index.php" method="GET">
            <input type="hidden" name="action" value="listProjects">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label for="search" class="sr-only">Rechercher</label>
                    <input type="text" name="search" id="search" placeholder="Rechercher par mot-clé (ex: bouteille, palette...)" value="<?= htmlspecialchars($searchTerm ?? '') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-lg">
                </div>
                <button type="submit" class="w-full bg-brand-green hover:bg-opacity-80 text-white font-bold py-3 px-6 rounded-lg shadow-md text-lg transition-transform transform hover:scale-105">
                    Rechercher
                </button>
            </div>
        </form>
    </div>
    
    <div id="filter-bar" class="flex flex-wrap justify-center gap-3 mb-10 reveal">
        <button class="filter-btn active" data-filter="all">Tous</button>
        <button class="filter-btn" data-filter="Décoration">Décoration</button>
        <button class="filter-btn" data-filter="Jardin">Jardin</button>
        <button class="filter-btn" data-filter="Mode">Mode</button>
        <button class="filter-btn" data-filter="Rangement">Rangement</button>
        <button class="filter-btn" data-filter="Mobilier">Mobilier</button>
    </div>
    
    <div class="reveal">
        <?php if (!empty($searchTerm)): ?>
            <div class="text-center mb-8">
                <p class="text-lg text-gray-700">Résultats de la recherche pour : <strong class="text-brand-green">"<?= htmlspecialchars($searchTerm) ?>"</strong></p>
                <a href="index.php?action=listProjects" class="text-sm text-blue-600 hover:underline">Effacer la recherche</a>
            </div>
        <?php endif; ?>

        <?php if (empty($projets)): ?>
            <div id="no-results-message" class="text-center bg-white p-12 rounded-lg shadow-md max-w-2xl mx-auto">
                <h2 class="text-2xl font-semibold text-gray-700">Aucun projet trouvé.</h2>
                <p class="mt-4 text-gray-500">Essayez un autre mot-clé ou soyez le premier à partager une idée !</p>
            </div>
        <?php else: ?>
            <div id="project-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($projets as $projet): ?>
                    <div class="professional-card project-card" data-category-name="<?= htmlspecialchars($projet['categorie_nom']) ?>">
                        <div class="card-image-container">
                             <span class="card-category">
                                <?= htmlspecialchars($projet['categorie_nom']) ?>
                            </span>
                            <a href="index.php?action=showProject&id=<?= htmlspecialchars($projet['id']) ?>">
                                <img src="uploads/<?= htmlspecialchars($projet['cover_image_url']) ?>" alt="Photo de <?= htmlspecialchars($projet['titre']) ?>">
                            </a>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title"><?= htmlspecialchars($projet['titre']) ?></h3>
                            <p class="card-text">Base : <?= htmlspecialchars($projet['materiel_principal']) ?></p>
                            <a href="index.php?action=showProject&id=<?= htmlspecialchars($projet['id']) ?>" class="card-button">
                                Découvrir le Projet
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div id="no-results-message" class="text-center bg-white p-12 rounded-lg shadow-md max-w-2xl mx-auto hidden">
                <h2 class="text-2xl font-semibold text-gray-700">Aucun projet trouvé.</h2>
                <p class="mt-4 text-gray-500">Aucun projet ne correspond à cette catégorie.</p>
            </div>
        <?php endif; ?>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBar = document.getElementById('filter-bar');
    if (!filterBar) return;

    const filterButtons = filterBar.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.project-card');
    const noResultsMessage = document.getElementById('no-results-message');

    filterBar.addEventListener('click', function(e) {
        if (!e.target.classList.contains('filter-btn')) return;

        const filterValue = e.target.getAttribute('data-filter');

        filterButtons.forEach(btn => btn.classList.remove('active'));
        e.target.classList.add('active');

        let visibleCount = 0;
        const cardsToShow = [];
        const cardsToHide = [];

        projectCards.forEach(card => {
            const cardCategory = card.getAttribute('data-category-name');
            if (filterValue === 'all' || cardCategory === filterValue) {
                cardsToShow.push(card);
                visibleCount++;
            } else {
                cardsToHide.push(card);
            }
        });

        // Animate cards out (fade out)
        cardsToHide.forEach(card => {
            card.classList.remove('showing');
            card.classList.add('hiding');
            setTimeout(() => {
                card.classList.add('hidden');
                card.classList.remove('hiding');
            }, 500);
        });

        // Animate cards in (fade in) with staggered timing
        cardsToShow.forEach((card, index) => {
            card.classList.remove('hidden', 'hiding');
            setTimeout(() => {
                card.classList.add('showing');
                setTimeout(() => {
                    card.classList.remove('showing');
                }, 600);
            }, index * 80);
        });

        if (noResultsMessage) {
            setTimeout(() => {
                if (visibleCount === 0) {
                    noResultsMessage.classList.remove('hidden');
                } else {
                    noResultsMessage.classList.add('hidden');
                }
            }, 500);
        }
    });
});
</script>

</body>
</html>