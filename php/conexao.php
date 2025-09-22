<?php
// Arquivo de conexão com o banco de dados

$servidor = "localhost"; // Geralmente "localhost" no XAMPP
$usuario = "root";       // Usuário padrão do MySQL no XAMPP
$senha = "";             // Senha padrão do MySQL no XAMPP é vazia
$banco = "academia_db";  // O nome do banco de dados que criamos

// Criar a conexão
$conexao = new mysqli($servidor, $usuario, $senha, $banco);

// Checar a conexão
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

// Opcional: Definir o charset para utf8 para evitar problemas com acentos
$conexao->set_charset("utf8");
?>