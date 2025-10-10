<?php

declare(strict_types=1);

require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/task.php';
require_once __DIR__ . '/../service/task.service.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /pages/index.php');
    exit;
}

$tarefaId = $_POST['id'] ?? null;
$novoStatus = $_POST['id_status'] ?? null;
$redirectTo = $_POST['redirect_to'] ?? 'index.php';

$statusPermitidos = ['andamento', 'concluida', 'cancelada'];

if ($tarefaId === null || $novoStatus === null) {
    header('Location: /pages/index.php');
    exit;
}

$tarefaId = filter_var($tarefaId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

if ($tarefaId === false) {
    header('Location: /pages/index.php');
    exit;
}

$novoStatus = (string) $novoStatus;
if (!in_array($novoStatus, $statusPermitidos, true)) {
    header('Location: /pages/index.php');
    exit;
}

$redirectTo = trim((string) $redirectTo);
if ($redirectTo === '' || str_contains($redirectTo, '..') || str_starts_with($redirectTo, '/')) {
    $redirectTo = 'index.php';
}

$tarefa = new Tarefa();
$tarefa->setId($tarefaId);

$conexao = new Connection();

try {
    $tarefaService = new TaskService($conexao, $tarefa);

    if ($novoStatus === 'cancelada') {
        $tarefaService->deletarTarefa();
    } else {
        $tarefa->setId_status($novoStatus);
        $tarefaService->atualizarTarefa();
    }

    header('Location: /pages/' . $redirectTo);
    exit;
} catch (Throwable $exception) {
    http_response_code(500);
    echo 'Erro ao atualizar o status da tarefa.';
}
