<?php
session_start();

$formData = $_SESSION['task_form_data'] ?? null;
$status = $_SESSION['task_form_status'] ?? null;
$errorMessage = $_SESSION['task_form_error'] ?? null;

unset($_SESSION['task_form_data'], $_SESSION['task_form_status'], $_SESSION['task_form_error']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Taskly — Todas as Tarefas</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<?php if ($status === 'success' && $formData): ?>
  <main>
    <section>
      <h1>Tarefa criada com sucesso</h1>
      <ul>
        <li><strong>ID:</strong> <?= htmlspecialchars((string) ($formData['id'] ?? '')) ?></li>
        <li><strong>Título:</strong> <?= htmlspecialchars($formData['titulo']) ?></li>
        <li><strong>Data limite:</strong> <?= htmlspecialchars($formData['data_limite']) ?></li>
        <li><strong>Status:</strong> <?= htmlspecialchars($formData['status']) ?></li>
        <li><strong>Urgência:</strong> <?= htmlspecialchars($formData['urgencia']) ?></li>
        <li><strong>Responsável:</strong> <?= htmlspecialchars($formData['responsavel']) ?></li>
      </ul>
    </section>
  </main>
<?php elseif ($status === 'error'): ?>
  <p><?= htmlspecialchars($errorMessage ?? 'Não foi possível processar a tarefa.') ?></p>
<?php else: ?>
  <p>Nenhum dado foi enviado.</p>
<?php endif; ?>
</body>
</html>
