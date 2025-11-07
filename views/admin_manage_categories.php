<?php 
$pageTitle = 'Gérer les Catégories';
require_once 'admin_header.php'; 
?>

<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-6">Gérer les Catégories</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4">Ajouter une Catégorie</h2>
                <form action="index.php?action=adminStoreCategory" method="POST">
                    <div class="space-y-4">
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom de la catégorie</label>
                            <input type="text" id="nom" name="nom" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" required>
                        </div>
                        <button type="submit" class="w-full bg-brand-green hover:bg-opacity-80 text-white font-bold py-2 px-4 rounded-lg">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4">Catégories Existantes</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b">
                                <th class="p-4">ID</th>
                                <th class="p-4">Nom</th>
                                <th class="p-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($categories)): ?>
                                <tr>
                                    <td colspan="3" class="p-4 text-center text-gray-500">Aucune catégorie n'a été trouvée.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($categories as $categorie): ?>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-4 font-medium"><?= htmlspecialchars($categorie['id']) ?></td>
                                        <td class="p-4"><?= htmlspecialchars($categorie['nom']) ?></td>
                                        <td class="p-4 text-right">
                                            <div class="flex justify-end gap-3">
                                                <a href="index.php?action=adminEditCategory&id=<?= $categorie['id'] ?>" class="Btn Btn-edit">
                                                    Edit
                                                    <svg class="svg" viewBox="0 0 512 512"><path d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"></path></svg>
                                                </a>
                                                <button onclick="openConfirmationModal('index.php?action=adminDeleteCategory', '<?= $categorie['id'] ?>')" class="Btn Btn-delete">
                                                    Delete
                                                    <svg class="svg" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M175 175C184 166 199 166 208 175L256 223L304 175C313 166 328 166 337 175C346 184 346 199 337 208L289 256L337 304C346 313 346 328 337 337C328 346 313 346 304 337L256 289L208 337C199 346 184 346 175 337C166 328 166 313 175 304L223 256L175 208C166 199 166 184 175 175Z M512 256C512 397 397 512 256 512C115 512 0 397 0 256C0 115 115 0 256 0C397 0 512 115 512 256ZM400 256C400 328 336 392 256 392C176 392 112 328 112 256C112 184 176 120 256 120C336 120 400 184 400 256Z"></path></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</main>
</body>
</html>
