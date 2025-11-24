<?php

ini_set('display_errors', 1); // Força a exibição de erros
error_reporting(E_ALL); // Mostra todos os tipos de erros

// Inclui o arquivo de conexão
include 'conexao.php';


// Define o cabeçalho como JSON para a resposta
header('Content-Type: application/json');

// Pega os dados enviados pelo formulário via POST
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];

$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 'aluno'; // Pega o tipo de usuário, padrão aluno

// Validação simples (em um projeto real, a validação seria mais robusta)
if (empty($nome) || empty($email) || empty($senha)) {
    echo json_encode(['status' => 'error', 'message' => 'Todos os campos são obrigatórios.']);
    exit;
}

// Criptografa a senha usando o algoritmo padrão e mais seguro do PHP
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// Prepara a query SQL para evitar injeção de SQL
$sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("ssss", $nome, $email, $senha_hash, $tipo);
// "ssss" significa que estamos passando quatro strings como parâmetros

// Executa a query e verifica se foi bem-sucedida
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Usuário registrado com sucesso!']);
} else {
    // Verifica se o erro é de email duplicado
    if ($conexao->errno == 1062) {
        echo json_encode(['status' => 'error', 'message' => 'Este email já está cadastrado.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao registrar usuário: ' . $stmt->error]);
    }
}

// Fecha a declaração e a conexão
$stmt->close();
$conexao->close();
?>