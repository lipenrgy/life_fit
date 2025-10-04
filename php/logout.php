<?php
// Inicia a sessão para poder acessá-la.
session_start();

// Destrói todas as variáveis da sessão.
session_unset();
session_destroy();

// Redireciona o usuário para a página inicial.
header("Location: ../index.html");
exit();
?>