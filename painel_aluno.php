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
            background-color: var(--fundo-secundario);
            padding: 25px;
            border-radius: 8px;
            box-shadow: var(--sombra);
            margin-bottom: 30px;
            border: 1px solid var(--borda);
        }
        .plano-display h2 {
            color: var(--violeta);
            margin-top: 0;
            border-bottom: 2px solid var(--fundo-primario);
            padding-bottom: 10px;
        }
        .plano-conteudo {
            white-space: pre-wrap;
            line-height: 1.6;
            font-size: 1.1rem;
            color: var(--texto-secundario);
            min-height: 50px;
        }
    </style>
</head>
<body>

    <main class="painel-container-aluno">
        
        <h1 style="color: var(--violeta); margin-bottom: 20px;">Meu Painel</h1>
        
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

    <div id="modal-foto" class="modal-overlay">
        <div class="modal-content">
            <span class="close-modal" onclick="fecharModalFoto()">&times;</span>
            <h3>Alterar Foto de Perfil</h3>
            <p style="font-size: 0.9em; color: #aaa;">Escolha uma imagem JPG ou PNG</p>
            
            <form action="php/api/upload_foto.php" method="POST" enctype="multipart/form-data">
                <div class="file-upload-wrapper">
                    <input type="file" name="foto_perfil" id="foto_perfil" required accept="image/png, image/jpeg">
                </div>
                <button type="submit" class="btn" style="width: 100%; background-color: var(--violeta); color: white;">Atualizar Foto</button>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- 1. LÓGICA DE BUSCAR O PLANO (EXISTENTE) ---
        const divTreino = document.getElementById('treino-conteudo');
        const divDieta = document.getElementById('dieta-conteudo');

        fetch('php/api/buscar_meu_plano.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'error') {
                    divTreino.innerHTML = '<span style="color:red">Sessão expirada. Faça login novamente.</span>';
                } else {
                    divTreino.innerText = data.treino;
                    divDieta.innerText = data.dieta;
                }
            })
            .catch(error => {
                console.error("Erro no fetch:", error);
                divTreino.innerText = "Erro ao carregar dados.";
            });

        // --- 2. LÓGICA DO MODAL DE FOTO (NOVO) ---
        const btnPerfil = document.getElementById('btn-meu-perfil');
        
        if (btnPerfil) {
            btnPerfil.addEventListener('click', function(e) {
                e.preventDefault();
                abrirModalFoto();
            });
        }
    });

    // Funções globais para o modal
    function abrirModalFoto() {
        document.getElementById('modal-foto').classList.add('open');
    }

    function fecharModalFoto() {
        document.getElementById('modal-foto').classList.remove('open');
    }

    // Fecha ao clicar fora
    document.getElementById('modal-foto').addEventListener('click', function(e) {
        if (e.target === this) {
            fecharModalFoto();
        }
    });
    </script>

    <script src="theme-toggle.js"></script>
</body>
</html>