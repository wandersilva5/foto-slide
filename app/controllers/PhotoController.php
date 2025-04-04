<?php
class PhotoController {
    private $db;
    private $photo;
    
    public function __construct() {
        require_once __DIR__ . '/../config/Database.php';
        require_once __DIR__ . '/../models/Photo.php';
        
        $database = new Database();
        $this->db = $database->getConnection();
        $this->photo = new Photo($this->db);
    }
    
    public function showCapturePage() {
        require_once __DIR__ . '/../views/photo/capture.php';
    }
    
    public function uploadPhoto() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle file upload
            if(isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                $allowed = array('jpg', 'jpeg', 'png');
                $filename = $_FILES['photo']['name'];
                $filetype = pathinfo($filename, PATHINFO_EXTENSION);
                
                if(in_array(strtolower($filetype), $allowed)) {
                    // Generate unique filename
                    $new_filename = md5(time() . $filename) . '.' . $filetype;
                    $upload_path = __DIR__ . '/../../public/img/uploads/' . $new_filename;
                    
                    if(move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                        // Save to database
                        $this->photo->filename = $new_filename;
                        $this->photo->uploaded_by = $_SERVER['REMOTE_ADDR']; // Use IP as identifier
                        
                        if($this->photo->create()) {
                            require_once __DIR__ . '/../views/photo/success.php';
                            return;
                        }
                    }
                }
            }
            // If we got here, something went wrong
            echo "Erro ao enviar a foto. Tente novamente.";
        }
    }
}