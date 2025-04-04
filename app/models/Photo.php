<?php
class Photo {
    private $conn;
    private $table_name = "photos";

    public $id;
    public $filename;
    public $uploaded_by;
    public $created_at;
    public $approved;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET filename=:filename, uploaded_by=:uploaded_by, created_at=:created_at, approved=:approved";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize inputs
        $this->filename = htmlspecialchars(strip_tags($this->filename));
        $this->uploaded_by = htmlspecialchars(strip_tags($this->uploaded_by));
        $this->created_at = date('Y-m-d H:i:s');
        $this->approved = 0; // Default to not approved
        
        // Bind values
        $stmt->bindParam(":filename", $this->filename);
        $stmt->bindParam(":uploaded_by", $this->uploaded_by);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":approved", $this->approved);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readPending() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE approved = 0 ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readApproved() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE approved = 1 ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function approve() {
        $query = "UPDATE " . $this->table_name . " SET approved = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function reject() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}