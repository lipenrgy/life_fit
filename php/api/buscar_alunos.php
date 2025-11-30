<?php
// php/api/buscar_alunos.php
session_start();

// O "../" é o segredo! Ele sobe da pasta 'api' para a pasta 'php' para achar a conexão.
include '../conexao.php';

header('Content-Type: application/json');

// Busca apenas usuários do tipo 'aluno'
$sql = "SELECT id, nome FROM usuarios WHERE tipo = 'aluno' ORDER BY nome ASC";
$result = $conn->query($sql);

$alunos = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $alunos[] = $row;
    }
}

// Retorna a lista em JSON para o Javascript ler
echo json_encode($alunos);

$conn->close();
?>