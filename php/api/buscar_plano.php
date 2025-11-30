<?php
// php/api/buscar_plano.php
include '../conexao.php';
header('Content-Type: application/json');

$aluno_id = $_GET['aluno_id'] ?? 0;

if ($aluno_id == 0) {
    echo json_encode(['treino' => '', 'dieta' => '']);
    exit;
}

// Busca o plano na tabela 'planos'
$sql = "SELECT treino, dieta FROM planos WHERE aluno_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $aluno_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $plano = $resultado->fetch_assoc();
    echo json_encode($plano);
} else {
    // Se não tiver plano ainda, retorna vazio
    echo json_encode(['treino' => '', 'dieta' => '']);
}

$stmt->close();
$conn->close();
?>