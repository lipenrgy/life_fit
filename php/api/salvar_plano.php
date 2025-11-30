<?php
// php/api/salvar_plano.php
session_start();
include '../conexao.php';
header('Content-Type: application/json');

// Pega o ID do treinador logado (da sessão)
$treinador_id = $_SESSION['usuario_id'] ?? 0;

$aluno_id = $_POST['aluno_id'] ?? 0;
$treino = $_POST['treino'] ?? '';
$dieta = $_POST['dieta'] ?? '';

if ($aluno_id == 0 || $treinador_id == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Erro de identificação. Faça login novamente.']);
    exit;
}

// Verifica se já existe um plano para este aluno
$check = $conn->prepare("SELECT id FROM planos WHERE aluno_id = ?");
$check->bind_param("i", $aluno_id);
$check->execute();
$res = $check->get_result();

if ($res->num_rows > 0) {
    // ATUALIZAR (UPDATE) se já existe
    $sql = "UPDATE planos SET treino = ?, dieta = ?, treinador_id = ? WHERE aluno_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $treino, $dieta, $treinador_id, $aluno_id);
} else {
    // CRIAR (INSERT) se não existe
    $sql = "INSERT INTO planos (aluno_id, treinador_id, treino, dieta) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $aluno_id, $treinador_id, $treino, $dieta);
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Plano salvo com sucesso!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao salvar: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>