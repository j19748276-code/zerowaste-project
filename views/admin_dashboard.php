<?php 
$pageTitle = 'Tableau de Bord';
require_once 'admin_header.php'; 
?>

<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-6">Tableau de Bord</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Projets Totaux</p>
                <p class="text-3xl font-bold"><?= $projectCount ?? 0 ?></p>
            </div>
            <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Utilisateurs Inscrits</p>
                <p class="text-3xl font-bold"><?= $userCount ?? 0 ?></p>
            </div>
            <div class="bg-green-100 text-green-600 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Catégories Actives</p>
                <p class="text-3xl font-bold"><?= $categoryCount ?? 0 ?></p>
            </div>
            <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
            </div>
        </div>
    </div>

    <div class="mt-10 grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4">Activité Récente</h2>
            <div class="space-y-4">
                <?php if (empty($recentProjects)): ?>
                    <p class="text-gray-500">Aucune activité récente à afficher.</p>
                <?php else: ?>
                    <?php foreach($recentProjects as $project): ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-semibold"><?= htmlspecialchars($project['titre']) ?></p>
                                <p class="text-sm text-gray-500">par <?= htmlspecialchars($project['auteur_nom']) ?></p>
                            </div>
                            <span class="text-xs text-gray-400">Nouveau</span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4">Top Contributeurs</h2>
            <div class="space-y-3">
                 <?php if (empty($topContributors)): ?>
                    <p class="text-gray-500">Aucun contributeur à afficher.</p>
                <?php else: ?>
                    <?php foreach($topContributors as $index => $contributor): ?>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-gray-400 w-5">#<?= $index + 1 ?></span>
                                <p class="font-medium"><?= htmlspecialchars($contributor['username']) ?></p>
                            </div>
                            <p class="font-bold text-brand-green"><?= $contributor['project_count'] ?> projet(s)</p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

</main>
</body>
</html>


