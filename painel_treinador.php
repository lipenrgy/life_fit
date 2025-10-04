<?php 
// Esta linha inclui nosso cabeçalho dinâmico e seguro!
include 'template/header.php'; 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Treinador - Life fit</title>
    <link rel="stylesheet" href="painel.css">
</head>
<body>

    <main class="painel-container">
        <section class="lista-alunos">
            <h2>Meus Alunos</h2>
            <ul id="alunos-ul"></ul>
        </section>
        <section class="area-plano">
            <h2 id="aluno-selecionado-nome">Selecione um aluno da lista</h2>
            <form id="form-plano" class="hidden">
                <input type="hidden" id="aluno-id-hidden" name="aluno_id">
                <label for="treino"><h3>Plano de Treino</h3></label>
                <textarea id="treino" name="treino" placeholder="..."></textarea>
                <label for="dieta"><h3>Plano de Dieta</h3></label>
                <textarea id="dieta" name="dieta" placeholder="..."></textarea>
                <button type="submit">Salvar Plano</button>
            </form>
        </section>
    </main>
    <script src="painel_treinador.js"></script>
</body>
</html>