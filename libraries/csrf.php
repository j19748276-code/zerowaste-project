<?php
// app/libraries/Csrf.php
// Simple CSRF token helper for your MVC app.
//
// Usage:
//  - Include/autoload this class in your bootstrap (or require when needed).
//  - In forms: echo Csrf::input();  // prints hidden input
//  - On POST handling: Csrf::validate($_POST['csrf'] ?? null) or Csrf::validateRequest();

class Csrf
{
    private const SESSION_KEY = 'csrf_tokens';
    private const MAX_TOKENS = 16;
    private const TOKEN_BYTES = 32;

    private static function ensureSession()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION[self::SESSION_KEY]) || !is_array($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = [];
        }
    }

    public static function generateToken(): string
    {
        self::ensureSession();
        $token = bin2hex(random_bytes(self::TOKEN_BYTES));
        array_unshift($_SESSION[self::SESSION_KEY], $token);
        if (count($_SESSION[self::SESSION_KEY]) > self::MAX_TOKENS) {
            $_SESSION[self::SESSION_KEY] = array_slice($_SESSION[self::SESSION_KEY], 0, self::MAX_TOKENS);
        }
        return $token;
    }

    public static function input(string $name = 'csrf'): string
    {
        $token = self::generateToken();
        return '<input type="hidden" name="' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function validate(?string $token): bool
    {
        self::ensureSession();
        if (empty($token) || !is_string($token)) {
            return false;
        }

        foreach ($_SESSION[self::SESSION_KEY] as $k => $stored) {
            if (hash_equals($stored, $token)) {
                array_splice($_SESSION[self::SESSION_KEY], $k, 1);
                return true;
            }
        }
        return false;
    }

    public static function validateRequest(string $name = 'csrf'): bool
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return false;
        }
        $token = $_POST[$name] ?? null;
        return self::validate($token);
    }

    public static function clear(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION[self::SESSION_KEY])) {
            unset($_SESSION[self::SESSION_KEY]);
        }
    }
}
