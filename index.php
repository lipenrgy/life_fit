<?php
// AJUSTE: O caminho agora aponta para a pasta 'php'
include 'php/conexao.php';

// Buscar Treinadores e Nutricionistas
$sql_profissionais = "SELECT * FROM usuarios WHERE tipo IN ('treinador', 'nutricionista') ORDER BY tipo DESC";
$result_profissionais = $conn->query($sql_profissionais);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Life Fit</title>
    <link rel="stylesheet" href="style.css">
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

                <a href="login.html">
                    <button id="open-modal-btn" class="btn">Login</button>
                </a>
                <div id="user-icon" class="user-icon hidden"><span>👤</span></div>
            </div>
        </div>
    </header>

    <main>
        <section id="inicio" class="hero">
            <div class="container hero-content">
                <div class="hero-text">
                    <h1>Transforme seu corpo, eleve sua mente.</h1>
                    <p>Na Life Fit, oferecemos o melhor ambiente e os melhores profissionais para você alcançar seus objetivos de forma saudável e eficiente.</p>
                    <a href="#calculo" class="btn btn-primary">Comece sua avaliação</a>
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
                            
                            // Lógica simples para definir o título e a descrição
                            $nome = htmlspecialchars($row['nome']);
                            $tipo = $row['tipo'];
                            
                            if ($tipo == 'treinador') {
                                $cargo = "Treinador";
                                $desc = "Especialista em Performance";
                                // Imagem aleatória de homem a treinar
                                $img = "https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?w=400&q=80"; 
                            } else {
                                $cargo = "Nutricionista";
                                $desc = "Nutrição Esportiva";
                                // Imagem aleatória de comida saudável/mulher
                                $img = "https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=400&q=80";
                            }

                            // Renderiza o Cartão HTML
                            echo '<div class="trainer-card">';
                            echo '  <img src="' . $img . '" alt="' . $nome . '">';
                            echo '  <h3>' . $nome . '</h3>';
                            echo '  <p style="color: var(--primary-color); font-weight: bold; text-transform: uppercase;">' . $cargo . '</p>';
                            echo '  <p>' . $desc . '</p>';
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
                <div id="resultado" class="resultado hidden">
                    </div>
            </div>
        </section>

        <section id="sobre-nos" class="section">
            <div class="container">
                <h2>Sobre a Life Fit</h2>
                <p>Fundada em 2025, Life Fit nasceu com a missão de democratizar o acesso à saúde e ao bem-estar. Nossos valores são o compromisso com o resultado dos alunos, a excelência no atendimento e a inovação constante em métodos e equipamentos.</p>
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

    <script src="script.js"></script>
    <script src="theme-toggle.js"></script>
</body>
</html>