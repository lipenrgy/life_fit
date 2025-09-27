<?php
// Inicia a sessão ANTES de qualquer outra coisa. Isso é muito importante.
session_start();

// Inclui o arquivo de conexão
include 'conexao.php';

// Define o cabeçalho como JSON para a resposta
header('Content-Type: application/json');

// Pega os dados enviados pelo formulário
$email = $_POST['email'] ?? ''; // Usar '??' é uma forma segura de evitar erros de 'Undefined index'
$senha_formulario = $_POST['senha'] ?? '';

if (empty($email) || empty($senha_formulario)) {
    echo json_encode(['status' => 'error', 'message' => 'Email e senha são obrigatórios.']);
    exit;
}

// Prepara a query para buscar o usuário pelo email
$sql = "SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();

    // Compara a senha do formulário com a senha criptografada (hash) do banco
    if (password_verify($senha_formulario, $usuario['senha'])) {
        // Senha correta! Login bem-sucedido.
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_tipo'] = $usuario['tipo'];
        
        echo json_encode([
            'status' => 'success', 
            'message' => 'Login realizado com sucesso!',
            'tipo' => $usuario['tipo']
        ]);
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