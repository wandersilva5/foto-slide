<?php
class SlideController {
    private $db;
    private $photo;
    
    public function __construct() {
        require_once __DIR__ . '/../config/Database.php';
        require_once __DIR__ . '/../models/Photo.php';
        
        $database = new Database();
        $this->db = $database->getConnection();
        $this->photo = new Photo($this->db);
    }
    
    public function showSlideshow() {
        require_once __DIR__ . '/../views/slide/slideshow.php';
    }
    
    public function getApprovedPhotos() {
        // Get approved photos
        $stmt = $this->photo->readApproved();
        $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($photos);
    }
}