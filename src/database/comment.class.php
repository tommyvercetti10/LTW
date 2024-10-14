<?php
class Comment {

    public $id;
    public $by;
    public $post;
    public $text;
    public $timestamp;

    // if comment was a reply, id
    // of the quoted comment is stored here
    public $repliedTo;

    public function __construct($by, $post, $text, $repliedTo = null, $timestamp = null, $id = null) {
        $this->id = $id ?? $this->generateSecureUniqueId($post);
        $this->by = $by;
        $this->post = $post;
        $this->text = $text;
        $this->timestamp = $timestamp !== null ? $timestamp : new DateTime();
        $this->repliedTo = $repliedTo;
    }

    private function generateSecureUniqueId($post) {
        $randomBytes = random_bytes(16);
        $time = microtime(true); 
        $salt = $post; 

        return hash('sha256', $randomBytes . $time . $salt);
    }

    public function __sleep() {
        return ['id', 'by', 'post', 'text', 'timestamp', 'repliedTo'];
    }

    public function serialize() {
        return serialize([
            'id' => $this->id,
            'by' => $this->by,
            'post' => $this->post,
            'timestamp' => $this->timestamp->format('Y-m-d H:i:s'),
            'repliedTo' => $this->repliedTo,
        ]);
    }

    public function unserialize($data) {
        $data = unserialize($data);
        $this->id = $data['id'];
        $this->by = $data['by'];
        $this->post = $data['post'];
        $this->text = $data['text'];
        $this->timestamp = new DateTime($data['timestamp']);
        $this->repliedTo = $data['repliedTo'];
    }

    public function postComment(PDO $db) {
        try {
            $stmt = $db->prepare('INSERT INTO Comment (id, by, post, text, timestamp, repliedTo) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([
                $this->id,
                $this->by, 
                $this->post,
                $this->text,
                $this->timestamp->format('Y-m-d H:i:s'), 
                $this->repliedTo
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Failed to post comment to db: " . $e->getMessage()); 
            return false;
        }
    }
    
    public static function getComment(PDO $db, string $id) : ?Comment {

        $stmt = $db->prepare('SELECT * FROM Comment WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        
        if ($comment = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return new Comment(
                $comment['by'],
                $comment['post'],
                $comment['text'],
                $comment['repliedTo'],
                $comment['timestamp'],
                $comment['id']
            );
        } else {
            error_log('Could not fetch comment with id: ' . $id);
        }
        return null;
    }
    
    public static function getCommentsFromPost(PDO $db, string $post) : ?array {

        $stmt = $db->prepare("SELECT * FROM Comment WHERE post = ? AND (repliedTo = '' OR repliedTo IS NULL)");
        $stmt->execute([$post]);
    
        $comments = [];

        while ($comment = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $comments[] = new Comment(
                $comment['by'],
                $comment['post'],
                $comment['text'],
                $comment['repliedTo'],
                $comment['timestamp'],
                $comment['id']
            );
        }
        return $comments;
    }

    public static function getReplies(PDO $db, string $id) : ?array {

        $stmt = $db->prepare('SELECT * FROM Comment WHERE repliedTo = ?');
        $stmt->execute([$id]);
    
        $comments = []; // may return an empty array
        
        while ($comment = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $comments[] = new Comment(
                $comment['by'],
                $comment['post'],
                $comment['text'],
                $comment['repliedTo'],
                $comment['timestamp'],
                $comment['id']
            );
        }
        return $comments;
    }

    public static function deleteComment(PDO $db, string $id) : bool {
        try {
            $stmt = $db->prepare('DELETE FROM Comment WHERE id = ?');
            $stmt->execute([$id]);
            return true;
        }
        catch (PDOException $e) {
            return false;
        }
    }
}
?>
