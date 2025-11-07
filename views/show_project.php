<?php 
$pageTitle = htmlspecialchars($projet['titre']);
require_once 'header.php'; 
?>
<div class="container mx-auto px-6 py-12">

    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        
        <div class="w-full">
            <img class="w-full h-96 object-cover" src="uploads/<?= htmlspecialchars($projet['cover_image_url']) ?>" alt="Photo de <?= htmlspecialchars($projet['titre']) ?>">
        </div>

        <div class="p-8 md:p-12">
            
            <div class="mb-6">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="inline-block bg-green-100 text-green-800 font-semibold px-4 py-1 rounded-full text-sm mb-3">
                            Catégorie : <?= htmlspecialchars($projet['categorie_nom']) ?>
                        </span>
                        <h1 class="text-4xl md:text-5xl font-bold text-gray-900">
                            <?= htmlspecialchars($projet['titre']) ?>
                        </h1>
                    </div>
                    <div class="text-right flex-shrink-0 ml-4">
                        <p class="text-sm text-gray-500">Proposé par :</p>
                        <p class="font-bold text-gray-800"><?= htmlspecialchars($projet['auteur_nom']) ?></p>
                    </div>
                </div>
            </div>

            <div class="mb-8 border-t pt-6">
                 <h2 class="text-xl font-semibold text-gray-700 mb-2">Objet principal à transformer :</h2>
                 <p class="text-lg text-gray-600 bg-gray-100 p-4 rounded-lg">
                    <?= htmlspecialchars($projet['materiel_principal']) ?>
                 </p>
            </div>
            
            <div class="prose max-w-none">
                <h2 class="text-xl font-semibold text-gray-700 mb-2">Résumé du projet :</h2>
                <p class="text-gray-600 leading-relaxed">
                    <?= nl2br(htmlspecialchars($projet['summary'])) ?>
                </p>
            </div>

            <div class="mt-10 border-t pt-6">
                <h2 class="text-3xl font-bold text-gray-800 mb-6" style="font-family: 'Ubuntu', sans-serif;">Instructions Étape par Étape</h2>
                <div class="space-y-4">
                    <?php if (empty($projet['steps'])): ?>
                        <p class="text-gray-500">Aucune instruction étape par étape n'a été fournie pour ce projet.</p>
                    <?php else: ?>
                        <?php foreach ($projet['steps'] as $index => $step): ?>
                            <div class="step-item">
                                <div class="step-number">
                                    <?= $index + 1 ?>
                                </div>
                                <div class="step-content">
                                    <p><?= nl2br(htmlspecialchars($step['description'])) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mt-12 border-t pt-8 flex flex-wrap justify-between items-center gap-4">
                <a href="index.php?action=listProjects" class="text-gray-600 hover:text-brand-green font-semibold transition-colors">
                    &larr; Retour au catalogue
                </a>
                
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $projet['id_auteur']): ?>
                <div class="flex items-center gap-4">
                    <a href="index.php?action=editProject&id=<?= htmlspecialchars($projet['id']) ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-transform transform hover:scale-105">
                        Modifier
                    </a>

                    <form id="delete-form" action="index.php?action=deleteProject" method="POST" style="display: none;">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($projet['id']) ?>">
                    </form>
                    
                    <button type="button" onclick="openConfirmationModal('index.php?action=deleteProject', '<?= htmlspecialchars($projet['id']) ?>')" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-transform transform hover:scale-105">
                        Supprimer
                    </button>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const stepItems = document.querySelectorAll('.step-item');
    if (!stepItems.length) return;

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15, rootMargin: '0px 0px -10% 0px' });

    stepItems.forEach((item, index) => {
        item.style.transitionDelay = `${index * 120}ms`;
        observer.observe(item);
    });
});
</script>

</body>
</html>