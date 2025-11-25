<?php
// VERIFICAÇÃO IMPORTANTE: Inicia a sessão se ela ainda não existir
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado. Se não estiver, manda pro login.
if (!isset($_SESSION['usuario_id'])) {
    // Ajuste o caminho "index.html" conforme a estrutura das suas pastas
    header("Location: ../index.html"); 
    exit;
}

// Pega o nome de forma segura. Se estiver vazio, usa "Usuário"
$nome_usuario = $_SESSION['usuario_nome'] ?? 'Aluno';
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
            <span>👤</span>
            <div class="dropdown-menu">
                <a href="#">Meu Perfil</a>
                <a href="#">Configurações</a>
                <a href="php/logout.php" class="btn-sair">Sair</a>
            </div>
        </div>
    </div>
</header>