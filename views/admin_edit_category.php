<?php 
$pageTitle = 'Modifier la Catégorie';
require_once 'admin_header.php'; 
?>

<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-6">Modifier la Catégorie</h1>

    <div class="bg-white p-8 rounded-lg shadow-md max-w-2xl mx-auto">
        <form action="index.php?action=adminUpdateCategory" method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($category['id']) ?>">
            
            <div class="space-y-6">
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom de la catégorie</label>
                    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($category['nom']) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" required>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t flex justify-end gap-4">
                <a href="index.php?action=adminManageCategories" class="px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100">
                    Annuler
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md">
                    Enregistrer les Modifications
                </button>
            </div>
        </form>
    </div>

</div>

</main>
</body>
</html>
