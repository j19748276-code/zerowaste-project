<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin Panel' ?> - ZeroWaste Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&family=Ubuntu:wght@700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #ecf0f1; color: #2C3E50; }
        .text-brand-green { color: #27ae60; }
        .bg-brand-green { background-color: #27ae60; }
        h1, h2, h3 { font-family: 'Ubuntu', sans-serif; }

        aside {
            transition: all 0.3s ease;
        }
        
        aside nav a {
            position: relative;
            overflow: hidden;
        }
        
        aside nav a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background-color: #27ae60;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        aside nav a:hover::before,
        aside nav a.active::before {
            transform: translateX(0);
        }
        
        aside nav a svg {
            transition: transform 0.3s ease;
        }
        
        aside nav a:hover svg {
            transform: scale(1.15) rotate(5deg);
        }

        .Btn {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 100px;
            height: 35px;
            border: none;
            padding: 0px 15px;
            color: white;
            font-weight: 500;
            cursor: pointer;
            border-radius: 8px;
            transition-duration: .3s;
            font-size: 14px;
            text-decoration: none;
        }
        .Btn .svg {
            width: 13px;
            position: absolute;
            right: 15px;
            fill: white;
            transition: all .3s ease;
        }
        .Btn:hover {
            color: transparent;
        }
        .Btn:hover .svg {
            left: 50%;
            transform: translateX(-50%);
            transition-duration: .3s;
        }
        .Btn:active {
            transform: translate(2px , 2px);
            transition-duration: .3s;
        }
        .Btn-edit { background-color: #3498db; box-shadow: 3px 3px 0px #2980b9; }
        .Btn-edit:active { box-shadow: 1px 1px 0px #2980b9; }
        .Btn-delete { background-color: #e74c3c; box-shadow: 3px 3px 0px #c0392b; }
        .Btn-delete:active { box-shadow: 1px 1px 0px #c0392b; }
        .Btn-view { background-color: #95a5a6; box-shadow: 3px 3px 0px #7f8c8d; }
        .Btn-view:active { box-shadow: 1px 1px 0px #7f8c8d; }

    </style>
</head>
<body class="flex h-screen">

    <aside class="w-64 bg-gray-800 text-white flex flex-col shadow-2xl">
        <div class="p-6 text-center border-b border-gray-700">
            <h1 class="text-2xl font-bold text-white" style="animation: fadeIn 0.8s ease-out;">Admin Panel</h1>
            <p class="text-sm text-gray-400" style="animation: fadeIn 1s ease-out;">ZeroWaste Upcycle</p>
        </div>
        <nav class="flex-grow p-4 space-y-2" id="admin-nav">
            <a href="index.php?action=adminDashboard" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                <span>Tableau de bord</span>
            </a>
            <a href="index.php?action=adminManageProjects" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                <span>Gérer les Projets</span>
            </a>
            <a href="index.php?action=adminManageUsers" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                <span>Gérer les Utilisateurs</span>
            </a>
            <a href="index.php?action=adminManageCategories" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                <span>Gérer les Catégories</span>
            </a>
        </nav>
        <div class="p-4 border-t border-gray-700">
            <a href="index.php?action=landing" class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-400 hover:bg-gray-700 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 22H5a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h5"></path><polyline points="17 16 21 12 17 8"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                <span>Retour au site</span>
            </a>
        </div>
    </aside>

    <main class="flex-1 p-8 overflow-y-auto">

    <div id="confirmation-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" style="transition: all 0.3s ease;">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4 transform transition-all" style="animation: modalSlideIn 0.3s ease-out;">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Confirmer la Suppression</h2>
            <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir supprimer cet élément ? Cette action est irréversible.</p>
            <div class="flex justify-end gap-4">
                <button id="cancel-delete-btn" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">Annuler</button>
                <form id="confirm-delete-form" action="" method="POST" style="margin:0;">
                    <input type="hidden" id="delete-id-input" name="id" value="">
                    <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg transition-colors">Oui, Supprimer</button>
                </form>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes modalSlideIn {
            from { opacity: 0; transform: scale(0.9) translateY(-20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        
        .table-row-animated {
            opacity: 0;
            animation: fadeInRow 0.5s ease-out forwards;
        }
        
        .table-row-animated:nth-child(1) { animation-delay: 0.05s; }
        .table-row-animated:nth-child(2) { animation-delay: 0.1s; }
        .table-row-animated:nth-child(3) { animation-delay: 0.15s; }
        .table-row-animated:nth-child(4) { animation-delay: 0.2s; }
        .table-row-animated:nth-child(5) { animation-delay: 0.25s; }
        .table-row-animated:nth-child(n+6) { animation-delay: 0.3s; }
        
        @keyframes fadeInRow {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Confirmation Modal
            const modal = document.getElementById('confirmation-modal');
            const cancelBtn = document.getElementById('cancel-delete-btn');
            const confirmForm = document.getElementById('confirm-delete-form');
            const deleteIdInput = document.getElementById('delete-id-input');
            
            window.openConfirmationModal = (actionUrl, deleteId) => {
                confirmForm.action = actionUrl;
                deleteIdInput.value = deleteId;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            };
            
            const closeModal = () => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            };
            
            cancelBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });

            // Active nav link highlighting
            const currentPath = window.location.search;
            const navLinks = document.querySelectorAll('#admin-nav a');
            navLinks.forEach(link => {
                if (link.getAttribute('href').includes(currentPath)) {
                    link.classList.add('active', 'bg-gray-700');
                }
            });

            // Animate table rows on page load
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                row.classList.add('table-row-animated');
            });
        });
    </script>

