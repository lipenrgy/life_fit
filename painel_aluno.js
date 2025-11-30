// painel_aluno.js

document.addEventListener('DOMContentLoaded', function() {
    
    // Pegamos os elementos EXATOS que mostraste no teu HTML
    const divTreino = document.getElementById('treino-conteudo');
    const divDieta = document.getElementById('dieta-conteudo');

    function carregarMeuPlano() {
        // Chama o PHP que criámos acima
        fetch('php/api/buscar_meu_plano.php')
            .then(response => response.json())
            .then(data => {
                // Se der erro de login (status error), avisa
                if (data.status === 'error') {
                    divTreino.innerHTML = '<p style="color:red">Sessão expirada. Faça login novamente.</p>';
                    return;
                }

                // Coloca o texto que veio do banco dentro das caixas
                // Usamos innerHTML para respeitar as quebras de linha se houver
                divTreino.innerText = data.treino;
                divDieta.innerText = data.dieta;
            })
            .catch(error => {
                console.error("Erro:", error);
                divTreino.innerText = "Erro ao carregar o treino.";
                divDieta.innerText = "Erro ao carregar a dieta.";
            });
    }

    // Executa a função imediatamente
    carregarMeuPlano();
});