<?php
session_start();
include 'conexao.php';

// Define que a resposta será sempre em formato JSON
header('Content-Type: application/json');

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

if (empty($email) || empty($senha)) {
    echo json_encode(['status' => 'error', 'message' => 'Preencha todos os campos!']);
    exit;
}

$sql = "SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();

    if (password_verify($senha, $usuario['senha'])) {
        // Login Sucesso
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_tipo'] = $usuario['tipo'];

        // Retorna sucesso e o tipo para o JS saber para onde redirecionar
        echo json_encode([
            'status' => 'success', 
            'message' => 'Login realizado!', 
            'tipo' => $usuario['tipo']
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Senha incorreta.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Email não encontrado.']);
}

$stmt->close();
$conn->close();
?>