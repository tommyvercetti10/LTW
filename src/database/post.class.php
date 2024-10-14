<?php
class Post {

    public $id;
    public $userId;
    public $name;
    public $createdAt;
    public $description;
    public $condition;
    public $categories;
    public $price;
    public $photos;

    public function __construct($userId, $name, $price, $description = null, $categories = [], $condition = null, $createdAt = null, $id = null, $photos = []) {
        $this->id = $id ?? $this->generateSecureUniqueId($userId, $name);
        $this->userId = $userId;
        $this->name = $name;
        $this->createdAt = $createdAt ? new DateTime($createdAt) : new DateTime();
        $this->description = $description ?? "";
        $this->categories = is_string($categories) ? explode(',', $categories) : $categories;
        $this->condition = $condition ?? "";
        $this->price = $price;
        $this->photos = is_string($photos) ? explode(",", $photos) : $photos;
    }

    private function generateSecureUniqueId($userId, $name) {
        $randomBytes = random_bytes(16); 
        $time = microtime(true); 
        $salt = $userId . $name; 

        return hash('sha256', $randomBytes . $time . $salt);
    }


    public static function getPost(PDO $db, string $postId) {
        $stmt = $db->prepare('SELECT p.id, p.userId, p.name, p.createdAt, p.description, p.price, p.condition, GROUP_CONCAT(pc.category) AS categories, GROUP_CONCAT(pp.photo) AS photos
                              FROM Post p
                              LEFT JOIN PostCategory pc ON p.id = pc.postId
                              LEFT JOIN PostPhoto pp ON p.id = pp.postId
                              WHERE p.id = ?'); 
    
        $stmt->execute([strtolower($postId)]);
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $categories = $row['categories'] ? explode(',', $row['categories']) : [];
        $photos = $row['photos'] ? explode(',', $row['photos']) : [];
        $post = new Post($row['userId'], $row['name'], $row['price'], $row['description'], $categories, $row['condition'], $row['createdAt'], $row['id'], $photos);
        
        return $post;
    }

    public static function getPostsFromUser(PDO $db, string $userId) {
        $stmt = $db->prepare('SELECT p.id, p.userId, p.name, p.createdAt, p.description, p.price, p.condition, GROUP_CONCAT(pc.category) AS categories, GROUP_CONCAT(pp.photo) AS photos
                              FROM Post p
                              LEFT JOIN PostCategory pc ON p.id = pc.postId
                              LEFT JOIN PostPhoto pp ON p.id = pp.postId
                              WHERE p.userId = ?
                              GROUP BY p.id'); 
    
        $stmt->execute([strtolower($userId)]);
    
        $posts = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories = $row['categories'] ? explode(',', $row['categories']) : [];
            $photos = $row['photos'] ? explode(',', $row['photos']) : [];
            $posts[] = new Post($row['userId'], $row['name'], $row['price'], $row['description'], $categories, $row['condition'], $row['createdAt'], $row['id'], $photos);
        }
    
        return $posts;
    }

    public static function getPosts(PDO $db, ?string $userId) {
        try {
            if ($userId !== null) {
                $stmt = $db->prepare('SELECT p.*, 
                 GROUP_CONCAT(DISTINCT pc.category) AS categories, 
                 GROUP_CONCAT(DISTINCT pp.photo) AS photos 
                 FROM Post p 
                 LEFT JOIN PostCategory pc ON p.id = pc.postId 
                 LEFT JOIN PostPhoto pp ON p.id = pp.postId 
                 WHERE p.userId <> ? 
                 GROUP BY p.id');
                $stmt->execute([strtolower($userId)]);
            } else {
                $stmt = $db->prepare('SELECT p.*
                GROUP_CONCAT(DISTINCT pc.category) AS categories, 
                GROUP_CONCAT(DISTINCT pp.photo) AS photos 
                FROM Post p 
                LEFT JOIN PostCategory pc ON p.id = pc.postId 
                LEFT JOIN PostPhoto pp ON p.id = pp.postId 
                GROUP BY p.id');
                $stmt->execute();
            }
    
            $posts = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $categories = isset($row['categories']) ? explode(',', $row['categories']) : [];
                $photos = isset($row['photos']) ? explode(',', $row['photos']) : [];
                $posts[] = new Post($row['userId'], $row['name'], $row['price'], $row['description'], $categories, $row['condition'], $row['createdAt'], $row['id'], $photos);
            }
    
            return $posts;
        } catch (PDOException $e) {
            error_log('PDOException: ' . $e->getMessage());
            return [];
        }
    }

    public function savePost(PDO $db) {
        try {
            $db->beginTransaction();

            $stmt = $db->prepare('INSERT INTO Post (id, userId, name, createdAt, description, price, condition) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([
                $this->id, 
                $this->userId,
                $this->name, 
                $this->createdAt->format('Y-m-d H:i:s'), 
                $this->description,
                $this->price,
                $this->condition
            ]);

            if (!empty($this->categories)) {
                $stmt = $db->prepare('INSERT INTO PostCategory (postId, category) VALUES (?, ?)');
                foreach ($this->categories as $category) {
                    $stmt->execute([$this->id, $category]);
                }
            }

            if (!empty($this->photos)) {
                $stmt = $db->prepare('INSERT INTO PostPhoto (postId, photo) VALUES (?, ?)');
                foreach ($this->photos as $photo) {
                    $stmt->execute([$this->id, $photo]);
                }
            }

            $db->commit();
            return true;
        } catch (PDOException $e) {
            $db->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }

    static public function deletePostById(PDO $db, string $post_id) {
        try {
            $stmt = $db->prepare('SELECT photo FROM PostPhoto WHERE postId = ?');
            $stmt->execute([$post_id]);
            $photos = $stmt->fetchAll(PDO::FETCH_COLUMN);

            foreach ($photos as $photo) {
                if (file_exists('../../' . $photo)) {
                    unlink('../../' . $photo);
                }
            }
            $stmt = $db->prepare('DELETE FROM PostPhoto WHERE postId = ?');
            $stmt->execute([$post_id]);

           
            $stmt = $db->prepare('DELETE FROM PostCategory WHERE postId = ?');
            $stmt->execute([$post_id]);

            
            $stmt = $db->prepare('DELETE FROM Post WHERE id = ?');
            $stmt->execute([$post_id]);

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    static public function deleteWishlistPostById(PDO $db, string $post_id, string $user_id) {
        try {
            $stmt = $db->prepare('DELETE FROM WishList WHERE userId = ? AND postId = ?; ');
            $stmt->execute([$user_id, $post_id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    static public function shopCartById(PDO $db, string $post_id, string $user_id){
        if (!self::checkIfShopCartById($db, $post_id, $user_id)) {
            try{
                $stmt = $db->prepare('INSERT INTO Shopcart (userId, postId) VALUES (?, ?)');
                $stmt->execute([$user_id, $post_id]);
                return true;
            } catch(PDOException $e){
                error_log('Failed to insert in shopcart: ' . $e->getMessage());
                return false;
            }
        } else{
            error_log('Product is already in Shopcart');
            return false;
        }
    }

    static public function checkIfShopCartById(PDO $db, string $post_id, string $user_id){
        try{
            $stmt = $db->prepare('SELECT 1 FROM Shopcart WHERE userId = ? AND postId = ?');
            $stmt->execute([$user_id, $post_id]);
            return $stmt->fetchColumn() !== false;
        } catch(PDOException $e){
            error_log('Error checking shopcart status: ' - $e->getMessage());
            return false;
        }
    }
    
    static public function deleteCartPostById(PDO $db, string $post_id, string $user_id) {
        try {
            $stmt = $db->prepare('DELETE FROM ShopCart WHERE userId = ? AND postId = ?');
            $stmt->execute([$user_id, $post_id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    static public function likePostById(PDO $db, string $post_id, string $user_id) {
        if (!self::checkIfPostIsLikedBy($db, $post_id, $user_id)) {
            try {
                $stmt = $db->prepare('INSERT INTO WishList (userId, postId) VALUES (?, ?)');
                $stmt->execute([$user_id, $post_id]);
                return true;
            } catch (PDOException $e) {
                error_log('Failed to insert like: ' . $e->getMessage());
                return false;
            }
        } else {
            error_log('Like already exists');
            return false;
        }
    }

    static public function checkIfPostIsLikedBy(PDO $db, string $post_id, string $user_id) {
        try {
            $stmt = $db->prepare('SELECT 1 FROM WishList WHERE userId = ? AND postId = ?');
            $stmt->execute([$user_id, $post_id]);
            return $stmt->fetchColumn() !== false;
        } catch (PDOException $e) {
            error_log('Error checking like status: ' . $e->getMessage());
            return false;
        }
    }

    public static function getLikedPosts(PDO $db, string $user_id) {
        try {
            $stmt = $db->prepare('
                SELECT p.id, p.userId, p.name, p.createdAt, p.description, p.price, p.condition, GROUP_CONCAT(pc.category) AS categories, GROUP_CONCAT(pp.photo) AS photos
                FROM WishList w
                JOIN Post p ON w.postId = p.id
                LEFT JOIN PostCategory pc ON p.id = pc.postId
                LEFT JOIN PostPhoto pp ON p.id = pp.postId
                WHERE w.userId = ?
                GROUP BY p.id
            ');
            $stmt->execute([$user_id]);
            
            $posts = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $categories = $row['categories'] ? explode(',', $row['categories']) : [];
                $photos = $row['photos'] ? explode(',', $row['photos']) : [];
                $posts[] = new Post(
                    $row['userId'], 
                    $row['name'], 
                    $row['price'], 
                    $row['description'], 
                    $categories, 
                    $row['condition'], 
                    $row['createdAt'], 
                    $row['id'],
                    $photos
                );
            }
            
            return $posts;
        } catch (PDOException $e) {
            error_log('Error retrieving liked posts: ' . $e->getMessage());
            return [];
        }
    }

    public static function filterPosts(PDO $db, float $price, bool $asc, array $categories = [], array $conditions = []) {
        
        $query = 'SELECT Post.*, GROUP_CONCAT(PostCategory.category) AS categories, GROUP_CONCAT(PostPhoto.photo) AS photos
                  FROM Post 
                  LEFT JOIN PostCategory ON Post.id = PostCategory.postId
                  LEFT JOIN PostPhoto ON Post.id = PostPhoto.postId
                  WHERE Post.price <= :price';
        
        $params = [':price' => $price];
        
        if (!empty($categories) && $categories[0] !== '') {
            error_log("Empty CATEGORIES");
            $placeholders = implode(',', array_fill(0, count($categories), '?'));
            $query .= " AND PostCategory.category IN ($placeholders)";
            $params = array_merge($params, $categories);
        }
        
        if (!empty($conditions) && $conditions[0] !== '') {
            error_log("Empty CONDITIONS");
            $conditionPlaceholders = implode(',', array_fill(0, count($conditions), '?'));
            $query .= " AND Post.condition IN ($conditionPlaceholders)";
            $params = array_merge($params, $conditions);
        }
        
        $query .= ' GROUP BY Post.id';

        if ($asc) {
            $query .= " ORDER BY Post.price ASC";
        } else {
            $query .= " ORDER BY Post.price DESC";
        }
        
        $stmt = $db->prepare($query);
        
        error_log("Executing query: $query with params: " . json_encode(array_values($params)));
        
        try {
            $stmt->execute(array_values($params));
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [];
        }
        
        $posts = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            error_log("filterPosts createdAt: " . $row['createdAt']);
            $categories = $row['categories'] ? explode(',', $row['categories']) : [];
            $photos = $row['photos'] ? explode(',', $row['photos']) : [];
            
            $post = new Post(
                $row['userId'],
                $row['name'],
                $row['price'],
                $row['description'],
                $categories,
                $row['condition'],
                $row['createdAt'],
                $row['id'],
                $photos
            );
            
            error_log("Post ID: " . $post->id);
            $posts[] = $post;
        }
        
        return $posts;
    }

    public static function addCategory(PDO $db, string $category) {
        try {
            $stmt = $db->prepare('INSERT INTO ItemCategory (category) VALUES (?)');
            $stmt->execute([$category]);
            return true;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public static function addCondition(PDO $db, string $condition) {
        try {
            $stmt = $db->prepare('INSERT INTO ItemCondition (condition) VALUES (?)');
            $stmt->execute([$condition]);
            return true;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    static public function getCategories(PDO $db) {
        try {
           
            $stmt = $db->prepare('SELECT * FROM ItemCategory');
            $stmt->execute();
            
            $categories = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $categories[] = $row['category'];
            }
            
            return $categories;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    static public function getConditions(PDO $db) {
        try {
           
            $stmt = $db->prepare('SELECT * FROM ItemCondition');
            $stmt->execute();
            
            $conditions = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $conditions[] = $row['condition'];
            }
            
            return $conditions;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function updatePost(PDO $db) {
        try {
            $stmt = $db->prepare('UPDATE Post SET name = ?, description = ?, price = ? WHERE id = ?');
            $stmt->execute([$this->name, $this->description, $this->price, $this->id]);
            return true;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    public function getPhotos() :array {

        if ($this->photos == []) {
            // no photos
            return ['../../assets/placeholder.png'];
        }

        if (is_string($this->photos)) {
            return ['../../' . $this->photos];
        }

        $photos = [];
        foreach ($this->photos as $photo) {
            $photos []= '../../' . $photo;
        }
        return $photos;
    }

    public static function getShoppingCart(PDO $db, string $userId) {
        try {
            $stmt = $db->prepare('SELECT * FROM Shopcart WHERE userId = ?');
            $stmt->execute([$userId]);
            
            $cart = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $cart []= Post::getPost($db, $row['postId']);
            }
            return $cart;

        } catch (PDOException $e) {
            error_log("Error fetching shopping cart of user " . $e->getMessage());
            return null;
        }
    }
}
?>
