<?php
require_once(__DIR__ . "/autoload.php");

session_start();

class Session {
    private array $messages;
    private static ?Session $instance = null;

    private function __construct() {
        $this->messages = $_SESSION['messages'] ?? [];
        unset($_SESSION['messages']);

        // Generate CSRF token if it doesn't exist
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    private function __clone() {}

    public function __wakeup() {}

    public static function getInstance(): Session {
        if (self::$instance === null) {
            self::$instance = new Session();
        }
        return self::$instance;
    }

    public function logout() {
        session_destroy();
    }

    public function get($key) {
        return $_SESSION[$key] ?? null;
    }

    public function set(string $key, $value): void {
        $_SESSION[$key] = $value;
    }

    public function addMessage(string $type, string $message) {
        if (!isset($_SESSION['messages'])) {
            $_SESSION['messages'] = [];
        }
        $_SESSION['messages'][] = ['type' => $type, 'message' => $message];
    }

    public function getMessages(): array {
        return $this->messages;
    }

    public function getCsrfToken(): string {
        return $_SESSION['csrf_token'];
    }

    public function validateCsrfToken($token): bool {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    public function removeCsrfToken(): void {
        unset($_SESSION['csrf_token']);
    }
}
?>
