<?php
session_start();
include '../conexao.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([]);
    exit;
}

$meu_id = $_SESSION['usuario_id'];
$meu_tipo = $_SESSION['usuario_tipo'];

// Lógica de Filtragem
if ($meu_tipo == 'treinador') {
    // Se sou treinador, busco alunos onde a coluna 'meu_treinador_id' é igual ao meu ID
    $sql = "SELECT id, nome FROM usuarios WHERE tipo = 'aluno' AND meu_treinador_id = ? ORDER BY nome ASC";
} elseif ($meu_tipo == 'nutricionista') {
    // Se sou nutricionista, busco alunos onde a coluna 'meu_nutricionista_id' é igual ao meu ID
    $sql = "SELECT id, nome FROM usuarios WHERE tipo = 'aluno' AND meu_nutricionista_id = ? ORDER BY nome ASC";
} else {
    // Se for outra coisa, não retorna nada
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $meu_id);
$stmt->execute();
$result = $stmt->get_result();

$alunos = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $alunos[] = $row;
    }
}

echo json_encode($alunos);
$stmt->close();
$conn->close();
?>