<?php
function registraChamado(): void {
    $baseDir = __DIR__ . '/../database/Chamados-database';
    try {
        if (!is_dir($baseDir)) {
            if (!mkdir($baseDir, 0775, true) && !is_dir($baseDir)) {
                throw new RuntimeException('Não foi possível preparar a pasta de chamados.');
            }
        }
    } catch (Throwable $e) {
        error_log($e->getMessage());
        throw new RuntimeException('Erro ao preparar a pasta de chamados.');
    }

    $titulo = str_replace('#', '-', $_POST['titulo'] ?? '');
    $categoria = str_replace('#', '-', $_POST['categoria'] ?? '');
    $descricao = str_replace('#', '-', $_POST['descricao'] ?? '');

    $slug = preg_replace('/[^a-z0-9]+/i', '-', strtolower($titulo));
    $slug = trim($slug, '-');
    if ($slug === '') {
        $slug = 'chamado';
    }

    $arquivoDestino = sprintf(
        '%s/%s-%s.txt',
        $baseDir,
        date('Ymd-His'),
        substr($slug, 0, 40)
    );

    $conteudo = sprintf(
        "Título: %s\nCategoria: %s\nDescrição:\n%s\n\nRegistrado em: %s\n",
        $titulo,
        $categoria,
        $descricao,
        date('c')
    );

    if (file_put_contents($arquivoDestino, $conteudo, LOCK_EX) === false) {
        error_log("Falha ao escrever em {$arquivoDestino}");
        throw new RuntimeException('Não foi possível registrar o chamado.');
    }
}
registraChamado();
header('Location: /pages/home.php');
exit();
