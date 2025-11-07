<?php 
$pageTitle = "Mes Projets";
require_once 'header.php';

// Initialize Session helper or native session
if (class_exists('Session')) {
    if (method_exists('Session', 'init')) {
        Session::init();
    }
} else {
    if (session_status() === PHP_SESSION_NONE) session_start();
}

// FLASH retrieval (robust)
$flash = null;
$flashColor = 'green';
if (class_exists('Session')) {
    $sessionClass = 'Session';
    if (is_callable([$sessionClass, 'flash'])) {
        $flash = call_user_func([$sessionClass, 'flash'], 'flash_message');
        $flashColor = call_user_func([$sessionClass, 'flash'], 'flash_message_color') ?? $flashColor;
    } elseif (is_callable([$sessionClass, 'get'])) {
        $flash = call_user_func([$sessionClass, 'get'], 'flash_message');
        $flashColor = call_user_func([$sessionClass, 'get'], 'flash_message_color') ?? $flashColor;
        if (is_callable([$sessionClass, 'set'])) {
            call_user_func([$sessionClass, 'set'], 'flash_message', null);
            call_user_func([$sessionClass, 'set'], 'flash_message_color', null);
        }
    } else {
        if (isset($_SESSION['flash_message'])) {
            $flash = $_SESSION['flash_message'];
            $flashColor = $_SESSION['flash_message_color'] ?? $flashColor;
            unset($_SESSION['flash_message'], $_SESSION['flash_message_color']);
        }
    }
} else {
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        $flashColor = $_SESSION['flash_message_color'] ?? $flashColor;
        unset($_SESSION['flash_message'], $_SESSION['flash_message_color']);
    }
}

function esc($v){ return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }

?>

<div class="container mx-auto px-6 py-12">

    <header class="text-center mb-12 reveal">
        <h1 class="text-4xl md:text-5xl font-bold text-brand-green">Mon Tableau de Bord</h1>
        <p class="mt-4 text-lg text-gray-600">Gérez toutes les créations que vous avez partagées avec la communauté.</p>
    </header>

    <?php if (!empty($flash)): ?>
        <div class="mb-6 p-4 rounded-lg <?php echo $flashColor === 'red' ? 'bg-red-50 border-red-200 text-red-800' : 'bg-green-50 border-green-200 text-green-800'; ?> max-w-3xl mx-auto">
            <?= esc($flash) ?>
        </div>
    <?php endif; ?>

    <div class="reveal">
        <?php if (empty($projets)): ?>
            <div class="text-center bg-white p-12 rounded-lg shadow-md max-w-2xl mx-auto">
                <h2 class="text-2xl font-semibold text-gray-700">Vous n'avez pas encore partagé de projet.</h2>
                <p class="mt-4 text-gray-500">Il est temps d'inspirer la communauté ! Cliquez sur le bouton ci-dessous pour partager votre première idée.</p>
                <div class="mt-8">
                    <a href="index.php?action=createProject" class="bg-brand-green hover:bg-opacity-80 text-white font-bold py-3 px-8 rounded-full shadow-lg text-lg transition-transform transform hover:scale-105 inline-block">
                        Partager ma Première Création
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($projets as $projet): ?>
                    <div class="professional-card bg-white rounded-lg overflow-hidden shadow-md">
                        <div class="card-image-container relative">
                            <span class="card-category absolute top-3 left-3 bg-black bg-opacity-60 text-white text-xs px-3 py-1 rounded-full"> 
                                <?= esc($projet['categorie_nom']) ?>
                            </span>
                            <a href="index.php?action=showProject&id=<?= (int)$projet['id'] ?>">
                                <img src="uploads/<?= esc($projet['cover_image_url']) ?>" alt="Photo de <?= esc($projet['titre']) ?>" class="w-full h-56 object-cover">
                            </a>
                        </div>
                        <div class="card-content p-4">
                            <h3 class="card-title text-lg font-semibold text-gray-800"><?= esc($projet['titre']) ?></h3>
                            <p class="card-text text-sm text-gray-600 mt-2">Base : <?= esc($projet['materiel_principal']) ?></p>

                            <div class="mt-4 flex items-center justify-between">
                                <a href="index.php?action=showProject&id=<?= (int)$projet['id'] ?>" class="card-button text-sm font-medium text-brand-green">Voir</a>

                                <div class="flex items-center gap-3">
                                    <a href="index.php?action=editProject&id=<?= (int)$projet['id'] ?>" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-sm">Modifier</a>

                                    <!-- Delete form (POST) to match controller protection -->
                                    <form action="index.php?action=deleteProject&id=<?= (int)$projet['id'] ?>" method="POST" onsubmit="return confirmDelete(event, this);" style="display:inline-block;">
                                        <?php if (class_exists('Csrf') && method_exists('Csrf','input')): ?>
                                            <?= Csrf::input(); ?>
                                        <?php else: ?>
                                            <!-- CSRF helper missing. Ensure controller validates otherwise. -->
                                        <?php endif; ?>
                                        <button type="submit" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>

<script>
function confirmDelete(e, form){
    e.preventDefault();
    if (confirm('Confirmez-vous la suppression de ce projet ? Cette action est irréversible.')){
        form.submit();
    }
    return false;
}
</script>

</body>
</html>
