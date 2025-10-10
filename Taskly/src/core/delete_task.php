<?php

require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/task.php';
require_once __DIR__ . '/../service/task.service.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /pages/index.php');
    exit;
}

$tarefaIdRaw = $_POST['id'] ?? null;
$redirectToRaw = $_POST['redirect_to'] ?? 'index.php';

$tarefaId = filter_var(
    $tarefaIdRaw,
    FILTER_VALIDATE_INT,
    [
        'options' => [
            'min_range' => 1,
        ],
    ]
);

if ($tarefaId === false) {
    header('Location: /pages/index.php');
    exit;
}

$redirectTo = trim((string) $redirectToRaw);
if ($redirectTo === '' || str_contains($redirectTo, '..') || str_starts_with($redirectTo, '/')) {
    $redirectTo = 'index.php';
}

$tarefa = new Tarefa();
$tarefa->setId($tarefaId);

$conexao = new Connection();

try {
    $tarefaService = new TaskService($conexao, $tarefa);
    $tarefaService->deletarTarefa();
} catch (Throwable $exception) {
    header('Location: /pages/index.php');
    exit;
}
header('Location: /pages/' . $redirectTo);
exit;
