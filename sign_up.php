<?php
require_once 'validation.php';

$host = "localhost";
$username = "root";
$password = "";
$database = "user_auth_db";

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!Validation::validateEmail($email)) {
        die("Invalid email format");
    }

    if (!Validation::validateLength($username, 3)) {
        die("Username must be at least 3 characters long");
    }

    if (!Validation::validateNotEmpty($password)) {
        die("Password cannot be empty");
    }

    if (!Validation::validateNotEmpty($email)) {
        die("Email cannot be empty");
    }

    if (!Validation::validateNotEmpty($username)) {
        die("Username cannot be empty");
    }

    if (!Validation::validatePasswordStrength($password)) {
        die("Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit, and one special character");
    }

    if (!Validation::isUnique($conn, $email, 'email')) {
        die("Email already registered");
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    echo "Registration successful!";

    $conn = null;

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

