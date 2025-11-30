<?php
// 1. BLINDAGEM: Desativa mensagens de erro visuais que quebram o JSON
error_reporting(0);
ini_set('display_errors', 0);

session_start();
header('Content-Type: application/json');

// Tenta incluir a conexão. Se falhar, o script para sem sujar a saída.
if (!file_exists('../conexao.php')) {
    echo json_encode(['treino' => '', 'dieta' => '']);
    exit;
}
include '../conexao.php';

$aluno_id = $_GET['aluno_id'] ?? 0;
$profissional_id = $_SESSION['usuario_id'] ?? 0;

// Se faltar ID, retorna vazio (sem erro)
if ($aluno_id == 0 || $profissional_id == 0) {
    echo json_encode(['treino' => '', 'dieta' => '']);
    exit;
}

// Busca o plano específico
$sql = "SELECT treino, dieta FROM planos WHERE aluno_id = ? AND profissional_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $aluno_id, $profissional_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $plano = $resultado->fetch_assoc();
    // Garante que não vem null
    echo json_encode([
        'treino' => $plano['treino'] ?? '', 
        'dieta' => $plano['dieta'] ?? ''
    ]);
} else {
    echo json_encode(['treino' => '', 'dieta' => '']);
}

$stmt->close();
$conn->close();
?>