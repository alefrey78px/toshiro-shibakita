<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');

// Variáveis de ambiente para evitar credenciais expostas
$servername = getenv('DB_HOST') ?: '54.234.153.24';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: 'Senha123';
$database = getenv('DB_NAME') ?: 'meubanco';

// Criar conexão segura
$link = new mysqli($servername, $username, $password, $database);

// Verificar conexão
if ($link->connect_error) {
    error_log("Erro de conexão: " . $link->connect_error);
    die("Erro ao conectar no banco de dados.");
}

// Gerar valores aleatórios de forma mais segura
$valor_rand1 = random_int(1, 999);
$valor_rand2 = strtoupper(bin2hex(random_bytes(4)));
$host_name = gethostname();

// Query com Prepared Statement para evitar SQL Injection
$query = "INSERT INTO dados (AlunoID, Nome, Sobrenome, Endereco, Cidade, Host) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $link->prepare($query);
$stmt->bind_param("isssss", $valor_rand1, $valor_rand2, $valor_rand2, $valor_rand2, $valor_rand2, $host_name);

if ($stmt->execute()) {
    echo "Novo registro criado com sucesso!";
} else {
    error_log("Erro na inserção: " . $stmt->error);
    echo "Erro ao inserir no banco.";
}

// Fechar conexão
$stmt->close();
$link->close();
?>
