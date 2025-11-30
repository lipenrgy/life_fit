<?php
// Exibe erros para ajudar na depuração (podes remover em produção)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Inclui o arquivo de conexão
include 'conexao.php';

// Define o cabeçalho como JSON para a resposta
header('Content-Type: application/json');

// Pega os dados enviados pelo formulário via POST
// O uso de '??' evita erros de "Undefined index" se o campo não existir
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';
$tipo = $_POST['tipo'] ?? 'aluno'; // Padrão aluno se não vier nada

// Validação simples
if (empty($nome) || empty($email) || empty($senha)) {
    echo json_encode(['status' => 'error', 'message' => 'Todos os campos são obrigatórios.']);
    exit;
}

// Criptografa a senha
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// Prepara a query SQL
$sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)";

// CORREÇÃO: Usamos $conn aqui, pois é assim que está no conexao.php
$stmt = $conn->prepare($sql);

if (!$stmt) {
    // Se houver erro na preparação (ex: nome da tabela errado), avisa
    echo json_encode(['status' => 'error', 'message' => 'Erro interno no banco: ' . $conn->error]);
    exit;
}

$stmt->bind_param("ssss", $nome, $email, $senha_hash, $tipo);

// Executa a query
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Usuário registrado com sucesso!']);
} else {
    // Verifica se o erro é de email duplicado (Erro 1062 no MySQL)
    if ($conn->errno == 1062) {
        echo json_encode(['status' => 'error', 'message' => 'Este email já está cadastrado.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao registrar usuário: ' . $stmt->error]);
    }
}

// Fecha a declaração e a conexão
$stmt->close();
$conn->close();
?>