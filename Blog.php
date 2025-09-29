<?php
class Blog {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Create Post
    public function create($title, $content, $image) {
        $sql = "INSERT INTO posts (title, context, image) VALUES (:title, :context, :image)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['title' => $title, 'context' => $content, 'image' => $image]);
    }
    
    // Get all posts
    public function getAllPost() {
        $sql = "SELECT * FROM posts ORDER BY created_at DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Get single post by id
    public function getById($id) {
        $sql = "SELECT * FROM posts WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Delete post
    public function delete($id) {
        $sql = "DELETE FROM posts WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    
    // Update post - Fixed version
    public function update($id, $title, $content, $image = null) {
        if($image) {
            // Update with new image
            $sql = "UPDATE posts SET title = :title, context = :context, image = :image WHERE id = :id";
            $params = [':id' => $id, ':title' => $title, ':context' => $content, ':image' => $image];
        } else {
            // Update without changing image
            $sql = "UPDATE posts SET title = :title, context = :context WHERE id = :id";
            $params = [':id' => $id, ':title' => $title, ':context' => $content];
        }
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }
}
?>