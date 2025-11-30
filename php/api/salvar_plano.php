<?php
// 1. BLINDAGEM CONTRA ERROS VISUAIS
error_reporting(0);
ini_set('display_errors', 0);

session_start();
header('Content-Type: application/json');

// Verifica conexão
if (!file_exists('../conexao.php')) {
    echo json_encode(['status' => 'error', 'message' => 'Erro interno: Conexão não encontrada.']);
    exit;
}
include '../conexao.php';

// Verifica se tem sessão
if (!isset($_SESSION['usuario_tipo'])) {
    echo json_encode(['status' => 'error', 'message' => 'Você não está logado.']);
    exit;
}

// 2. SEGURANÇA INTELIGENTE (Aceita Maiúsculas e Minúsculas)
// Pega o tipo (ex: 'Nutricionista') e transforma em 'nutricionista'
$tipo_usuario = strtolower($_SESSION['usuario_tipo']); 

// A Regra: Se NÃO for treinador E NÃO for nutricionista, bloqueia.
if (($tipo_usuario !== 'treinador' && $tipo_usuario !== 'nutricionista') || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Dica de Debug: Mostra qual tipo o sistema leu, para gente saber se der erro
    echo json_encode([
        'status' => 'error', 
        'message' => 'Acesso não autorizado. Seu tipo de usuário é: ' . $_SESSION['usuario_tipo']
    ]);
    exit;
}

// 3. Recebendo dados
$aluno_id = $_POST['aluno_id'] ?? null;
// Nutricionistas não enviam 'treino', então assumimos vazio
$treino = $_POST['treino'] ?? ''; 
// Treinadores não enviam 'dieta', então assumimos vazio
$dieta = $_POST['dieta'] ?? ''; 
$profissional_id = $_SESSION['usuario_id'];

if (empty($aluno_id)) {
    echo json_encode(['status' => 'error', 'message' => 'Erro: Aluno não identificado.']);
    exit;
}

// 4. Lógica de Salvar (Inteligente)
try {
    // Verifica se já existe um plano DESTE profissional para ESTE aluno
    $check_sql = "SELECT id FROM planos WHERE aluno_id = ? AND profissional_id = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("ii", $aluno_id, $profissional_id);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        // ATUALIZAR (Update)
        // Se for treinador, atualiza só o treino. Se for nutri, só a dieta.
        // Mas como o HTML já manda o outro campo vazio, podemos atualizar tudo
        // PORÉM, para ser perfeito, vamos atualizar apenas o campo que veio preenchido.
        
        if ($tipo_usuario === 'treinador') {
            $sql = "UPDATE planos SET treino = ? WHERE aluno_id = ? AND profissional_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sii", $treino, $aluno_id, $profissional_id);
        } else {
            // É Nutricionista
            $sql = "UPDATE planos SET dieta = ? WHERE aluno_id = ? AND profissional_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sii", $dieta, $aluno_id, $profissional_id);
        }
        
    } else {
        // CRIAR NOVO (Insert)
        $sql = "INSERT INTO planos (aluno_id, profissional_id, treino, dieta) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $aluno_id, $profissional_id, $treino, $dieta);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Plano salvo com sucesso!']);
    } else {
        throw new Exception("Erro ao executar SQL.");
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro no banco de dados.']);
}
?>