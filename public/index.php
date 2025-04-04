<?php
// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Get controller and action from URL parameters
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'photo';
$action = isset($_GET['action']) ? $_GET['action'] : 'showCapturePage';

// Load the appropriate controller
switch($controller) {
    case 'admin':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        $controller = new AdminController();
        break;
    case 'slide':
        require_once __DIR__ . '/../app/controllers/SlideController.php';
        $controller = new SlideController();
        break;
    default:
        require_once __DIR__ . '/../app/controllers/PhotoController.php';
        $controller = new PhotoController();
        break;
}

// Call the action
if(method_exists($controller, $action)) {
    $controller->$action();
} else {
    // Default action
    $controller->showCapturePage();
}