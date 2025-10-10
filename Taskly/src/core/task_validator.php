<?php

require_once __DIR__ . '/task.php';
require_once __DIR__ . '/../service/task.service.php';
require_once __DIR__ . '/../database/connection.php';

class ValidaTarefaIndex
{
    private TaskService $tarefaService;

    public function __construct(?TaskService $taskService = null)
    {
        if ($taskService instanceof TaskService) {
            $this->tarefaService = $taskService;
            return;
        }

        $tarefa = new Tarefa();
        $conexao = new Connection();
        $this->tarefaService = new TaskService($conexao, $tarefa);
    }

    /**
     * Retorna apenas tarefas com status permitidos para exibição no dashboard inicial.
     *
     * @param string[] $statusPermitidos
     * @return array<int, array<string, mixed>>
     */
    public function validarTarefas(array $statusPermitidos = ['a_iniciar', 'andamento']): array
    {
        try {
            $tarefas = $this->tarefaService->listarTarefas();
        } catch (\Throwable $exception) {
            return [];
        }

        if (!is_array($tarefas)) {
            return [];
        }

        $statusPermitidos = array_map('strval', $statusPermitidos);

        $tarefasFiltradas = array_filter(
            $tarefas,
            static function ($tarefa) use ($statusPermitidos) {
                if (!is_array($tarefa) || !isset($tarefa['id_status'])) {
                    return false;
                }

                return in_array((string) $tarefa['id_status'], $statusPermitidos, true);
            }
        );

        return array_values($tarefasFiltradas);
    }

}
