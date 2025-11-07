<?php 
$pageTitle = "Partager une Idée";
require_once 'header.php'; 

// Initialize Session helper if available
if (class_exists('Session')) {
    if (method_exists('Session', 'init')) {
        Session::init();
    }
} else {
    if (session_status() === PHP_SESSION_NONE) session_start();
}

// show flash messages (compatible with Session helper or legacy $_SESSION)
$flash = null;
$flashColor = 'green';

if (class_exists('Session')) {
    $sessionClass = 'Session';
    if (is_callable([$sessionClass, 'flash'])) {
        $flash = call_user_func([$sessionClass, 'flash'], 'flash_message');
        $flashColor = call_user_func([$sessionClass, 'flash'], 'flash_message_color') ?? $flashColor;
    } else {
        if (is_callable([$sessionClass, 'get'])) {
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
    }
} else {
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        $flashColor = $_SESSION['flash_message_color'] ?? $flashColor;
        unset($_SESSION['flash_message'], $_SESSION['flash_message_color']);
    }
}


// Helper to output attributes safely
function escAttr($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }

?>

<div class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto bg-white p-8 md:p-10 rounded-2xl shadow-lg">
        
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Partagez Votre Créativité</h1>
            <p class="text-gray-500 mt-2">Donnez une seconde vie à un objet et inspirez la communauté.</p>
        </div>

        <?php if (!empty($flash)): ?>
            <div class="mb-6 p-4 rounded-lg <?php echo $flashColor === 'red' ? 'bg-red-50 border-red-200 text-red-800' : 'bg-green-50 border-green-200 text-green-800'; ?>">
                <?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form id="createProjectForm" action="index.php?action=storeProject" method="POST" enctype="multipart/form-data" novalidate autocomplete="off">
            <?php if (class_exists('Csrf') && method_exists('Csrf','input')): ?>
                <?= Csrf::input(); ?>
            <?php else: ?>
                <!-- CSRF helper missing: ensure server validates otherwise -->
            <?php endif; ?>

            <div class="space-y-6">
                <div>
                    <label for="titre" class="block text-sm font-medium text-gray-700 mb-1">Titre du Projet</label>
                    <div class="title-validation-wrapper">
                        <input type="text" id="titre" name="titre" placeholder="Ex: Lampe à partir d'une bouteille" class="w-full px-4 py-2 pr-12 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" required minlength="3" value="<?= escAttr($_POST['titre'] ?? '') ?>" aria-describedby="titreHelp">
                        <span id="titreStatus" class="title-validation-icon" aria-hidden="true"></span>
                    </div>
                    <p id="titreHelp" class="mt-1 text-xs text-gray-500">Minimum 3 caractères.</p>
                </div>

                <div>
                    <label for="materiel_principal" class="block text-sm font-medium text-gray-700 mb-1">Objet principal à transformer</label>
                    <input type="text" id="materiel_principal" name="materiel_principal" placeholder="Ex: Bouteille en verre, Vieux T-shirt, Palette en bois" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" required value="<?= escAttr($_POST['materiel_principal'] ?? '') ?>">
                </div>

                <div>
                    <label for="id_categorie" class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                    <select id="id_categorie" name="id_categorie" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" required>
                        <option value="" disabled <?= empty($_POST['id_categorie']) ? 'selected' : '' ?>>Choisissez une catégorie</option>
                        <?php foreach ($categories as $categorie): ?>
                            <option value="<?= escAttr($categorie['id']) ?>" <?= (isset($_POST['id_categorie']) && $_POST['id_categorie'] == $categorie['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($categorie['nom'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="summary" class="block text-sm font-medium text-gray-700 mb-1">Résumé du Projet</label>
                    <textarea id="summary" name="summary" rows="3" placeholder="Une courte description qui donne envie..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" required><?= htmlspecialchars($_POST['summary'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                    <p class="mt-1 text-xs text-gray-500">Ce résumé apparaîtra dans les cartes de projet. (Remplace l'ancienne description)</p>
                </div>

                <!-- Section dynamique pour les étapes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Instructions (Étape par étape)</label>
                    <div id="steps-container" class="space-y-3">
                        <div class="step-input-group">
                            <span class="step-number">1.</span>
                            <textarea name="steps[]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" placeholder="Décrivez la première étape..." required></textarea>
                            <button type="button" class="btn-remove-step" style="display: none;">&times;</button>
                        </div>
                    </div>
                    <button type="button" id="add-step-btn" class="mt-4 text-sm font-medium text-green-600 hover:text-green-800 hover:underline">+ Ajouter une étape</button>
                </div>
                <!-- Fin section dynamique -->

                <div>
                    <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-1">Photo du Résultat</label>
                    <input type="file" id="cover_image" name="cover_image" accept="image/jpeg,image/png,image/webp" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100" required aria-describedby="coverHelp">
                    <p id="coverHelp" class="mt-2 text-xs text-gray-500">Formats acceptés: JPG, PNG, WEBP — Taille max: 3MB.</p>

                    <div id="previewContainer" class="mt-4 hidden" aria-live="polite">
                        <p class="text-sm font-medium text-gray-700 mb-2">Aperçu de l'image :</p>
                        <img id="previewImage" src="#" alt="Preview" class="max-w-full h-auto rounded-lg shadow-sm" />
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t flex justify-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg shadow-md transition-transform transform hover:scale-105">
                    Soumettre le Projet
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function(){
    // --- Step Management ---
    const stepsContainer = document.getElementById('steps-container');
    const addStepBtn = document.getElementById('add-step-btn');
    const titleInput = document.getElementById('titre');
    const titleStatus = document.getElementById('titreStatus');

    const successSvg = `<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M8.292 13.708a1 1 0 0 1-1.414 0L3.88 10.71a1 1 0 1 1 1.414-1.414l2.293 2.292 6.119-6.118a1 1 0 0 1 1.414 1.414l-7.828 7.824z"/></svg>`;
    const errorSvg = `<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 1.667a8.333 8.333 0 1 1 0 16.666 8.333 8.333 0 0 1 0-16.666zm2.357 4.643L10 8.667 7.643 6.31 6.31 7.643 8.667 10 6.31 12.357l1.333 1.333L10 11.333l2.357 2.357 1.333-1.333L11.333 10l2.357-2.357-1.333-1.333z"/></svg>`;

    const updateTitleStatus = (state) => {
        if (!titleStatus) return;
        if (state === 'valid') {
            titleStatus.innerHTML = successSvg;
            titleStatus.classList.add('title-validation-icon--success', 'active');
            titleStatus.classList.remove('title-validation-icon--error');
        } else if (state === 'invalid') {
            titleStatus.innerHTML = errorSvg;
            titleStatus.classList.add('title-validation-icon--error', 'active');
            titleStatus.classList.remove('title-validation-icon--success');
        } else {
            titleStatus.innerHTML = '';
            titleStatus.classList.remove('title-validation-icon--success', 'title-validation-icon--error', 'active');
        }
    };

    if (titleInput) {
        const handleTitleKeyup = () => {
            const length = titleInput.value.trim().length;
            if (length >= 3) {
                updateTitleStatus('valid');
            } else if (titleStatus.classList.contains('title-validation-icon--error')) {
                updateTitleStatus('invalid');
            } else {
                updateTitleStatus(null);
            }
        };

        titleInput.addEventListener('keyup', handleTitleKeyup);
        titleInput.addEventListener('blur', () => {
            if (titleInput.value.trim().length < 3) {
                updateTitleStatus('invalid');
            }
        });

        handleTitleKeyup();
    }

    const updateStepNumbers = () => {
        const stepGroups = stepsContainer.querySelectorAll('.step-input-group');
        stepGroups.forEach((group, index) => {
            group.querySelector('.step-number').textContent = `${index + 1}.`;
            const removeBtn = group.querySelector('.btn-remove-step');
            // Show remove button for all but the first step
            if (removeBtn) {
                removeBtn.style.display = index === 0 ? 'none' : 'inline-flex';
            }
        });
    };

    const createStepInput = () => {
        const div = document.createElement('div');
        div.className = 'step-input-group';
        div.innerHTML = `
            <span class="step-number"></span>
            <textarea name="steps[]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" placeholder="Décrivez cette étape..." required></textarea>
            <button type="button" class="btn-remove-step">&times;</button>
        `;
        div.querySelector('.btn-remove-step').addEventListener('click', () => {
            div.remove();
            updateStepNumbers();
        });
        return div;
    };

    addStepBtn.addEventListener('click', () => {
        stepsContainer.appendChild(createStepInput());
        updateStepNumbers();
    });

    // Initial numbering
    updateStepNumbers();

    // --- Image Preview ---
    const fileInput = document.getElementById('cover_image');
    const preview = document.getElementById('previewImage');
    const container = document.getElementById('previewContainer');
    const form = document.getElementById('createProjectForm');

    fileInput.addEventListener('change', function(){
        const f = this.files[0];
        if (!f) return;
        const allowed = ['image/jpeg','image/png','image/webp'];
        if (!allowed.includes(f.type)){
            alert('Type de fichier non autorisé. Utilisez jpg/png/webp.');
            this.value = '';
            container.classList.add('hidden');
            return;
        }
        if (f.size > 3 * 1024 * 1024){
            alert('Fichier trop volumineux (max 3MB).');
            this.value = '';
            container.classList.add('hidden');
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e){
            preview.src = e.target.result;
            container.classList.remove('hidden');
        }
        reader.readAsDataURL(f);
    });

    // --- Form Validation ---
    form.addEventListener('submit', function(e){
        const title = document.getElementById('titre').value.trim();
        if (title.length < 3){
            e.preventDefault();
            alert('Le titre doit contenir au moins 3 caractères.');
            document.getElementById('titre').focus();
            return false;
        }

        const summary = document.getElementById('summary').value.trim();
        if (summary.length < 1){
            e.preventDefault();
            alert('Le résumé ne peut pas être vide.');
            document.getElementById('summary').focus();
            return false;
        }
        
        const steps = stepsContainer.querySelectorAll('textarea');
        let allStepsValid = true;
        let firstInvalidStep = null;

        if (steps.length === 0) {
            allStepsValid = false;
        } else {
            steps.forEach(step => {
                if (step.value.trim().length === 0) {
                    allStepsValid = false;
                    if (!firstInvalidStep) {
                        firstInvalidStep = step;
                    }
                }
            });
        }
        
        if (!allStepsValid) {
            e.preventDefault();
            alert('Vous devez avoir au moins une étape, et aucune étape ne peut être vide.');
            if (firstInvalidStep) {
                firstInvalidStep.focus();
            }
            return false;
        }
    });
})();
</script>

</body>
</html>