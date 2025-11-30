<?php 
include 'template/header.php'; 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Profissional - Life fit</title>
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

    <input type="hidden" id="tipo-usuario-logado" value="<?php echo isset($_SESSION['usuario_tipo']) ? $_SESSION['usuario_tipo'] : ''; ?>">

    <header class="painel-header">
        <h1>Painel do Profissional</h1>
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
                <textarea id="treino" name="treino" placeholder="Descreva o treino aqui..."></textarea>
                
                <label for="dieta"><h3>Plano de Dieta</h3></label>
                <textarea id="dieta" name="dieta" placeholder="Descreva a dieta aqui..."></textarea>
                
                <button type="submit">Salvar Plano</button>
            </form>
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
                <button type="submit" class="btn" style="width: 100%;">Atualizar Foto</button>
            </form>
        </div>
    </div>

    <script>
        // Função para abrir o modal
        function abrirModalFoto() {
            document.getElementById('modal-foto').classList.add('open');
        }

        // Função para fechar o modal
        function fecharModalFoto() {
            document.getElementById('modal-foto').classList.remove('open');
        }

        // Fecha se clicar fora da janelinha
        document.getElementById('modal-foto').addEventListener('click', function(e) {
            if (e.target === this) {
                fecharModalFoto();
            }
        });

        // Tenta encontrar o link "Meu Perfil" no menu e adicionar o clique
        // IMPORTANTE: O texto dentro do link deve ser exatamente "Meu Perfil" ou adicione um ID no header.php
        document.addEventListener('DOMContentLoaded', function() {
            const links = document.querySelectorAll('a');
            links.forEach(link => {
                if (link.textContent.trim() === "Meu Perfil") {
                    link.href = "javascript:void(0)"; // Evita recarregar a página
                    link.addEventListener('click', abrirModalFoto);
                }
            });
        });
    </script>

    <script src="painel_treinador.js"></script>
    <script src="theme-toggle.js"></script>
</body>
</html>