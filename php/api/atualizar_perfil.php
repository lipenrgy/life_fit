<?php
session_start();
include '../conexao.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Não logado.']);
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$novo_nome = $_POST['nome'] ?? '';
$nova_senha = $_POST['nova_senha'] ?? '';

// 1. Atualizar o NOME (Obrigatório)
if (!empty($novo_nome)) {
    $sql = "UPDATE usuarios SET nome = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $novo_nome, $id_usuario);
    $stmt->execute();
    $stmt->close();
    $_SESSION['usuario_nome'] = $novo_nome;
}

// 2. Atualizar a SENHA (Com verificação de segurança)
if (!empty($nova_senha)) {
    
    // --- NOVO: Buscar a senha atual no banco ---
    $busca = $conn->prepare("SELECT senha FROM usuarios WHERE id = ?");
    $busca->bind_param("i", $id_usuario);
    $busca->execute();
    $resultado = $busca->get_result();
    $dados = $resultado->fetch_assoc();
    $senha_hash_atual = $dados['senha'];
    $busca->close();

    // --- NOVO: Verificar se a nova senha é igual à antiga ---
    // password_verify compara o texto digitado com o hash do banco
    if (password_verify($nova_senha, $senha_hash_atual)) {
        echo json_encode(['status' => 'error', 'message' => 'A nova senha não pode ser igual à atual!']);
        exit; // Para tudo aqui
    }

    // Se passou no teste, criptografa a nova senha e salva
    $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

    $sql = "UPDATE usuarios SET senha = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nova_senha_hash, $id_usuario);
    
    if ($stmt->execute()) {
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao salvar senha.']);
        exit;
    }
}

echo json_encode(['status' => 'success', 'message' => 'Dados atualizados com sucesso!']);
$conn->close();
?>