<?php
// php/api/escolher_profissional.php
session_start();

// Limpa qualquer texto/espaço anterior para não quebrar o JSON
ob_clean();

include '../conexao.php';
header('Content-Type: application/json');

// 1. Verifica login
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'aluno') {
    echo json_encode(['status' => 'error', 'message' => 'Você precisa estar logado como Aluno.']);
    exit;
}

$id_aluno = $_SESSION['usuario_id'];
$id_profissional = $_POST['id_profissional'] ?? '';
$tipo_profissional = $_POST['tipo_profissional'] ?? '';

// 2. Valida dados
if (empty($id_profissional) || empty($tipo_profissional)) {
    echo json_encode(['status' => 'error', 'message' => 'Dados incompletos enviados.']);
    exit;
}

// 3. Define coluna
$coluna = "";
$tipo_limpo = strtolower($tipo_profissional);

if ($tipo_limpo == 'treinador') {
    $coluna = "meu_treinador_id";
} elseif ($tipo_limpo == 'nutricionista') {
    $coluna = "meu_nutricionista_id";
} else {
    echo json_encode(['status' => 'error', 'message' => 'Tipo inválido: ' . $tipo_profissional]);
    exit;
}

// 4. Executa a atualização
$sql = "UPDATE usuarios SET $coluna = ? WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Erro na preparação SQL: ' . $conn->error]);
    exit;
}

$stmt->bind_param("ii", $id_profissional, $id_aluno);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Profissional escolhido com sucesso!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao salvar no banco: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>