<?php
// 1. Inicia a sessão
session_start();

// Variáveis de controle
$esta_logado = isset($_SESSION['usuario_id']);
$foto_perfil = "";
$nome_usuario = "";
$link_painel = "#";
$texto_botao = "Comece sua avaliação"; 

if ($esta_logado) {
    // Conecta ao banco
    include 'php/conexao.php';
    
    $usuario_id = $_SESSION['usuario_id'];
    $nome_usuario = $_SESSION['usuario_nome'];
    $tipo_usuario = $_SESSION['usuario_tipo'];

    // Define link e texto do botão da capa
    if ($tipo_usuario == 'aluno') {
        $link_painel = "painel_aluno.php";
        $texto_botao = "Acessar meu Treino";
    } else {
        $link_painel = "painel_treinador.php";
        $texto_botao = "Acessar meus Alunos";
    }

    // Busca foto
    $sql = "SELECT foto FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $dados = $resultado->fetch_assoc();
        if (!empty($dados['foto']) && file_exists($dados['foto'])) {
            $foto_perfil = $dados['foto'] . "?v=" . time();
        }
    }
    
    // Busca profissionais
    $sql_profissionais = "SELECT * FROM usuarios WHERE tipo IN ('treinador', 'nutricionista') ORDER BY tipo DESC";
    $result_profissionais = $conn->query($sql_profissionais);
} else {
    include 'php/conexao.php';
    $sql_profissionais = "SELECT * FROM usuarios WHERE tipo IN ('treinador', 'nutricionista') ORDER BY tipo DESC";
    $result_profissionais = $conn->query($sql_profissionais);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Life Fit</title>
    <link rel="stylesheet" href="style.css">
    
    <style>
        /* CSS do Menu do Usuário */
        .nav-avatar {
            width: 45px; height: 45px; border-radius: 50%; object-fit: cover;
            border: 2px solid #8A2BE2; cursor: pointer; transition: transform 0.2s;
        }
        .nav-avatar:hover { transform: scale(1.1); }
        
        .user-menu-container {
            position: relative; display: flex; align-items: center;
            gap: 10px; height: 100%; padding-bottom: 5px;
        }

        .home-dropdown {
            display: none; position: absolute; top: 100%; right: 0;
            background-color: var(--card-bg, #fff); min-width: 180px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2); border-radius: 8px;
            z-index: 1000; padding: 10px 0; margin-top: 10px; 
        }

        /* A PONTE INVISÍVEL */
        .home-dropdown::before {
            content: ""; position: absolute; top: -20px; left: 0;
            width: 100%; height: 20px; background: transparent;
        }
        
        .dark-mode .home-dropdown { background-color: #1f1f1f; border: 1px solid #333; }
        .user-menu-container:hover .home-dropdown { display: block; }

        .home-dropdown a {
            color: #333; padding: 12px 20px; text-decoration: none;
            display: block; font-size: 0.95rem; transition: background 0.2s;
        }
        .dark-mode .home-dropdown a { color: #fff; }
        .home-dropdown a:hover { background-color: #f1f1f1; color: #8A2BE2; }
        .dark-mode .home-dropdown a:hover { background-color: #333; }
    </style>

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

    <header class="navbar">
        <div class="container">
            <a href="#inicio" class="logo">Life fit</a>
            <nav>
                <ul class="nav-links">
                    <li><a href="#inicio">Início</a></li>
                    <li><a href="#profissionais">Equipe</a></li>
                    <li><a href="#calculo">Cálculo</a></li>
                    <li><a href="#sobre-nos">Sobre Nós</a></li>
                </ul>
            </nav>

            <div class="nav-actions">
                <div class="theme-switch-wrapper">
                    <label class="theme-switch" for="checkbox-theme">
                        <input type="checkbox" id="checkbox-theme" />
                        <div class="slider round">
                            <span class="icon-sun">☀️</span>
                            <span class="icon-moon">🌙</span>
                        </div>
                    </label>
                </div>

                <?php if ($esta_logado): ?>
                    <div class="user-menu-container">
                        <span style="font-size: 0.9em; font-weight: bold;">Olá, <?php echo htmlspecialchars($nome_usuario); ?></span>
                        
                        <?php if ($foto_perfil): ?>
                            <img src="<?php echo $foto_perfil; ?>" alt="Perfil" class="nav-avatar">
                        <?php else: ?>
                            <span style="font-size: 2rem; cursor: pointer;">👤</span>
                        <?php endif; ?>

                        <div class="home-dropdown">
                            <a href="<?php echo $link_painel; ?>"><strong>Meu Painel</strong></a>
                            <a href="#">Configurações</a>
                            <hr style="border: 0; border-top: 1px solid #eee;">
                            <a href="php/logout.php" style="color: red;">Sair</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.html">
                        <button id="open-modal-btn" class="btn">Login</button>
                    </a>
                <?php endif; ?>

            </div>
        </div>
    </header>

    <main>
        <section id="inicio" class="hero">
            <div class="container hero-content">
                <div class="hero-text">
                    <h1>Transforme seu corpo, eleve sua mente.</h1>
                    <p>Na Life Fit, oferecemos o melhor ambiente e os melhores profissionais para você alcançar seus objetivos de forma saudável e eficiente.</p>
                    
                    <?php if ($esta_logado): ?>
                        <a href="<?php echo $link_painel; ?>" class="btn btn-primary"><?php echo $texto_botao; ?></a>
                    <?php else: ?>
                        <a href="#calculo" class="btn btn-primary">Comece sua avaliação</a>
                    <?php endif; ?>
                    
                </div>
                <div class="hero-image">
                    <img src="https://images.unsplash.com/photo-1571902943202-507ec2618e8f?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=1200" alt="Pessoa se exercitando na academia">
                </div>
            </div>
        </section>

        <section id="profissionais" class="section">
            <div class="container">
                <h2>Nossos Profissionais</h2>
                <div class="trainers-grid">
                    <?php 
                    if ($result_profissionais && $result_profissionais->num_rows > 0) {
                        while($row = $result_profissionais->fetch_assoc()) {
                            
                            $nome = htmlspecialchars($row['nome']);
                            $id_prof = $row['id'];
                            $tipo = $row['tipo'];
                            
                            $foto_exibir = "";
                            if (!empty($row['foto']) && file_exists($row['foto'])) {
                                $foto_exibir = $row['foto'] . "?v=" . time();
                            } else {
                                if ($tipo == 'treinador') {
                                    $foto_exibir = "https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?w=400&q=80"; 
                                } else {
                                    $foto_exibir = "https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=400&q=80";
                                }
                            }

                            if ($tipo == 'treinador') {
                                $cargo = "TREINADOR";
                                $desc = "Especialista em Performance";
                            } else {
                                $cargo = "NUTRICIONISTA";
                                $desc = "Nutrição Esportiva";
                            }

                            echo '<div class="trainer-card">';
                            echo '  <img src="' . $foto_exibir . '" alt="' . $nome . '" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 15px;">';
                            echo '  <h3>' . $nome . '</h3>';
                            echo '  <p style="color: var(--primary-color); font-weight: bold; text-transform: uppercase; margin: 5px 0;">' . $cargo . '</p>';
                            echo '  <p style="font-size: 0.9em; color: #666; margin-bottom: 15px;">' . $desc . '</p>';
                            
                            // BOTÃO SÓ PARA ALUNOS
                            if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] == 'aluno') {
                                echo '<button onclick="escolherProfissional('.$id_prof.', \''.$tipo.'\')" class="btn" style="padding: 8px 15px; font-size: 0.8rem; background-color: #6C63FF; color: white; border: none; border-radius: 5px; cursor: pointer;">Escolher como meu ' . ucfirst($tipo) . '</button>';
                            }
                            
                            echo '</div>';
                        }
                    } else {
                        echo '<p>Ainda não temos profissionais cadastrados no sistema.</p>';
                    }
                    ?>
                </div>
            </div>
        </section>

        <section id="calculo" class="section section-dark">
            <div class="container">
                <h2>Calculadora de Saúde Física</h2>
                <p>Insira seus dados abaixo para uma avaliação inicial baseada no seu IMC.</p>
                <form id="calc-form" class="calc-form">
                    <div class="form-group">
                        <label for="peso">Peso (kg)</label>
                        <input type="number" id="peso" step="0.1" placeholder="Ex: 75.5" required>
                    </div>
                    <div class="form-group">
                        <label for="altura">Altura (cm)</label>
                        <input type="number" id="altura" placeholder="Ex: 180" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Calcular Agora</button>
                </form>
                <div id="resultado" class="resultado hidden"></div>
            </div>
        </section>

        <section id="sobre-nos" class="section">
            <div class="container">
                <h2>Sobre a Life Fit</h2>
                <p>Fundada em 2025, Life Fit nasceu com a missão de democratizar o acesso à saúde e ao bem-estar.</p>
            </div>
        </section>

    </main>

    <footer>
        <div class="container">
            <p><strong>Endereço:</strong> Rua do sol, 123 - Centro, São Luis - MA</p>
            <p><strong>Telefone:</strong> (98) 99999-8888</p>
            <p>&copy; 2025 Life Fit. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script>
    function escolherProfissional(idProfissional, tipoProfissional) {
        if(!confirm("Deseja escolher este profissional como seu " + tipoProfissional + "?")) {
            return;
        }

        const formData = new FormData();
        formData.append('id_profissional', idProfissional);
        formData.append('tipo_profissional', tipoProfissional);

        fetch('php/api/escolher_profissional.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                alert(data.message);
                // location.reload(); 
            } else {
                alert("Erro: " + data.message);
            }
        })
        .catch(error => console.error('Erro:', error));
    }
    </script>

    <script src="script.js"></script>
    <script src="theme-toggle.js"></script>
</body>
</html>