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
    <title><?= $pageTitle ?? 'ZeroWaste Upcycle' ?> - ZeroWaste Platform</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&family=Ubuntu:wght@700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="css/upcycling.css">
</head>
<body class="antialiased">

    <div id="loader-wrapper"><div class="loader"><div class="text"><span>Loading</span></div><div class="text"><span>Loading</span></div><div class="text"><span>Loading</span></div><div class="text"><span>Loading</span></div><div class="text"><span>Loading</span></div><div class="text"><span>Loading</span></div><div class="text"><span>Loading</span></div><div class="text"><span>Loading</span></div><div class="text"><span>Loading</span></div><div class="line"></div></div></div>

    <nav id="main-navbar" class="bg-white sticky top-0 z-50 transition duration-300">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <a href="index.php?action=landing" class="text-2xl font-bold flex items-center gap-2">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z" fill="#27ae60"/></svg>
                    <div><span class="text-gray-800">Zero</span><span class="text-brand-green">Waste</span></div>
                </a>
                
                <div class="hidden md:flex items-center gap-6">
                    <a href="index.php?action=listProjects" class="text-gray-600 hover:text-brand-green font-medium transition-colors">Explorer les Idées</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="index.php?action=myProjects" class="text-gray-600 hover:text-brand-green font-medium transition-colors">Mes Projets</a>
                        <a href="index.php?action=createProject" class="bg-brand-green text-white font-bold py-2 px-5 rounded-full hover:bg-opacity-80 transition shadow-md hover:shadow-lg">+ Partager</a>
                        <span class="text-gray-500">|</span>
                        <span class="font-medium text-gray-800">Bonjour, <?= htmlspecialchars($_SESSION['username']) ?></span>
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                            <a href="index.php?action=adminDashboard" class="text-sm font-bold text-red-600 hover:underline">PANNEAU ADMIN</a>
                        <?php endif; ?>
                        <a href="index.php?action=logout" class="text-sm text-red-500 hover:underline">Déconnexion</a>
                    <?php else: ?>
                        <a href="#" class="text-gray-600 hover:text-brand-green font-medium transition-colors">Connexion</a>
                    <?php endif; ?>
                </div>
                
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="focus:outline-none"><svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg></button>
                </div>
            </div>
        </div>
        
        <div id="mobile-menu" class="md:hidden hidden bg-white border-t">
            <a href="index.php?action=listProjects" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Explorer les Idées</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="index.php?action=myProjects" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Mes Projets</a>
                <a href="index.php?action=createProject" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Partager une Création</a>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <a href="index.php?action=adminDashboard" class="block py-2 px-4 text-sm text-red-600 font-bold hover:bg-gray-100">PANNEAU ADMIN</a>
                <?php endif; ?>
                <a href="index.php?action=logout" class="block py-2 px-4 text-sm text-red-500 hover:bg-gray-100">Déconnexion</a>
            <?php else: ?>
                <a href="#" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Connexion</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container mx-auto px-6 mt-4">
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div id="flash-message" class="p-4 rounded-lg shadow-md flex justify-between items-center
                <?php if (isset($_SESSION['flash_message_color']) && $_SESSION['flash_message_color'] === 'red'): ?>
                    bg-red-100 border-l-4 border-red-500 text-red-700
                <?php else: ?>
                    bg-green-100 border-l-4 border-green-500 text-green-700
                <?php endif; ?>
            " role="alert">
                <p class="font-bold"><?= htmlspecialchars($_SESSION['flash_message']) ?></p>
                <button onclick="document.getElementById('flash-message').style.display='none'">&times;</button>
            </div>
            <?php 
                unset($_SESSION['flash_message']); 
                unset($_SESSION['flash_message_color']);
            ?>
        <?php endif; ?>
    </div>

    <div id="confirmation-modal" class="modal-overlay">
        <div class="modal-content">
            <h2 class="text-2xl font-bold mb-4" style="font-family: 'Ubuntu', sans-serif;">Confirmer la Suppression</h2>
            <p class="text-gray-600 mb-8">Êtes-vous sûr de vouloir supprimer ce projet ? Cette action est irréversible.</p>
            <div class="flex justify-center gap-4">
                <button id="cancel-delete-btn" class="px-8 py-2 border border-gray-300 rounded-full text-sm font-medium text-gray-700 hover:bg-gray-100">Annuler</button>
                <form id="confirm-delete-form" action="" method="POST" style="margin:0;"><input type="hidden" id="delete-id-input" name="id" value=""><button type="submit" class="px-8 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-full shadow-md">Oui, Supprimer</button></form>
            </div>
        </div>
    </div>

    <script>
          document.addEventListener('DOMContentLoaded', () => {
              const loader = document.getElementById('loader-wrapper');
              window.addEventListener('load', () => {
                  loader.style.opacity = '0';
                  setTimeout(() => { loader.style.display = 'none'; }, 500);
              });
              const menuButton = document.getElementById('mobile-menu-button');
              const mobileMenu = document.getElementById('mobile-menu');
              menuButton.addEventListener('click', () => { mobileMenu.classList.toggle('hidden'); });
              const modal = document.getElementById('confirmation-modal');
              const cancelBtn = document.getElementById('cancel-delete-btn');
              const confirmForm = document.getElementById('confirm-delete-form');
              const deleteIdInput = document.getElementById('delete-id-input');
              const mainNavbar = document.getElementById('main-navbar');
              window.openConfirmationModal = (actionUrl, deleteId) => {
                  confirmForm.action = actionUrl;
                  deleteIdInput.value = deleteId;
                  modal.classList.add('active');
              };
              const closeModal = () => modal.classList.remove('active');
              cancelBtn.addEventListener('click', closeModal);
              modal.addEventListener('click', (e) => { if (e.target === modal) { closeModal(); } });
              const observer = new IntersectionObserver((entries) => {
                  entries.forEach(entry => {
                      if (entry.isIntersecting) {
                          entry.target.classList.add('visible');
                      }
                  });
              }, { threshold: 0.1 });
              document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
              const toggleNavbarShadow = () => {
                  if (!mainNavbar) return;
                  if (window.scrollY > 20) {
                      mainNavbar.classList.add('navbar-scrolled');
                  } else {
                      mainNavbar.classList.remove('navbar-scrolled');
                  }
              };
              toggleNavbarShadow();
              window.addEventListener('scroll', toggleNavbarShadow, { passive: true });
          });
    </script>
</body>
</html>

