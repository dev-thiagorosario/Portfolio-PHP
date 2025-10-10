<?php

session_start();

require_once __DIR__ . '/../service/task.service.php';
require_once __DIR__ . '/../core/task.php';
require_once __DIR__ . '/../database/connection.php';

$acao = $acao ?? $_GET['acao'] ?? $_POST['acao'] ?? null;

if ($acao === 'recuperar') {
    $tarefa = new Tarefa();
    $conexao = new Connection();

    $tarefaService = new TaskService($conexao, $tarefa);
    $tarefas = $tarefaService->listarTarefas();
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $acao === 'inserir') {
    $payload = [
        'id'              => null,
        'id_status'       => trim($_POST['status'] ?? ''),
        'tarefa'          => trim($_POST['titulo'] ?? ''),
        'data_cadastrada' => trim($_POST['data_limite'] ?? ''),
        'responsavel'     => trim($_POST['responsavel'] ?? ''),
    ];
    $prioridade = trim($_POST['urgencia'] ?? '');

    $tarefa = new Tarefa();
    $tarefa->setId($payload['id']);
    $tarefa->setId_status($payload['id_status']);
    $tarefa->setTarefa($payload['tarefa']);
    $dataCadastrada = $payload['data_cadastrada'] === '' ? null : $payload['data_cadastrada'];
    $tarefa->setData_cadastrada($dataCadastrada);
    $tarefa->setResponsavel($payload['responsavel']);
    $tarefa->setPrioridade($prioridade);

    $conexao = new Connection();

    try {
        $tarefaService = new TaskService($conexao, $tarefa);
        $tarefaService->inserirTarefa();

        $payload['id'] = $tarefa->getId();
        $_SESSION['task_form_data'] = array_merge(
            $payload,
            [
                'titulo'      => $payload['tarefa'],
                'data_limite' => $payload['data_cadastrada'],
                'status'      => $payload['id_status'],
                'urgencia'    => $prioridade,
            ]
        );
        $_SESSION['task_form_status'] = 'success';

        header('Location: /pages/task_controller.php');
        exit;
    } catch (Throwable $e) {
        $_SESSION['task_form_data'] = array_merge(
            $payload,
            [
                'titulo'      => $payload['tarefa'],
                'data_limite' => $payload['data_cadastrada'],
                'status'      => $payload['id_status'],
                'urgencia'    => $prioridade,
            ]
        );
        $_SESSION['task_form_status'] = 'error';
        $_SESSION['task_form_error'] = 'Erro ao salvar tarefa: ' . $e->getMessage();

        header('Location: /pages/task_controller.php');
        exit;
    }
}

header('Location: ../pages/new_task.php');
exit;
