<?php

require_once __DIR__ . '/../../vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$host = $_ENV['DB_HOST'] ?? null;
$port = $_ENV['DB_PORT'] ?? null;
$dbname = $_ENV['DB_DATABASE'] ?? null;
$user = $_ENV['DB_USERNAME'] ?? null;
$password = $_ENV['DB_PASSWORD'] ?? null;
try {
    // Conexão com o banco de dados via PDO
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "✅ Conectado ao banco de dados com sucesso!<br>";

    $sqlStatus = "
        CREATE TABLE IF NOT EXISTS status (
            id SERIAL PRIMARY KEY,
            nome VARCHAR(50) NOT NULL UNIQUE
        );
    ";

    $sqlTarefa = "
        CREATE TABLE IF NOT EXISTS tarefa (
            id SERIAL PRIMARY KEY,
            id_status INTEGER NOT NULL REFERENCES status(id),
            tarefa VARCHAR(255) NOT NULL,
            data_cadastrada TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            responsavel VARCHAR(255)
        );
    ";

    $pdo->exec($sqlStatus);
    $pdo->exec($sqlTarefa);

    $statusIniciais = ['a_iniciar', 'andamento', 'concluida', 'cancelada'];
    $stmt = $pdo->prepare('INSERT INTO status (nome) VALUES (:nome) ON CONFLICT (nome) DO NOTHING');
    foreach ($statusIniciais as $status) {
        $stmt->execute([':nome' => $status]);
    }

    echo "✅ Estruturas criadas/atualizadas!";
} catch (PDOException $e) {
    echo "❌ Erro ao criar tabelas: " . $e->getMessage();
}
?>
