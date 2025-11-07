<?php 
$pageTitle = "Modifier : " . htmlspecialchars($projet['titre'] ?? '', ENT_QUOTES, 'UTF-8');
require_once 'header.php';

// Initialize Session helper or native session
if (class_exists('Session')) {
    if (method_exists('Session', 'init')) {
        Session::init();
    }
} else {
    if (session_status() === PHP_SESSION_NONE) session_start();
}

// FLASH retrieval
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

// Fallback values using POST first then $projet
$val = function($key, $fallback='') use ($projet) {
    if (isset($_POST[$key])) return $_POST[$key];
    return $projet[$key] ?? $fallback;
};

// Handle steps: if form was submitted and failed, use POST data. Otherwise, use data from DB.
$steps = $_POST['steps'] ?? array_map(function($step) { return $step['description']; }, $projet['steps'] ?? []);

?>

<style>
.step-input-group {
    display: flex;
    gap: 0.75rem;
    align-items: flex-start;
    animation: fadeIn 0.3s ease;
}
.step-input-group textarea {
    flex-grow: 1;
}
.step-input-group .btn-remove-step {
    flex-shrink: 0;
    margin-top: 0.5rem;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<div class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto bg-white p-8 md:p-10 rounded-2xl shadow-lg">
        
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Modifier Votre Projet</h1>
            <p class="text-gray-500 mt-2">Mettez à jour les détails de votre idée et continuez d'inspirer !</p>
        </div>

        <?php if (!empty($flash)): ?>
            <div class="mb-6 p-4 rounded-lg <?php echo $flashColor === 'red' ? 'bg-red-50 border-red-200 text-red-800' : 'bg-green-50 border-green-200 text-green-800'; ?>">
                <?= esc($flash) ?>
            </div>
        <?php endif; ?>

        <form id="editProjectForm" action="index.php?action=updateProject" method="POST" enctype="multipart/form-data" novalidate autocomplete="off">
            <?php if (class_exists('Csrf') && method_exists('Csrf', 'input')): ?>
                <?= Csrf::input(); ?>
            <?php endif; ?>

            <input type="hidden" name="id" value="<?= (int)($projet['id'] ?? 0) ?>">

            <div class="space-y-6">
                <div>
                    <label for="titre" class="block text-sm font-medium text-gray-700 mb-1">Titre du Projet</label>
                    <div class="relative">
                        <input type="text" id="titre" name="titre" value="<?= esc($val('titre')) ?>" class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" required minlength="3" aria-describedby="titreHelp">
                        <div id="titre-validation-icon" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
                            <!-- Green checkmark icon -->
                            <svg id="titre-valid-icon" class="w-5 h-5 text-green-500 hidden" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <!-- Red X icon -->
                            <svg id="titre-invalid-icon" class="w-5 h-5 text-red-500 hidden" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <p id="titreHelp" class="mt-1 text-xs text-gray-500">Minimum 3 caractères.</p>
                </div>

                <div>
                    <label for="materiel_principal" class="block text-sm font-medium text-gray-700 mb-1">Objet principal à transformer</label>
                    <input type="text" id="materiel_principal" name="materiel_principal" value="<?= esc($val('materiel_principal')) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" required>
                </div>

                <div>
                    <label for="id_categorie" class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                    <select id="id_categorie" name="id_categorie" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" required>
                        <?php foreach ($categories as $categorie): ?>
                            <option value="<?= esc($categorie['id']) ?>" <?= ((isset($_POST['id_categorie']) ? $_POST['id_categorie'] : ($projet['id_categorie'] ?? '')) == $categorie['id']) ? 'selected' : '' ?>>
                                <?= esc($categorie['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="summary" class="block text-sm font-medium text-gray-700 mb-1">Résumé du Projet</label>
                    <textarea id="summary" name="summary" rows="3" placeholder="Un bref résumé de votre projet..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" required><?= esc($val('summary')) ?></textarea>
                </div>

                <!-- Section dynamique pour les étapes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Instructions (Étape par étape)</label>
                    <div id="steps-container" class="space-y-4">
                        <?php if (empty($steps)): ?>
                            <!-- Ajouter une étape vide par défaut si aucune n'existe -->
                            <div class="step-input-group">
                                <span class="step-number text-lg font-bold text-gray-500 pt-2">1.</span>
                                <textarea name="steps[]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" placeholder="Décrivez cette étape..." required></textarea>
                                <button type="button" class="btn-remove-step px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200" style="display: none;">&times;</button>
                            </div>
                        <?php else: ?>
                            <?php foreach ($steps as $index => $step_text): ?>
                                <div class="step-input-group">
                                    <span class="step-number text-lg font-bold text-gray-500 pt-2"><?= $index + 1 ?>.</span>
                                    <textarea name="steps[]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" placeholder="Décrivez cette étape..." required><?= esc($step_text) ?></textarea>
                                    <button type="button" class="btn-remove-step px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200" <?= $index === 0 ? 'style="display: none;"' : '' ?>>&times;</button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" id="add-step-btn" class="mt-4 text-sm font-medium text-green-600 hover:text-green-800">+ Ajouter une étape</button>
                </div>
                <!-- Fin section dynamique -->


                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Photo Actuelle</label>
                    <div class="mb-4">
                        <?php if (!empty($projet['cover_image_url'])): ?>
                            <img id="currentImage" src="uploads/<?= esc($projet['cover_image_url']) ?>" alt="Photo actuelle" class="w-48 h-auto rounded-lg">
                        <?php else: ?>
                            <div class="w-48 h-32 bg-gray-100 rounded-lg flex items-center justify-center text-sm text-gray-500">Aucune image</div>
                        <?php endif; ?>
                    </div>

                    <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-1">Changer la photo (optionnel)</label>
                    <input type="file" id="cover_image" name="cover_image" accept="image/jpeg,image/png,image/webp" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                    <p class="mt-2 text-xs text-gray-500">Formats acceptés: JPG, PNG, WEBP — Taille max: 3MB.</p>

                    <div id="previewContainer" class="mt-4 hidden" aria-live="polite">
                        <p class="text-sm font-medium text-gray-700 mb-2">Aperçu de la nouvelle image :</p>
                        <img id="previewImage" src="#" alt="Preview" class="max-w-full h-auto rounded-lg shadow-sm" />
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t flex justify-end gap-4">
                <a href="index.php?action=showProject&id=<?= (int)($projet['id'] ?? 0) ?>" class="px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100">
                    Annuler
                </a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-transform transform hover:scale-105">
                    Mettre à Jour
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function(){
    // --- Real-time Title Validation ---
    const titreInput = document.getElementById('titre');
    const validIcon = document.getElementById('titre-valid-icon');
    const invalidIcon = document.getElementById('titre-invalid-icon');
    const iconContainer = document.getElementById('titre-validation-icon');
    
    const validateTitle = () => {
        const value = titreInput.value.trim();
        
        if (value.length >= 3) {
            // Show green checkmark
            validIcon.classList.remove('hidden');
            invalidIcon.classList.add('hidden');
            iconContainer.classList.remove('hidden');
            titreInput.classList.remove('border-red-400');
            titreInput.classList.add('border-green-400');
        } else if (value.length > 0) {
            // Show red X
            validIcon.classList.add('hidden');
            invalidIcon.classList.remove('hidden');
            iconContainer.classList.remove('hidden');
            titreInput.classList.add('border-red-400');
            titreInput.classList.remove('border-green-400');
        } else {
            // Hide all icons when empty
            iconContainer.classList.add('hidden');
            titreInput.classList.remove('border-red-400', 'border-green-400');
        }
    };
    
    // Validate on keyup (as user types)
    titreInput.addEventListener('keyup', validateTitle);
    
    // Validate on blur (when user clicks away)
    titreInput.addEventListener('blur', () => {
        const value = titreInput.value.trim();
        if (value.length > 0 && value.length < 3) {
            // Show red X if user leaves field with insufficient characters
            validIcon.classList.add('hidden');
            invalidIcon.classList.remove('hidden');
            iconContainer.classList.remove('hidden');
            titreInput.classList.add('border-red-400');
        }
    });

    // --- Step Management ---
    const stepsContainer = document.getElementById('steps-container');
    const addStepBtn = document.getElementById('add-step-btn');

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
            <span class="step-number text-lg font-bold text-gray-500 pt-2"></span>
            <textarea name="steps[]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" placeholder="Décrivez cette étape..." required></textarea>
            <button type="button" class="btn-remove-step px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200">&times;</button>
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

    // Add remove functionality to existing steps
    stepsContainer.querySelectorAll('.btn-remove-step').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.target.closest('.step-input-group').remove();
            updateStepNumbers();
        });
    });

    // Initial update on page load
    updateStepNumbers();


    // --- Image Preview ---
    const fileInput = document.getElementById('cover_image');
    const preview = document.getElementById('previewImage');
    const container = document.getElementById('previewContainer');
    const form = document.getElementById('editProjectForm');

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
            return false;
        }
        
        const summary = document.getElementById('summary').value.trim();
        if (summary.length < 1){
            e.preventDefault();
            alert('Le résumé ne peut pas être vide.');
            return false;
        }
        
        const steps = stepsContainer.querySelectorAll('textarea');
        let allStepsValid = true;
        steps.forEach(step => {
            if (step.value.trim().length === 0) {
                allStepsValid = false;
            }
        });
        
        if (!allStepsValid || steps.length === 0) {
            e.preventDefault();
            alert('Vous devez avoir au moins une étape, et aucune étape ne peut être vide.');
            return false;
        }
    });
})();
</script>

</body>
</html>