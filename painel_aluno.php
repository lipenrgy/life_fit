<?php 
// Inclui o cabeçalho (que já tem o session_start e o visual do topo)
include 'template/header.php'; 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Painel - Life Fit</title>
    <link rel="stylesheet" href="painel.css">
    <style>
        /* Estilos específicos para o painel do aluno */
        .plano-display {
            background-color: var(--fundo-secundario);
            padding: 25px;
            border-radius: 8px;
            box-shadow: var(--sombra);
            margin-bottom: 30px;
        }
        .plano-display h2 {
            color: var(--violeta);
            margin-top: 0;
            border-bottom: 2px solid var(--fundo-primario);
            padding-bottom: 10px;
        }
        .plano-conteudo {
            white-space: pre-wrap;
            line-height: 1.7;
            font-size: 1rem;
            color: var(--texto-secundario);
        }
        .painel-container-aluno {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
        }
        /* Estilo para o título que movemos para o main */
        .titulo-pagina {
            color: var(--violeta);
            margin-bottom: 20px;
        }
    </style>
    <script>
        // Script para evitar "flash" do tema claro ao carregar
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

    <main class="painel-container-aluno">
        
        <h1 class="titulo-pagina">Meu Painel</h1>
        
        <section class="plano-display">
            <h2>Meu Plano de Treino</h2>
            <div id="treino-conteudo" class="plano-conteudo">
                Carregando treino...
            </div>
        </section>

        <section class="plano-display">
            <h2>Minha Dieta</h2>
            <div id="dieta-conteudo" class="plano-conteudo">
                Carregando dieta...
            </div>
        </section>

    </main>

    <script src="painel_aluno.js"></script>
    <script src="theme-toggle.js"></script>
</body>
</html>