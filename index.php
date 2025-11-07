<?php
// DEV: temporarily show all PHP errors to help debugging — remove when done
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// index.php (modified)
// Front controller bootstrap: initialize secure session and helpers

// Initialize session securely using our Session helper
require_once __DIR__ . '/libraries/session.php';
Session::init();

// Make CSRF helper available globally (controllers/views can use it)
require_once __DIR__ . '/libraries/csrf.php';

// --- SIMULATE ADMIN LOGIN ---
// To test the back-office, we will log in as the user with the 'admin' role.
// Use Session helper and also set raw $_SESSION for compatibility with existing code.

// FIX: Changed user_id from 3 to 1, because user 3 does not exist in zerowaste_db (2).sql
Session::set('user_id', 1); 
Session::set('username', 'sanad zhioua (Admin-Test)'); // You can change this name
Session::set('user_role', 'admin'); // We assume user 1 should be an admin for testing

// Keep compatibility for controllers/views that access $_SESSION directly
$_SESSION['user_id'] = Session::get('user_id');
$_SESSION['username'] = Session::get('username');
$_SESSION['user_role'] = Session::get('user_role');

// --- To test as a regular user, comment out the lines above and uncomment the lines below ---
// Session::set('user_id', 2); // User 2 is 'Test User'
// Session::set('username', 'You (Test User)');
// Session::set('user_role', 'user');
// $_SESSION['user_id'] = Session::get('user_id');
// $_SESSION['username'] = Session::get('username');
// $_SESSION['user_role'] = Session::get('user_role');


require_once __DIR__ . '/controllers/ProjetController.php';
require_once __DIR__ . '/controllers/AdminController.php';

$action = $_GET['action'] ?? 'landing';

if (strpos($action, 'admin') === 0) {
    $controller = new AdminController();

    switch($action) {
        case 'adminDashboard':
            $controller->dashboard();
            break;
        case 'adminManageProjects':
            $controller->manageProjects();
            break;
        case 'adminManageUsers':
            $controller->manageUsers();
            break;
        case 'adminEditUser':
            $controller->editUser();
            break;
        case 'adminUpdateUser':
            $controller->updateUser();
            break;
        case 'adminDeleteUser':
            $controller->deleteUser();
            break;
        case 'adminManageCategories':
            $controller->manageCategories();
            break;
        case 'adminStoreCategory':
            $controller->storeCategory();
            break;
        case 'adminEditCategory':
            $controller->editCategory();
            break;
        case 'adminUpdateCategory':
            $controller->updateCategory();
            break;
        case 'adminDeleteCategory':
            $controller->deleteCategory();
            break;
        default:
            $controller->dashboard();
            break;
    }
} else {
    $controller = new ProjetController();

    switch ($action) {
        case 'landing':
            $controller->showLandingPage();
            break;
        case 'listProjects':
            $controller->listAll();
            break;
        case 'myProjects':
            $controller->myProjects();
            break;
        case 'showProject':
            $id = (int)($_GET['id'] ?? 0);
            if ($id > 0) { $controller->show($id); }
            break;
        case 'createProject':
            $controller->create();
            break;
        case 'storeProject':
            $controller->store();
            break;
        case 'deleteProject':
            $id = (int)($_REQUEST['id'] ?? 0);
            if ($id > 0) { $controller->delete($id); }
            break;
        case 'editProject':
            $id = (int)($_GET['id'] ?? 0);
            if ($id > 0) { $controller->edit($id); }
            break;
        case 'updateProject':
            $controller->update();
            break;
        case 'logout':
            $controller->logout();
            break;
        default:
            $controller->showLandingPage();
            break;
    }
}