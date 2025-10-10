<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../core/task_controller.php';
    exit;
}

session_start();

$formData = $_SESSION['task_form_data'] ?? null;
$status = $_SESSION['task_form_status'] ?? null;
$errorMessage = $_SESSION['task_form_error'] ?? null;

unset($_SESSION['task_form_data'], $_SESSION['task_form_status'], $_SESSION['task_form_error']);

header('Location: new_task_include1.php');
exit;
