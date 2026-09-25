<?php
$dbType = 'sqlite';

if (function_exists('mysqli_connect')) {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $conn = mysqli_connect("localhost", "root", "", "onehome_db");

    if (!$conn) {
        $conn = mysqli_connect("localhost", "root", "");

        if (!$conn) {
            die("Database connection failed: " . mysqli_connect_error());
        }

        $createDb = mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS onehome_db");
        if (!$createDb) {
            die("Failed to create database: " . mysqli_error($conn));
        }

        $conn = mysqli_connect("localhost", "root", "", "onehome_db");
        if (!$conn) {
            die("Database connection failed after database creation: " . mysqli_connect_error());
        }
    }

    $createUsersTable = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('customer', 'provider', 'admin') DEFAULT 'customer'
    )";

    if (!mysqli_query($conn, $createUsersTable)) {
        die("Failed to create users table: " . mysqli_error($conn));
    }

    $dbType = 'mysql';
} else {
    $dbFile = __DIR__ . '/onehome.sqlite';

    try {
        $conn = new PDO('sqlite:' . $dbFile);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $conn->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            role TEXT DEFAULT 'customer'
        )");
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

function db_user_exists($email) {
    global $conn, $dbType;

    if ($dbType === 'mysql') {
        $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    $stmt = $conn->prepare('SELECT id FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]);
    return $stmt->fetch() !== false;
}

function db_get_user_by_email($email) {
    global $conn, $dbType;

    if ($dbType === 'mysql') {
        $stmt = $conn->prepare('SELECT id, name, email, password, role FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    $stmt = $conn->prepare('SELECT id, name, email, password, role FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]);
    return $stmt->fetch();
}

function db_insert_user($fullName, $email, $passwordHash) {
    global $conn, $dbType;

    if ($dbType === 'mysql') {
        $stmt = $conn->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, "customer")');
        $stmt->bind_param('sss', $fullName, $email, $passwordHash);
        return $stmt->execute();
    }

    $stmt = $conn->prepare('INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, "customer")');
    return $stmt->execute([
        'name' => $fullName,
        'email' => $email,
        'password' => $passwordHash,
    ]);
}
?>