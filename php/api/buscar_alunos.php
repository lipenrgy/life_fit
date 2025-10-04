<?php
session_start();
include '../conexao.php'; // Acessa o arquivo de conexão que está uma pasta acima

header('Content-Type: application/json');

// Medida de segurança: verifica se o usuário é um treinador
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'treinador') {
    echo json_encode(['status' => 'error', 'message' => 'Acesso negado']);
    exit;
}

$sql = "SELECT id, nome FROM usuarios WHERE tipo = 'aluno' ORDER BY nome ASC";
$resultado = $conexao->query($sql);

$alunos = [];
if ($resultado->num_rows > 0) {
    while($linha = $resultado->fetch_assoc()) {
        $alunos[] = $linha;
    }
}

echo json_encode($alunos);

$conexao->close();
?>