<?php

class Validation
{
    public static function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function validateLength($input, $minLength = 6)
    {
        return strlen($input) >= $minLength;
    }

    public static function validateNotEmpty($field)
    {
        return !empty($field);
    }

    public static function validatePasswordStrength($password)
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/', $password);
    }

    public static function isUnique($pdo, $email, $column)
    {
        $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM users WHERE $column = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] == 0;
    }
}
