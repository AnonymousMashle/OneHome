<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$errors = [];
$formValues = [
    'customer_name' => trim($_POST['customer_name'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'phone' => trim($_POST['phone'] ?? ''),
    'address' => trim($_POST['address'] ?? ''),
    'product_name' => trim($_POST['product_name'] ?? ''),
    'part_name' => trim($_POST['part_name'] ?? ''),
    'part_number' => trim($_POST['part_number'] ?? ''),
    'quantity' => trim($_POST['quantity'] ?? ''),
    'priority' => $_POST['priority'] ?? 'Normal',
    'notes' => trim($_POST['notes'] ?? ''),
];

if ($formValues['customer_name'] === '') {
    $errors[] = 'Customer name is required.';
}

if ($formValues['email'] === '' || !filter_var($formValues['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'A valid email address is required.';
}

if ($formValues['product_name'] === '') {
    $errors[] = 'Product name is required.';
}

if ($formValues['part_name'] === '') {
    $errors[] = 'Part name is required.';
}

if ($formValues['part_number'] === '') {
    $errors[] = 'Part number is required.';
}

if ($formValues['quantity'] === '' || !ctype_digit($formValues['quantity']) || (int)$formValues['quantity'] < 1) {
    $errors[] = 'Quantity must be a number greater than 0.';
}

$_SESSION['form_values'] = $formValues;

if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_success'] = '';
    header('Location: index.php');
    exit;
}

$_SESSION['customer_requests'][] = [
    'customer_name' => $formValues['customer_name'],
    'email' => $formValues['email'],
    'phone' => $formValues['phone'],
    'address' => $formValues['address'],
    'product_name' => $formValues['product_name'],
    'part_name' => $formValues['part_name'],
    'part_number' => $formValues['part_number'],
    'quantity' => (int)$formValues['quantity'],
    'priority' => $formValues['priority'],
    'notes' => $formValues['notes'],
    'date' => date('Y-m-d H:i:s'),
];

$_SESSION['form_errors'] = [];
$_SESSION['form_success'] = 'Your parts request has been submitted successfully.';
$_SESSION['form_values'] = [];

header('Location: index.php');
exit;
