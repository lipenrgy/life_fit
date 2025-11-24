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
    <script>
        (function() {
            const theme = localStorage.getItem('theme');
            if (theme === 'dark') {
                document.documentElement.classList.add('dark-mode');
            } else if (theme === 'light') {
                document.documentElement.classList.remove('dark-mode');
            } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.classList.add('dark-mode');
            }
        })();
    </script>
</head>
<body>

    <header class="painel-header">
        <h1>Painel do Treinador</h1>
        
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

        </div>
    </header>
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
    <script src="theme-toggle.js"></script>
</body>
</html>