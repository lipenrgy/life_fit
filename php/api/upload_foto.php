<?php
session_start();
include '../conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    die("Acesso negado.");
}

$id_usuario = $_SESSION['usuario_id'];
$tipo_usuario = $_SESSION['usuario_tipo']; // Pegamos o tipo para saber para onde voltar

// Verifica se o arquivo foi enviado
if (isset($_FILES['foto_perfil'])) {
    $arquivo = $_FILES['foto_perfil'];

    // 1. Validações Básicas
    if ($arquivo['error']) {
        die("Falha ao enviar arquivo.");
    }

    // Pasta onde vamos salvar
    $pasta = "../../uploads/";
    
    // Pega a extensão do arquivo (jpg, png)
    $nomeDoArquivo = $arquivo['name'];
    $extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION));

    // Validar extensão
    if ($extensao != "jpg" && $extensao != "png" && $extensao != "jpeg") {
        die("Tipo de arquivo não aceito. Apenas JPG e PNG.");
    }

    // 2. Cria um nome único para a foto
    $novoNome = "perfil_" . $id_usuario . "." . $extensao;
    
    // Caminhos
    $caminhoNoDisco = $pasta . $novoNome;
    $caminhoNoBanco = "uploads/" . $novoNome;

    // 3. Move o arquivo
    if (move_uploaded_file($arquivo['tmp_name'], $caminhoNoDisco)) {
        
        // 4. Atualiza o banco
        $sql = "UPDATE usuarios SET foto = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $caminhoNoBanco, $id_usuario);
        
        if ($stmt->execute()) {
            
            // --- AQUI ESTÁ A CORREÇÃO ---
            // Define para onde o usuário vai ser redirecionado
            $url_destino = "";
            
            if ($tipo_usuario == 'aluno') {
                $url_destino = "../../painel_aluno.php";
            } else {
                // Se for treinador ou nutricionista
                $url_destino = "../../painel_treinador.php";
            }

            echo "<script>
                    alert('Foto atualizada com sucesso!');
                    window.location.href = '$url_destino';
                  </script>";
            // ---------------------------

        } else {
            echo "Erro ao salvar no banco.";
        }
    } else {
        echo "Erro ao mover o arquivo para a pasta uploads.";
    }
}
?>