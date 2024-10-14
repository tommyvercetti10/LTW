<?php
if(!class_exists("User")) {
    class User {
    public $id;
    public $email;
    public $name;
    public $password;
    public $createdAt;
    public $biography;

    public $photo;

    public $city;
    public $isAdmin;

    public function __construct($email, $name, $password, $id = null, $city = null, $biography = null, $createdAt = null, $isAdmin = false, $photo = '') {
        $this->id = $id ?? $this->generateSecureUniqueId($email);
        $this->email = strtolower($email); 
        $this->name = $name;
        $this->password = $password;
        $this->city = $city ?? '';
        $this->biography = $biography ?? '';
        $this->createdAt = $createdAt ? new DateTime($createdAt) : new DateTime();
        $this->isAdmin = $isAdmin;
        $this->photo = $photo;
    }

    private function generateSecureUniqueId($email) {
        $randomBytes = random_bytes(16);
        $time = microtime(true); 
        $salt = $email; 

        return hash('sha256', $randomBytes . $time . $salt);
    }

    public function __sleep() {
        return ['id', 'email', 'name', 'createdAt', 'biography', 'city', 'isAdmin', 'photo'];
    }

    public function serialize() {
        return serialize([
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'biography' => $this->biography,
            'city' => $this->city,
            'isAdmin' => $this->isAdmin,
            'photo' => $this->photo ?? ''
        ]);
    }

    public function unserialize($data) {
        $data = unserialize($data);
        $this->id = $data['id'];
        $this->email = $data['email'];
        $this->name = $data['name'];
        $this->createdAt = new DateTime($data['createdAt']);
        $this->biography = $data['biography'];
        $this->city = $data['city'];
        $this->password = null;
        $this->isAdmin = $data['isAdmin'];
        $this->photo = $data['photo'];
    }

    public function saveUser(PDO $db) {
        try {
            $stmt = $db->prepare('INSERT INTO User (id, email, name, password, createdAt, biography, city, isAdmin, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([
                $this->id,
                strtolower($this->email),  
                $this->name, 
                password_hash($this->password, PASSWORD_DEFAULT), 
                $this->createdAt->format('Y-m-d H:i:s'), 
                $this->biography, 
                $this->city,
                $this->isAdmin,
                $this->photo
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Failed to save user: " . $e->getMessage()); 
            return false;
        }
    }
    
    public static function getUser(PDO $db, string $email, string $password) : ?User {
        $email = trim($email);
        $password = trim($password);

        $stmt = $db->prepare('SELECT * FROM User WHERE email = ?');
        $stmt->execute([strtolower($email)]);
        
        if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (password_verify($password, $user['password'])) {
                return new User(
                    $user['email'],
                    $user['name'],
                    $user['password'],
                    $user['id'],
                    $user['city'],
                    $user['biography'],
                    $user['createdAt'],
                    $user['isAdmin'],
                    $user['photo'],
                );
            } else {
                error_log('Password verification failed.');
            }
        } else {
            error_log('No user found with that email: ' . $email);
        }
        return null;
    }
    
    

    public function updateUser(PDO $db) {
        try {
            $stmt = $db->prepare('UPDATE User SET email = ?, name = ?, biography = ?, city = ?, photo = ? WHERE id = ?');
            $stmt->execute([
                $this->email,
                $this->name, 
                $this->biography,
                $this->city,
                $this->photo,
                $this->id
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Failed to update user: " . $e->getMessage());
            return false;
        }
    }

    public function updateUserPassword(PDO $db, string $newPassword) {
        try {
            $stmt = $db->prepare('UPDATE User SET password = ? WHERE id = ?');
            $stmt->execute([
                password_hash($newPassword, PASSWORD_DEFAULT),
                $this->id
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Failed to update user: " . $e->getMessage());
            return false;
        }
    }
    static public function elevateUser(PDO $db, string $user_id) {
        try {
            $stmt = $db->prepare('UPDATE User SET isAdmin = ? WHERE id = ?');
            $stmt->execute([
                true,
                $user_id
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Failed to update user: " . $e->getMessage());
            return false;
        }
    }

    static public function banUser(PDO $db, string $user_id) {
        try {
            $stmt = $db->prepare('DELETE FROM User WHERE id = ?');
            $stmt->execute([$user_id]);
            return true;
        } catch (PDOException $e) {
            error_log("Failed to delete user: " . $e->getMessage());
            return false;
        }
    }
    public static function getUserById(PDO $db, string $id) : ?User {

        $stmt = $db->prepare('SELECT * FROM User WHERE id = ?');
        $stmt->execute([$id]);
        
        if($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return new User(
                $user['email'], 
                $user['name'], 
                $user['password'],
                $user['id'],
                $user['city'],
                $user['biography'],
                $user['createdAt'],
                $user['isAdmin'],
                $user['photo'],
            );
        }
        return null;
    }

    public function getPhoto() {
        if ($this->photo == '') {
            return $this->photo = '../../assets/person.jpeg';
        }
        else {
            return $this->photo = '../../uploads/users/' . $this->photo;
        }
    }
}
}
?>
