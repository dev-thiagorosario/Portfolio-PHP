<?php

require_once __DIR__ . '/../../vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

var_dump($_ENV); // Adicione esta linha para ver se as variáveis estão carregadas

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

    // Criação da tabela 'status'
    $sqlStatus = "
        CREATE TABLE IF NOT EXISTS status (
            id SERIAL PRIMARY KEY,
            nome VARCHAR(50) NOT NULL
        );
    ";

    // Criação da tabela 'tarefa'
    $sqlTarefa = "
        CREATE TABLE IF NOT EXISTS tarefa (
            id SERIAL PRIMARY KEY,
            id_status INTEGER NOT NULL REFERENCES status(id),
            tarefa VARCHAR(255) NOT NULL,
            data_cadastrada TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        );
    ";

    // Executa as queries
    $pdo->exec($sqlStatus);
    $pdo->exec($sqlTarefa);

    echo "✅ Tabelas criadas com sucesso!";
} catch (PDOException $e) {
    echo "❌ Erro ao criar tabelas: " . $e->getMessage();
}
?>
