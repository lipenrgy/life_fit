<?php 
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
        .painel-container-aluno {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
        }
        .plano-display {
            background-color: #fff; /* Fundo branco para garantir contraste */
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            border: 1px solid #eee;
        }
        .plano-display h2 {
            color: #6C63FF; /* Cor violeta */
            margin-top: 0;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
        }
        .plano-conteudo {
            white-space: pre-wrap; /* Mantém os parágrafos */
            line-height: 1.6;
            font-size: 1.1rem;
            color: #333; /* Cor escura para garantir leitura */
            min-height: 50px;
        }
        /* Modo escuro */
        .dark-mode .plano-display {
            background-color: #2c2c2c;
            border-color: #444;
        }
        .dark-mode .plano-conteudo {
            color: #e0e0e0;
        }
    </style>
</head>
<body>

    <main class="painel-container-aluno">
        
        <h1 style="color: #6C63FF;">Meu Painel</h1>
        
        <section class="plano-display">
            <h2>Meu Plano de Treino</h2>
            <div id="treino-conteudo" class="plano-conteudo">
                ⏳ Buscando seu treino...
            </div>
        </section>

        <section class="plano-display">
            <h2>Minha Dieta</h2>
            <div id="dieta-conteudo" class="plano-conteudo">
                ⏳ Buscando sua dieta...
            </div>
        </section>

    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const divTreino = document.getElementById('treino-conteudo');
        const divDieta = document.getElementById('dieta-conteudo');

        fetch('php/api/buscar_meu_plano.php')
            .then(response => {
                // Verifica se a resposta é válida antes de tentar ler o JSON
                if (!response.ok) {
                    throw new Error('Erro na rede ou arquivo não encontrado');
                }
                return response.json();
            })
            .then(data => {
                console.log("Dados recebidos:", data); // Para debug no F12

                if (data.status === 'error') {
                    divTreino.innerHTML = '<span style="color:red">Sessão expirada. Faça login novamente.</span>';
                } else {
                    // Atualiza o texto visualmente
                    divTreino.innerText = data.treino;
                    divDieta.innerText = data.dieta;
                }
            })
            .catch(error => {
                console.error("Erro no fetch:", error);
                divTreino.innerHTML = '<span style="color:red">Erro ao carregar dados. Verifique o console.</span>';
                divDieta.innerHTML = '<span style="color:red">Erro ao carregar dados.</span>';
            });
    });
    </script>

    <script src="theme-toggle.js"></script>
</body>
</html>