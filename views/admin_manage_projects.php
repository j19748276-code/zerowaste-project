<?php 
$pageTitle = 'Gérer les Projets';
require_once 'admin_header.php'; 

// Helper function to safely truncate multibyte strings
function truncateText($text, $length = 50) {
    if (mb_strlen($text) > $length) {
        return mb_substr($text, 0, $length) . '...';
    }
    return $text;
}
?>

<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-6">Gérer les Projets</h1>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b">
                        <th class="p-4">Titre du Projet</th>
                        <th class="p-4">Résumé</th>
                        <th class="p-4">Auteur</th>
                        <th class="p-4">Catégorie</th>
                        <th class="p-4">Nb. d'Étapes</th>
                        <th class="p-4">Date de Création</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($projets)): ?>
                        <tr>
                            <td colspan="7" class="p-4 text-center text-gray-500">Aucun projet n'a été trouvé.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($projets as $projet): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-4 font-medium"><?= htmlspecialchars($projet['titre']) ?></td>
                                <td class="p-4 text-sm text-gray-600"><?= htmlspecialchars(truncateText($projet['summary'], 40)) ?></td>
                                <td class="p-4"><?= htmlspecialchars($projet['auteur_nom']) ?></td>
                                <td class="p-4"><?= htmlspecialchars($projet['categorie_nom']) ?></td>
                                <td class="p-4">
                                    <?php if ($projet['step_count'] > 0): ?>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                            <?= htmlspecialchars($projet['step_count']) ?> étape(s)
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                            Résumé simple
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 text-sm text-gray-600"><?= date('d/m/Y', strtotime($projet['date_creation'])) ?></td>
                                <td class="p-4 text-right">
                                    <div class="flex justify-end gap-3">
                                        <a href="index.php?action=showProject&id=<?= $projet['id'] ?>" target="_blank" class="Btn Btn-view">
                                            View
                                            <svg class="svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-144c-17.7 0-32-14.3-32-32s14.3-32 32-32s32 14.3 32 32s-14.3 32-32 32z"/></svg>
                                        </a>
                                        <a href="index.php?action=editProject&id=<?= $projet['id'] ?>" class="Btn Btn-edit">
                                            Edit
                                            <svg class="svg" viewBox="0 0 512 512">
                                                <path d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"></path>
                                            </svg>
                                        </a>
                                        <button onclick="openConfirmationModal('index.php?action=deleteProject', '<?= $projet['id'] ?>')" class="Btn Btn-delete">
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

</main>
</body>
</html>