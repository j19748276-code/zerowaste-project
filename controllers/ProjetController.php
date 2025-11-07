<?php

require_once __DIR__ . '/../libraries/session.php';
require_once __DIR__ . '/../libraries/csrf.php';
require_once __DIR__ . '/../libraries/Database.php';
require_once __DIR__ . '/../models/ProjetUpcycling.php';
require_once __DIR__ . '/../models/Categorie.php';

class ProjetController {

    private $db;

    public function __construct() {
        if (class_exists('Session') && method_exists('Session', 'init')) {
            Session::init();
        } else {
            if (session_status() === PHP_SESSION_NONE) session_start();
        }
        
        $this->db = Database::getInstance();
    }

    private function detectUploadMime(string $tmpPath): string
    {
        if (function_exists('finfo_open')) {
            try {
                $f = finfo_open(FILEINFO_MIME_TYPE);
                if ($f !== false) {
                    $m = finfo_file($f, $tmpPath);
                    finfo_close($f);
                    if (is_string($m) && $m !== '') return $m;
                }
            } catch (Throwable $e) { }
        }
        if (function_exists('mime_content_type')) {
            try {
                $m = mime_content_type($tmpPath);
                if (is_string($m) && $m !== '') return $m;
            } catch (Throwable $e) { }
        }
        if (function_exists('getimagesize')) {
            $info = @getimagesize($tmpPath);
            if ($info && isset($info['mime']) && is_string($info['mime'])) return $info['mime'];
        }
        return '';
    }

    public function showLandingPage() {
        $projetsRecents = ProjetUpcycling::findRecent($this->db, 6);
        require_once __DIR__ . '/../views/landing.php';
    }

    public function listAll() {
        $searchTerm = $_GET['search'] ?? '';
        $projets = ProjetUpcycling::findAll($this->db, $searchTerm);
        $categories = Categorie::findAll($this->db);
        require_once __DIR__ . '/../views/list_projects.php';
    }
    
    public function myProjects() {
        $userId = class_exists('Session') && is_callable(['Session', 'get']) ? Session::get('user_id') : ($_SESSION['user_id'] ?? null);
        if (!$userId) {
            header('Location: index.php?action=landing');
            exit;
        }

        $projets = ProjetUpcycling::findByUser($this->db, $userId);
        require_once __DIR__ . '/../views/my_projects.php';
    }

    public function show(int $id) {
        $projet = ProjetUpcycling::findById($this->db, $id);
        if (!$projet) {
            header("HTTP/1.0 404 Not Found");
            echo "Project not found.";
            exit;
        }
        require_once __DIR__ . '/../views/show_project.php';
    }

    public function create() {
        $userId = class_exists('Session') && is_callable(['Session','get']) ? Session::get('user_id') : ($_SESSION['user_id'] ?? null);
        if (!$userId) {
            header('Location: index.php?action=landing');
            exit;
        }
        
        $categories = Categorie::findAll($this->db);
        require_once __DIR__ . '/../views/create_project.php';
    }

    private function handleFileUpload(array $imageFile): ?string
    {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) { @mkdir($uploadDir, 0755, true); }

        if ($imageFile['error'] !== UPLOAD_ERR_OK) {
            Session::set('flash_message','Erreur: image manquante ou erreur lors de l\'upload.');
            Session::set('flash_message_color', 'red');
            return null;
        }

        $maxBytes = 3 * 1024 * 1024;
        if ($imageFile['size'] > $maxBytes) {
            Session::set('flash_message','Fichier trop volumineux (max 3MB).');
            Session::set('flash_message_color', 'red');
            return null;
        }

        $mime = $this->detectUploadMime($imageFile['tmp_name']);
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        if (!array_key_exists($mime, $allowed)) {
            Session::set('flash_message','Type de fichier non autorisé. Utilisez jpg/png/webp.');
            Session::set('flash_message_color', 'red');
            return null;
        }

        $ext = $allowed[$mime];
        $imageName = bin2hex(random_bytes(12)) . '.' . $ext;
        $targetPath = $uploadDir . $imageName;

        if (!move_uploaded_file($imageFile['tmp_name'], $targetPath)) {
            Session::set('flash_message','Erreur: impossible d\'enregistrer l\'image.');
            Session::set('flash_message_color', 'red');
            return null;
        }
        @chmod($targetPath, 0644);
        return $imageName;
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=landing');
            exit;
        }
        $userId = class_exists('Session') && is_callable(['Session','get']) ? Session::get('user_id') : ($_SESSION['user_id'] ?? null);
        if (!$userId) { header('Location: index.php?action=landing'); exit; }

        if (class_exists('Csrf') && is_callable(['Csrf','validate'])) {
            if (!Csrf::validate($_POST['csrf'] ?? null)) {
                http_response_code(400); die('Invalid CSRF token.');
            }
        }

        $titre = trim($_POST['titre'] ?? '');
        $summary = trim($_POST['summary'] ?? '');
        $materiel = trim($_POST['materiel_principal'] ?? '');
        $id_categorie = (int)($_POST['id_categorie'] ?? 0);
        $steps = (array)($_POST['steps'] ?? []);
        $filteredSteps = array_filter(array_map('trim', $steps));

        if ($id_categorie === 0) {
            Session::set('flash_message', 'Erreur : Vous devez sélectionner une catégorie valide.'); 
            Session::set('flash_message_color', 'red');
            header('Location: index.php?action=createProject'); 
            exit;
        }
        
        if (empty($summary)) {
            Session::set('flash_message', 'Erreur : Le résumé ne peut pas être vide.'); 
            Session::set('flash_message_color', 'red');
            header('Location: index.php?action=createProject'); 
            exit;
        }
        
        if (empty($filteredSteps)) {
            Session::set('flash_message', 'Erreur : Vous devez ajouter au moins une étape d\'instruction.'); 
            Session::set('flash_message_color', 'red');
            header('Location: index.php?action=createProject'); 
            exit;
        }

        if (strlen($titre) < 3) {
            Session::set('flash_message', 'Le titre doit contenir au moins 3 caractères.');
            Session::set('flash_message_color', 'red');
            header('Location: index.php?action=createProject'); exit;
        }

        $imageName = $this->handleFileUpload($_FILES['cover_image'] ?? []);
        if ($imageName === null) {
            header('Location: index.php?action=createProject'); exit;
        }

        try {
            $projet = new ProjetUpcycling($this->db);
            $projet->setTitre($titre);
            $projet->setSummary($summary);
            $projet->setMaterielPrincipal($materiel);
            $projet->setCoverImageUrl($imageName);
            $projet->setIdCategorie($id_categorie);
            $projet->setIdAuteur($userId);
            $projet->setSteps($filteredSteps);
            
            $projet->save();

            Session::set('flash_message', "Votre projet a été ajouté avec succès !");
            header('Location: index.php?action=myProjects'); exit;
        } catch (PDOException $e) {
            $targetPath = __DIR__ . '/../uploads/' . $imageName;
            if (file_exists($targetPath)) @unlink($targetPath);
            Session::set('flash_message','Erreur serveur: impossible d\'ajouter le projet. ' . $e->getMessage());
            Session::set('flash_message_color', 'red');
            header('Location: index.php?action=createProject'); exit;
        }
    }

    public function delete(int $id) {
        $userId = class_exists('Session') && is_callable(['Session','get']) ? Session::get('user_id') : ($_SESSION['user_id'] ?? null);
        if (!$userId) { header('Location: index.php?action=landing'); exit; }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php?action=myProjects'); exit; }
        if (class_exists('Csrf') && is_callable(['Csrf','validate'])) {
            if (!Csrf::validate($_POST['csrf'] ?? null)) {
                Session::set('flash_message','Requête invalide.');
                Session::set('flash_message_color', 'red');
                header('Location: index.php?action=myProjects'); exit;
            }
        }

        $projet = ProjetUpcycling::findByIdSimple($this->db, $id);
        
        if ($projet && $projet->getIdAuteur() == $userId) {
            $projet->delete();
            Session::set('flash_message','Le projet a été supprimé avec succès.');
        } else {
            Session::set('flash_message','Erreur : Vous n\'êtes pas autorisé à supprimer ce projet.');
            Session::set('flash_message_color', 'red');
        }
        header('Location: index.php?action=myProjects'); exit;
    }

    public function edit(int $id) {
        $userId = class_exists('Session') && is_callable(['Session','get']) ? Session::get('user_id') : ($_SESSION['user_id'] ?? null);
        if (!$userId) { header('Location: index.php?action=landing'); exit; }

        $projet = ProjetUpcycling::findByIdSimple($this->db, $id);
        
        if (!$projet || $projet->getIdAuteur() != $userId) {
            Session::set('flash_message','Erreur : Vous n\'êtes pas autorisé à modifier ce projet.');
            Session::set('flash_message_color', 'red');
            header('Location: index.php?action=listProjects'); exit;
        }
        
        $categories = Categorie::findAll($this->db);
        
        $projetArray = [
            'id' => $projet->getId(),
            'titre' => $projet->getTitre(),
            'summary' => $projet->getSummary(),
            'materiel_principal' => $projet->getMaterielPrincipal(),
            'id_categorie' => $projet->getIdCategorie(),
            'cover_image_url' => $projet->getCoverImageUrl(),
            'steps' => $projet->getSteps()
        ];
        $projet = $projetArray; 
        
        require_once __DIR__ . '/../views/edit_project.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php?action=landing'); exit; }
        $userId = class_exists('Session') && is_callable(['Session','get']) ? Session::get('user_id') : ($_SESSION['user_id'] ?? null);
        if (!$userId) { header('Location: index.php?action=landing'); exit; }

        if (class_exists('Csrf') && is_callable(['Csrf','validate'])) {
            if (!Csrf::validate($_POST['csrf'] ?? null)) { http_response_code(400); die('Invalid CSRF token.'); }
        }

        $id = (int)($_POST['id'] ?? 0);
        $projet = ProjetUpcycling::findByIdSimple($this->db, $id);

        if (!$projet || $projet->getIdAuteur() != $userId) {
            Session::set('flash_message','Erreur : Opération non autorisée.');
            Session::set('flash_message_color', 'red');
            header('Location: index.php?action=listProjects'); exit;
        }

        $titre = trim($_POST['titre'] ?? '');
        $summary = trim($_POST['summary'] ?? '');
        $materiel_principal = trim($_POST['materiel_principal'] ?? '');
        $id_categorie = (int)($_POST['id_categorie'] ?? 0);
        $steps = (array)($_POST['steps'] ?? []);
        $filteredSteps = array_filter(array_map('trim', $steps));

        if ($id_categorie === 0) {
            Session::set('flash_message', 'Erreur : Vous devez sélectionner une catégorie valide.');
            Session::set('flash_message_color', 'red');
            header('Location: index.php?action=editProject&id=' . $id); 
            exit;
        }
        
        if (empty($summary)) {
            Session::set('flash_message', 'Erreur : Le résumé ne peut pas être vide.');
            Session::set('flash_message_color', 'red');
            header('Location: index.php?action=editProject&id=' . $id); 
            exit;
        }
        
        if (empty($filteredSteps)) {
            Session::set('flash_message', 'Erreur : Vous devez ajouter au moins une étape d\'instruction.'); 
            Session::set('flash_message_color', 'red');
            header('Location: index.php?action=editProject&id=' . $id); 
            exit;
        }

        $imageName = $projet->getCoverImageUrl();
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $newImageName = $this->handleFileUpload($_FILES['cover_image']);
            if ($newImageName !== null) {
                $oldImagePath = __DIR__ . '/../uploads/' . $imageName;
                if (file_exists($oldImagePath)) { @unlink($oldImagePath); }
                $imageName = $newImageName;
            } else {
                header('Location: index.php?action=editProject&id=' . $id); exit;
            }
        }

        try {
            $projet->setTitre($titre);
            $projet->setSummary($summary);
            $projet->setMaterielPrincipal($materiel_principal);
            $projet->setIdCategorie($id_categorie);
            $projet->setCoverImageUrl($imageName);
            $projet->setSteps($filteredSteps);
            
            $projet->update();

            Session::set('flash_message','Le projet a été mis à jour avec succès !');
            header('Location: index.php?action=showProject&id=' . $id);
            exit;
        } catch (PDOException $e) {
            Session::set('flash_message','Erreur serveur: impossible de mettre à jour le projet.');
            Session::set('flash_message_color', 'red');
            header('Location: index.php?action=editProject&id=' . $id);
            exit;
        }
    }

    public function logout() {
        if (class_exists('Session') && is_callable(['Session','destroy'])) {
            Session::destroy();
        } else {
            session_destroy();
        }
        header('Location: index.php?action=landing');
        exit;
    }
}