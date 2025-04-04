<?php
class AdminController {
    private $db;
    private $photo;
    
    public function __construct() {
        require_once __DIR__ . '/../config/Database.php';
        require_once __DIR__ . '/../models/Photo.php';
        
        $database = new Database();
        $this->db = $database->getConnection();
        $this->photo = new Photo($this->db);

        // Start session
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function showLoginPage() {
        require_once __DIR__ . '/../views/admin/login.php';
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = isset($_POST['username']) ? $_POST['username'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            
            // Hard-coded admin credentials - in production use a proper authentication system
            if($username === 'admin' && $password === 'admin123') {
                $_SESSION['admin_logged_in'] = true;
                header('Location: index.php?controller=admin&action=dashboard');
                exit;
            } else {
                echo "Login inválido. Tente novamente.";
                $this->showLoginPage();
            }
        }
    }
    
    public function dashboard() {
        // Check if admin is logged in
        if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header('Location: index.php?controller=admin&action=showLoginPage');
            exit;
        }
        
        // Get pending photos
        $stmt = $this->photo->readPending();
        $pending_photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get approved photos
        $stmt = $this->photo->readApproved();
        $approved_photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }
    
    public function approvePhoto() {
        // Check if admin is logged in
        if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            echo json_encode(['success' => false, 'message' => 'Não autorizado']);
            return;
        }
        
        if(isset($_POST['id'])) {
            $this->photo->id = $_POST['id'];
            if($this->photo->approve()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao aprovar foto']);
            }
        }
    }
    
    public function rejectPhoto() {
        // Check if admin is logged in
        if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            echo json_encode(['success' => false, 'message' => 'Não autorizado']);
            return;
        }
        
        if(isset($_POST['id'])) {
            $this->photo->id = $_POST['id'];
            if($this->photo->reject()) {
                // Also delete the file
                $filename = $_POST['filename'];
                if($filename) {
                    $file_path = __DIR__ . '/../../public/img/uploads/' . $filename;
                    if(file_exists($file_path)) {
                        unlink($file_path);
                    }
                }
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao rejeitar foto']);
            }
        }
    }
    
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit;
    }
}