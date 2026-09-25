<?php
session_start();
require 'dbconn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$errors = [];

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}

if ($password === '') {
    $errors[] = 'Password is required.';
}

if (!empty($errors)) {
    $_SESSION['login_errors'] = $errors;
    $_SESSION['login_email'] = $email;
    header('Location: login.php');
    exit;
}

$user = db_get_user_by_email($email);

if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['login_errors'] = ['Invalid email or password.'];
    $_SESSION['login_email'] = $email;
    header('Location: login.php');
    exit;
}

$_SESSION['admin_logged_in'] = true;
$_SESSION['admin_email'] = $user['email'];
$_SESSION['admin_name'] = $user['name'];
$_SESSION['admin_role'] = $user['role'];

header('Location: index.php');
exit;
