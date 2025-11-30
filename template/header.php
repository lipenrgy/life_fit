<?php
// 1. Inicia sessão se não existir
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Verifica login
if (!isset($_SESSION['usuario_id'])) {
    // Correto: aponta para php
    header("Location: ../index.php"); 
    exit;
}
// 3. Conecta ao banco para buscar a foto atualizada
// Usamos __DIR__ para garantir que ele acha a pasta php independente de onde o header é chamado
require_once __DIR__ . '/../php/conexao.php';

$usuario_id = $_SESSION['usuario_id'];
$nome_usuario = $_SESSION['usuario_nome'] ?? 'Aluno';
$foto_perfil = null;

// Busca a foto no banco
$sql = "SELECT foto FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $dados = $resultado->fetch_assoc();
    // Verifica se o campo não está vazio e se o arquivo existe fisicamente
    if (!empty($dados['foto']) && file_exists(__DIR__ . '/../' . $dados['foto'])) {
        // Adiciona time() para evitar cache (a foto atualiza na hora quando troca)
        $foto_perfil = $dados['foto'] . "?v=" . time();
    }
}
?>

<header class="painel-header">
    <h1>Bem-vindo, <?php echo htmlspecialchars($nome_usuario); ?>!</h1>

    <div class="header-controls">
        <div class="theme-switch-wrapper">
            <label class="theme-switch" for="checkbox-theme">
                <input type="checkbox" id="checkbox-theme" />
                <div class="slider round">
                    <span class="icon-sun">☀️</span>
                    <span class="icon-moon">🌙</span>
                </div>
            </label>
        </div>

        <div class="user-icon-painel">
            <?php if ($foto_perfil): ?>
                <img src="<?php echo $foto_perfil; ?>" alt="Perfil" class="user-avatar">
            <?php else: ?>
                <span>👤</span>
            <?php endif; ?>

            <div class="dropdown-menu">
                <a href="#" id="btn-meu-perfil">Meu Perfil</a>
                <a href="#">Configurações</a>
                <a href="php/logout.php" class="btn-sair">Sair</a>
            </div>
        </div>
    </div>
</header>