<?php
// Inicia a sessão no topo de tudo.
session_start();

// VERIFICAÇÃO DE SEGURANÇA: Se não houver um usuário logado na sessão,
// redireciona de volta para a página de login (index.html).
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.html");
    exit(); // Garante que o resto do script não seja executado.
}

// Pega o nome do usuário da sessão para exibir.
$nome_usuario = $_SESSION['usuario_nome'];
?>

<header class="painel-header">
    <h1>Bem-vindo, <?php echo htmlspecialchars($nome_usuario); ?>!</h1>
    
    <div class= "header-controls">
        <button id= "theme-toggle" class="btn-icon">🌙</button>

        <div class="user-icon-painel">
            <span>👤</span>
            <div class="dropdown-menu">
                <a href="#">Meu Perfil</a>
                <a href="#">Configurações</a>
                <a href="php/logout.php">Sair</a>
            </div>
        </div>
    </div>
</header>