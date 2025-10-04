<?php
session_start();
include '../conexao.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'treinador') {
    echo json_encode(['status' => 'error', 'message' => 'Acesso negado']);
    exit;
}

$aluno_id = $_GET['aluno_id'];

if (empty($aluno_id)) {
    echo json_encode(['treino' => '', 'dieta' => '']);
    exit;
}

$sql = "SELECT treino, dieta FROM planos WHERE aluno_id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $aluno_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $plano = $resultado->fetch_assoc();
    echo json_encode($plano);
} else {
    // Se não houver plano, retorna vazio para preencher os campos
    echo json_encode(['treino' => '', 'dieta' => '']);
}

$stmt->close();
$conexao->close();
?>