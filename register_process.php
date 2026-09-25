<?php
session_start();
require 'dbconn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

$formValues = [
    'full_name' => trim($_POST['full_name'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'password' => $_POST['password'] ?? '',
    'confirm_password' => $_POST['confirm_password'] ?? '',
];

$errors = [];

if ($formValues['full_name'] === '') {
    $errors[] = 'Full name is required.';
}

if ($formValues['email'] === '' || !filter_var($formValues['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}

if (strlen($formValues['password']) < 6) {
    $errors[] = 'Password must be at least 6 characters long.';
}

if ($formValues['password'] !== $formValues['confirm_password']) {
    $errors[] = 'Passwords do not match.';
}

if (!empty($errors)) {
    $_SESSION['register_errors'] = $errors;
    $_SESSION['register_values'] = $formValues;
    header('Location: register.php');
    exit;
}

if (db_user_exists($formValues['email'])) {
    $_SESSION['register_errors'] = ['An account with this email already exists.'];
    $_SESSION['register_values'] = $formValues;
    header('Location: register.php');
    exit;
}

$passwordHash = password_hash($formValues['password'], PASSWORD_DEFAULT);

if (!db_insert_user($formValues['full_name'], $formValues['email'], $passwordHash)) {
    $_SESSION['register_errors'] = ['Unable to create account right now. Please try again.'];
    $_SESSION['register_values'] = $formValues;
    header('Location: register.php');
    exit;
}

$_SESSION['register_success'] = 'Account created successfully. Please sign in.';
header('Location: login.php');
exit;
