<?php
// Desativa erros visuais para não sujar o JSON
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');

// Como este arquivo está na mesma pasta que conexao.php, o include é direto
if (file_exists('conexao.php')) {
    include 'conexao.php';
} else {
    // Caso de erro
    echo json_encode([]);
    exit;
}

// Busca Treinadores e Nutricionistas
$sql = "SELECT id, nome, tipo, foto FROM usuarios WHERE tipo IN ('treinador', 'nutricionista')";
$result = $conn->query($sql);

$profissionais = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Se não tiver foto, define uma padrão cinza
        $foto = !empty($row['foto']) ? $row['foto'] : 'https://cdn-icons-png.flaticon.com/512/1077/1077114.png';
        
        $profissionais[] = [
            'id' => $row['id'],
            'nome' => $row['nome'],
            'tipo' => ucfirst($row['tipo']), // Deixa a primeira letra maiúscula (Treinador)
            'foto' => $foto
        ];
    }
}

echo json_encode($profissionais);
$conn->close();
?>