<?php
// Inicia a sessão para que possamos manter o usuário logado
session_start();

include 'conexao.php';
header('Content-Type: application/json');

$email = $_POST['email'];
$senha_formulario = $_POST['senha'];

if (empty($email) || empty($senha_formulario)) {
    echo json_encode(['status' => 'error', 'message' => 'Email e senha são obrigatórios.']);
    exit;
}

// Busca o usuário pelo email
$sql = "SELECT id, nome, senha FROM usuarios WHERE email = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

// Verifica se encontrou algum usuário
if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();

    // Compara a senha do formulário com a senha criptografada (hash) do banco
    if (password_verify($senha_formulario, $usuario['senha'])) {
        // Senha correta! Login bem-sucedido.
        // Armazena informações do usuário na sessão
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        
        echo json_encode(['status' => 'success', 'message' => 'Login realizado com sucesso!']);
    } else {
        // Senha incorreta
        echo json_encode(['status' => 'error', 'message' => 'Email ou senha inválidos.']);
    }
} else {
    // Usuário não encontrado
    echo json_encode(['status' => 'error', 'message' => 'Email ou senha inválidos.']);
}

$stmt->close();
$conexao->close();
?>