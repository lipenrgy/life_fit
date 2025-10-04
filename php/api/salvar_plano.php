<?php
session_start();
include '../conexao.php';

header('Content-Type: application/json');

// Segurança: verifica se é um treinador logado e se o método é POST
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'treinador' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Acesso negado ou método inválido']);
    exit;
}

$aluno_id = $_POST['aluno_id'];
$treino = $_POST['treino'];
$dieta = $_POST['dieta'];
$treinador_id = $_SESSION['usuario_id'];

if (empty($aluno_id)) {
    echo json_encode(['status' => 'error', 'message' => 'ID do aluno não fornecido.']);
    exit;
}

// SQL inteligente: Insere um novo plano. Se o aluno_id já existir (por causa da chave UNIQUE),
// ele atualiza os campos treino e dieta da linha existente.
$sql = "INSERT INTO planos (aluno_id, treinador_id, treino, dieta) 
        VALUES (?, ?, ?, ?) 
        ON DUPLICATE KEY UPDATE 
        treino = VALUES(treino), dieta = VALUES(dieta), treinador_id = VALUES(treinador_id)";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("iiss", $aluno_id, $treinador_id, $treino, $dieta);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Plano salvo com sucesso!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao salvar o plano: ' . $stmt->error]);
}

$stmt->close();
$conexao->close();
?>