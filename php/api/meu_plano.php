<?php
session_start();
include '../conexao.php'; // Acessa o arquivo de conexão

header('Content-Type: application/json');

// Medida de segurança: verifica se há um usuário logado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuário não logado.']);
    exit;
}

// Pega o ID do aluno diretamente da sessão
$aluno_id = $_SESSION['usuario_id'];

$sql = "SELECT treino, dieta FROM planos WHERE aluno_id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $aluno_id);
$stmt->execute();
$resultado = $stmt->get_result();

$resposta = [
    'treino' => 'Nenhum plano de treino definido pelo seu treinador ainda.',
    'dieta' => 'Nenhuma dieta definida pelo seu treinador ainda.'
];

if ($resultado->num_rows > 0) {
    $plano = $resultado->fetch_assoc();
    // Se o campo não estiver vazio, usa o valor do banco
    if (!empty($plano['treino'])) {
        $resposta['treino'] = htmlspecialchars($plano['treino']);
    }
    if (!empty($plano['dieta'])) {
        $resposta['dieta'] = htmlspecialchars($plano['dieta']);
    }
}

echo json_encode($resposta);

$stmt->close();
$conexao->close();
?>