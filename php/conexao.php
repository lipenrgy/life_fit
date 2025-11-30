<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "academia_db";

// Criar a conexão e guardar na variável $conn
$conn = new mysqli($host, $usuario, $senha, $banco);

// Verificar se houve erro
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>