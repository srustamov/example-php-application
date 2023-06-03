<?php

namespace App\Support;

class CSRF
{
    protected static ?string $token = null;

    public static string $tokenName = '_token';

    public static function getToken(): string
    {
        if (!self::$token) {
            self::$token = bin2hex(random_bytes(32));
        }

        $_SESSION[self::$tokenName] = self::$token;

        return self::$token;
    }

    public static function verifyToken(mixed $token): bool
    {
        if (!isset($_SESSION[self::$tokenName])) {
            return false;
        }

        return hash_equals($_SESSION[self::$tokenName], $token);
    }
}