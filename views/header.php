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
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #F8F7F2; color: #2C3E50; }
        .text-brand-green { color: #27ae60; }
        .bg-brand-green { background-color: #27ae60; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Ubuntu', sans-serif; }
        html { scroll-behavior: smooth; }
        #loader-wrapper { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: #F8F7F2; display: flex; justify-content: center; align-items: center; z-index: 1000; transition: opacity 0.5s ease; }
        .loader { --main-size: 4em; --text-color: #27ae60; --shine-color: #27ae6040; --shadow-color: #bdc3c7; display: flex; justify-content: center; align-items: center; overflow: hidden; user-select: none; position: relative; font-size: var(--main-size); font-weight: 900; text-transform: uppercase; color: var(--text-color); width: 7.3em; height: 1em; filter: drop-shadow(0 0 0.05em var(--shine-color)); }
        .loader .text { display: flex; align-items: center; justify-content: center; text-align: center; white-space: nowrap; overflow: hidden; position: absolute; }
        .loader .text:nth-child(1) { clip-path: polygon(0% 0%, 11.11% 0%, 11.11% 100%, 0% 100%); font-size: calc(var(--main-size) / 20); margin-left: -2.1em; opacity: 0.6; } .loader .text:nth-child(2) { clip-path: polygon(11.11% 0%, 22.22% 0%, 22.22% 100%, 11.11% 100%); font-size: calc(var(--main-size) / 16); margin-left: -0.98em; opacity: 0.7; } .loader .text:nth-child(3) { clip-path: polygon(22.22% 0%, 33.33% 0%, 33.33% 100%, 22.22% 100%); font-size: calc(var(--main-size) / 13); margin-left: -0.33em; opacity: 0.8; } .loader .text:nth-child(4) { clip-path: polygon(33.33% 0%, 44.44% 0%, 44.44% 100%, 33.33% 100%); font-size: calc(var(--main-size) / 11); margin-left: -0.05em; opacity: 0.9; } .loader .text:nth-child(5) { clip-path: polygon(44.44% 0%, 55.55% 0%, 55.55% 100%, 44.44% 100%); font-size: calc(var(--main-size) / 10); margin-left: 0em; opacity: 1; } .loader .text:nth-child(6) { clip-path: polygon(55.55% 0%, 66.66% 0%, 66.66% 100%, 55.55% 100%); font-size: calc(var(--main-size) / 11); margin-left: 0.05em; opacity: 0.9; } .loader .text:nth-child(7) { clip-path: polygon(66.66% 0%, 77.77% 0%, 77.77% 100%, 66.66% 100%); font-size: calc(var(--main-size) / 13); margin-left: 0.33em; opacity: 0.8; } .loader .text:nth-child(8) { clip-path: polygon(77.77% 0%, 88.88% 0%, 88.88% 100%, 77.77% 100%); font-size: calc(var(--main-size) / 16); margin-left: 0.98em; opacity: 0.7; } .loader .text:nth-child(9) { clip-path: polygon(88.88% 0%, 100% 0%, 100% 100%, 88.88% 100%); font-size: calc(var(--main-size) / 20); margin-left: 2.1em; opacity: 0.6; }
        .loader .text span { animation: scrolling 2s cubic-bezier(0.1, 0.6, 0.9, 0.4) infinite, shadow 2s cubic-bezier(0.1, 0.6, 0.9, 0.4) infinite; } .loader .text:nth-child(1) span { background: linear-gradient( to right, var(--text-color) 4%, var(--shadow-color) 7% ); background-size: 200% auto; background-clip: text; color: transparent; } .loader .text:nth-child(2) span { background: linear-gradient( to right, var(--text-color) 9%, var(--shadow-color) 13% ); background-size: 200% auto; background-clip: text; color: transparent; } .loader .text:nth-child(3) span { background: linear-gradient( to right, var(--text-color) 15%, var(--shadow-color) 18% ); background-size: 200% auto; background-clip: text; color: transparent; } .loader .text:nth-child(4) span { background: linear-gradient( to right, var(--text-color) 20%, var(--shadow-color) 23% ); background-size: 200% auto; background-clip: text; color: transparent; } .loader .text:nth-child(6) span { background: linear-gradient( to right, var(--shadow-color) 29%, var(--text-color) 32% ); background-size: 200% auto; background-clip: text; color: transparent; } .loader .text:nth-child(7) span { background: linear-gradient( to right, var(--shadow-color) 34%, var(--text-color) 37% ); background-size: 200% auto; background-clip: text; color: transparent; } .loader .text:nth-child(8) span { background: linear-gradient( to right, var(--shadow-color) 39%, var(--text-color) 42% ); background-size: 200% auto; background-clip: text; color: transparent; } .loader .text:nth-child(9) span { background: linear-gradient( to right, var(--shadow-color) 45%, var(--text-color) 48% ); background-size: 200% auto; background-clip: text; color: transparent; }
        .loader .line { position: relative; display: flex; align-items: center; justify-content: center; overflow: hidden; height: 0.05em; width: calc(var(--main-size) / 2); margin-top: 0.9em; border-radius: 0.05em; }
        .loader .line::before { content: ""; position: absolute; height: 100%; width: 100%; background-color: var(--text-color); opacity: 0.3; } .loader .line::after { content: ""; position: absolute; height: 100%; width: 100%; background-color: var(--text-color); border-radius: 0.05em; transform: translateX(-90%); animation: wobble 2s cubic-bezier(0.5, 0.8, 0.5, 0.2) infinite; }
        @keyframes wobble { 0% { transform: translateX(-90%); } 50% { transform: translateX(90%); } 100% { transform: translateX(-90%); } } @keyframes scrolling { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } } @keyframes shadow { 0% { background-position: -98% 0; } 100% { background-position: 102% 0; } }
        .animated-button { position: relative; display: inline-flex; align-items: center; gap: 4px; padding: 16px 36px; border: 4px solid; border-color: transparent; font-size: 16px; background-color: #27ae60; border-radius: 100px; font-weight: 600; color: #ffffff; box-shadow: 0 0 0 2px #ffffff; cursor: pointer; overflow: hidden; transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1); white-space: nowrap; } .animated-button svg { position: absolute; width: 24px; fill: #ffffff; z-index: 9; transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1); } .animated-button .arr-1 { right: 16px; } .animated-button .arr-2 { left: -25%; } .animated-button .circle { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 20px; height: 20px; background-color: #ecf0f1; border-radius: 50%; opacity: 0; transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1); } .animated-button .text { position: relative; z-index: 1; transform: translateX(-12px); transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1); font-family: 'Ubuntu', sans-serif; } .animated-button:hover { box-shadow: 0 0 0 12px transparent; color: #2C3E50; border-radius: 12px; } .animated-button:hover .arr-1 { right: -25%; } .animated-button:hover .arr-2 { left: 16px; } .animated-button:hover .text { transform: translateX(12px); } .animated-button:hover svg { fill: #2C3E50; } .animated-button:active { scale: 0.95; box-shadow: 0 0 0 4px #27ae60; } .animated-button:hover .circle { width: 220px; height: 220px; opacity: 1; }
        .professional-card { background: white; border-radius: 1rem; box-shadow: 0 8px 16px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); display: flex; flex-direction: column; height: 100%; }
        .professional-card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(44, 62, 80, 0.12); }
        .professional-card .card-image-container { overflow: hidden; height: 240px; position: relative; }
        .professional-card .card-image-container::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 50%; background: linear-gradient(to top, rgba(0,0,0,0.5), transparent); }
        .professional-card img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
        .professional-card:hover img { transform: scale(1.08); }
        .professional-card .card-content { padding: 1.5rem; flex-grow: 1; display: flex; flex-direction: column; }
        .professional-card .card-category { position: absolute; top: 1rem; left: 1rem; background-color: rgba(39, 174, 96, 0.9); color: white; padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.75rem; font-weight: 500; }
        .professional-card .card-title { font-family: 'Ubuntu', sans-serif; font-size: 1.3rem; font-weight: 700; line-height: 1.3; color: #2C3E50; margin-bottom: auto; }
        .professional-card .card-text { color: #7f8c8d; font-size: 0.9rem; margin-top: 0.5rem; margin-bottom: 1.5rem; }
        .professional-card .card-button { display: block; width: 100%; padding: 0.75rem; background-color: #27ae60; color: white; font-weight: 700; border-radius: 0.5rem; text-align: center; transition: all 0.3s ease; }
        .professional-card .card-button:hover { background-color: #2C3E50; transform: scale(1.03); }
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(44, 62, 80, 0.6); display: flex; justify-content: center; align-items: center; z-index: 1001; opacity: 0; visibility: hidden; transition: opacity 0.3s ease, visibility 0.3s ease; }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-content { background: white; padding: 2.5rem; border-radius: 1rem; width: 90%; max-width: 450px; text-align: center; transform: scale(0.95); transition: transform 0.3s ease; }
        .modal-overlay.active .modal-content { transform: scale(1); }
        .reveal { opacity: 0; transform: translateY(60px); transition: opacity 1s cubic-bezier(0.5, 0, 0, 1), transform 1s cubic-bezier(0.5, 0, 0, 1); }
        .reveal.visible { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body class="antialiased">

    <div id="loader-wrapper"><div class="loader"><div class="text"><span>Loading</span></div><div class="text"><span>Loading</span></div><div class="text"><span>Loading</span></div><div class="text"><span>Loading</span></div><div class="text"><span>Loading</span></div><div class="text"><span>Loading</span></div><div class="text"><span>Loading</span></div><div class="text"><span>Loading</span></div><div class="text"><span>Loading</span></div><div class="line"></div></div></div>

    <nav id="main-nav" class="bg-white shadow-md sticky top-0 z-50 transition-shadow duration-300">
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
            
            // Dynamic navigation shadow on scroll
            const mainNav = document.getElementById('main-nav');
            let lastScrollY = window.scrollY;
            
            const updateNavShadow = () => {
                if (window.scrollY > 20) {
                    mainNav.style.boxShadow = '0 10px 30px rgba(44, 62, 80, 0.15)';
                } else {
                    mainNav.style.boxShadow = '0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1)';
                }
                lastScrollY = window.scrollY;
            };
            
            window.addEventListener('scroll', updateNavShadow, { passive: true });
            
            const menuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            menuButton.addEventListener('click', () => { mobileMenu.classList.toggle('hidden'); });
            const modal = document.getElementById('confirmation-modal');
            const cancelBtn = document.getElementById('cancel-delete-btn');
            const confirmForm = document.getElementById('confirm-delete-form');
            const deleteIdInput = document.getElementById('delete-id-input');
            window.openConfirmationModal = (actionUrl, deleteId) => {
                confirmForm.action = actionUrl;
                deleteIdInput.value = deleteId;
                modal.classList.add('active');
            }
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
        });
    </script>
</body>
</html>

