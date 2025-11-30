<?php
error_reporting(0);
ini_set('display_errors', 0);
session_start();
header('Content-Type: application/json');

if (!file_exists('../conexao.php')) exit;
include '../conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['treino' => '', 'dieta' => '']);
    exit;
}

$aluno_id = $_SESSION['usuario_id'];

// Busca TODAS as linhas de planos para este aluno
$sql = "SELECT treino, dieta FROM planos WHERE aluno_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $aluno_id);
$stmt->execute();
$resultado = $stmt->get_result();

$treino_final = "";
$dieta_final = "";

// LOOP MÁGICO: Lê linha por linha e preenche o que falta
while ($linha = $resultado->fetch_assoc()) {
    // Se nessa linha tiver treino, guarda ele
    if (!empty($linha['treino'])) {
        $treino_final = $linha['treino'];
    }
    // Se nessa linha tiver dieta, guarda ela
    if (!empty($linha['dieta'])) {
        $dieta_final = $linha['dieta'];
    }
}

// Entrega o resultado combinado
echo json_encode([
    'treino' => $treino_final ?: 'Aguardando plano do treinador...',
    'dieta' => $dieta_final ?: 'Aguardando plano do nutricionista...'
]);
?>