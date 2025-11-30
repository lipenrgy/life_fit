document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Pega os lugares onde vamos escrever
    const displayTreino = document.getElementById('meu-treino');
    const displayDieta = document.getElementById('minha-dieta');

    // 2. Busca os dados no PHP "Misturador"
    fetch('php/api/meu_plano.php')
        .then(response => response.json())
        .then(data => {
            // 3. Escreve na tela (respeitando as quebras de linha com white-space no CSS)
            if(displayTreino) displayTreino.innerText = data.treino;
            if(displayDieta) displayDieta.innerText = data.dieta;
        })
        .catch(error => {
            console.error('Erro ao buscar plano:', error);
            if(displayTreino) displayTreino.innerText = "Erro ao carregar treino.";
            if(displayDieta) displayDieta.innerText = "Erro ao carregar dieta.";
        });
});