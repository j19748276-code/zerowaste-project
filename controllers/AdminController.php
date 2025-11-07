<?php

require_once __DIR__ . '/../libraries/session.php';
require_once __DIR__ . '/../libraries/csrf.php';
require_once __DIR__ . '/../libraries/Database.php';
require_once __DIR__ . '/../models/ProjetUpcycling.php';
require_once __DIR__ . '/../models/Categorie.php';
require_once __DIR__ . '/../models/User.php';

if (class_exists('Session')) {
    if (method_exists('Session', 'init')) {
        Session::init();
    }
} else {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

class AdminController {

    private $db;

    public function __construct() {
        if (class_exists('Session')) {
            $userRole = Session::get('user_role');
        } else {
            $userRole = $_SESSION['user_role'] ?? null;
        }

        if (! $userRole || $userRole !== 'admin') {
            if (class_exists('Session')) {
                Session::set('flash_message', 'Accès non autorisé.');
            } else {
                $_SESSION['flash_message'] = 'Accès non autorisé.';
            }
            header('Location: index.php');
            exit;
        }

        $this->db = Database::getInstance();
    }

    public function dashboard() {
        $projectCount = (int) $this->db->query('SELECT COUNT(*) FROM projets_upcycling')->fetchColumn();
        $userCount = (int) $this->db->query('SELECT COUNT(*) FROM users')->fetchColumn();
        $categoryCount = (int) $this->db->query('SELECT COUNT(*) FROM categories')->fetchColumn();

        $recentProjects = ProjetUpcycling::findRecent($this->db, 5);
        $topContributors = User::findTopContributors($this->db, 5);

        require_once __DIR__ . '/../views/admin_dashboard.php';
    }

    public function manageProjects() {
        $projets = ProjetUpcycling::findAll($this->db);
        require_once __DIR__ . '/../views/admin_manage_projects.php';
    }

    public function manageUsers() {
        $users = User::findAll($this->db);
        require_once __DIR__ . '/../views/admin_manage_users.php';
    }

    public function editUser() {
        $idToEdit = (int) ($_GET['id'] ?? 0);
        if ($idToEdit === 0) {
            header('Location: index.php?action=adminManageUsers');
            exit;
        }

        $user = User::findById($this->db, $idToEdit);

        if (! $user) {
            header('Location: index.php?action=adminManageUsers');
            exit;
        }

        require_once __DIR__ . '/../views/admin_edit_user.php';
    }

    public function updateUser() {
        $idToUpdate = (int) ($_POST['id'] ?? 0);
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? 'user';

        if ($idToUpdate === 0 || $username === '' || $email === '') {
            if (class_exists('Session')) {
                Session::set('flash_message', 'Erreur : Données manquantes.');
            } else {
                $_SESSION['flash_message'] = 'Erreur : Données manquantes.';
            }
            header('Location: index.php?action=adminManageUsers');
            exit;
        }

        User::update($this->db, $idToUpdate, $username, $email, $role);

        if (class_exists('Session')) {
            Session::set('flash_message', "L'utilisateur a été mis à jour avec succès.");
        } else {
            $_SESSION['flash_message'] = "L'utilisateur a été mis à jour avec succès.";
        }

        header('Location: index.php?action=adminManageUsers');
        exit;
    }

    public function deleteUser() {
        $idToDelete = (int) ($_POST['id'] ?? 0);

        if ($idToDelete === 0) {
            if (class_exists('Session')) {
                Session::set('flash_message', 'Erreur : ID utilisateur invalide.');
            } else {
                $_SESSION['flash_message'] = 'Erreur : ID utilisateur invalide.';
            }
            header('Location: index.php?action=adminManageUsers');
            exit;
        }

        $currentUserId = class_exists('Session') ? Session::get('user_id') : ($_SESSION['user_id'] ?? 0);
        if ($idToDelete === (int) $currentUserId) {
            if (class_exists('Session')) {
                Session::set('flash_message', 'Erreur : Vous ne pouvez pas supprimer votre propre compte administrateur.');
            } else {
                $_SESSION['flash_message'] = 'Erreur : Vous ne pouvez pas supprimer votre propre compte administrateur.';
            }
            header('Location: index.php?action=adminManageUsers');
            exit;
        }

        User::delete($this->db, $idToDelete);

        if (class_exists('Session')) {
            Session::set('flash_message', 'L\'utilisateur a été supprimé avec succès.');
        } else {
            $_SESSION['flash_message'] = 'L\'utilisateur a été supprimé avec succès.';
        }
        header('Location: index.php?action=adminManageUsers');
        exit;

    }

    public function manageCategories() {
        $categories = Categorie::findAll($this->db);
        require_once __DIR__ . '/../views/admin_manage_categories.php';
    }

    public function storeCategory() {
        $nom = trim($_POST['nom'] ?? '');
        if ($nom === '') {
            if (class_exists('Session')) {
                Session::set('flash_message', 'Erreur : Le nom de la catégorie ne peut pas être vide.');
            } else {
                $_SESSION['flash_message'] = 'Erreur : Le nom de la catégorie ne peut pas être vide.';
            }
            header('Location: index.php?action=adminManageCategories');
            exit;
        }

        Categorie::save($this->db, $nom);

        if (class_exists('Session')) {
            Session::set('flash_message', 'La catégorie a été ajoutée avec succès.');
        } else {
            $_SESSION['flash_message'] = 'La catégorie a été ajoutée avec succès.';
        }

        header('Location: index.php?action=adminManageCategories');
        exit;
    }

    public function editCategory() {
        $idToEdit = (int) ($_GET['id'] ?? 0);
        if ($idToEdit === 0) {
            header('Location: index.php?action=adminManageCategories');
            exit;
        }

        $category = Categorie::findById($this->db, $idToEdit);

        if (! $category) {
            header('Location: index.php?action=adminManageCategories');
            exit;
        }

        require_once __DIR__ . '/../views/admin_edit_category.php';
    }

    public function updateCategory() {
        $idToUpdate = (int) ($_POST['id'] ?? 0);
        $nom = trim($_POST['nom'] ?? '');
        if ($idToUpdate === 0 || $nom === '') {
            if (class_exists('Session')) {
                Session::set('flash_message', 'Erreur : Données manquantes.');
            } else {
                $_SESSION['flash_message'] = 'Erreur : Données manquantes.';
            }
            header('Location: index.php?action=adminManageCategories');
            exit;
        }

        Categorie::update($this->db, $idToUpdate, $nom);

        if (class_exists('Session')) {
            Session::set('flash_message', 'La catégorie a été mise à jour avec succès.');
        } else {
            $_SESSION['flash_message'] = 'La catégorie a été mise à jour avec succès.';
        }

        header('Location: index.php?action=adminManageCategories');
        exit;
    }

    public function deleteCategory() {
        $idToDelete = (int) ($_POST['id'] ?? 0);
        if ($idToDelete === 0) {
            header('Location: index.php?action=adminManageCategories');
            exit;
        }

        $projectCount = Categorie::getProjectCount($this->db, $idToDelete);

        if ($projectCount > 0) {
            $msg = 'Erreur : Impossible de supprimer cette catégorie car elle est utilisée par ' . $projectCount . ' projet(s).';
            if (class_exists('Session')) {
                Session::set('flash_message', $msg);
            } else {
                $_SESSION['flash_message'] = $msg;
            }
            header('Location: index.php?action=adminManageCategories');
            exit;
        }

        Categorie::delete($this->db, $idToDelete);

        if (class_exists('Session')) {
            Session::set('flash_message', 'La catégorie a été supprimée avec succès.');
        } else {
            $_SESSION['flash_message'] = 'La catégorie a été supprimée avec succès.';
        }

        header('Location: index.php?action=adminManageCategories');
        exit;
    }
}