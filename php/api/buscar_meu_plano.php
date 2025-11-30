<?php
// php/api/buscar_meu_plano.php
session_start();

// Garante que não há "lixo" de output antes do JSON
ob_clean(); 

include '../conexao.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Deslogado']);
    exit;
}

$meu_id = $_SESSION['usuario_id'];

// Busca o plano
$sql = "SELECT treino, dieta FROM planos WHERE aluno_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $meu_id);
$stmt->execute();
$resultado = $stmt->get_result();

$response = [
    'treino' => 'Aguardando plano do treinador...', 
    'dieta' => 'Aguardando plano do nutricionista...'
];

if ($resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();
    
    // Usa trim() para remover espaços vazios e verifica se tem texto real
    if (!empty(trim($row['treino']))) {
        $response['treino'] = $row['treino'];
    }
    if (!empty(trim($row['dieta']))) {
        $response['dieta'] = $row['dieta'];
    }
}

echo json_encode($response);

$stmt->close();
$conn->close();
?>