document.addEventListener('DOMContentLoaded', function() {
    const treinoDiv = document.getElementById('treino-conteudo');
    const dietaDiv = document.getElementById('dieta-conteudo');

    // Função para buscar e exibir o plano do aluno
    function carregarPlano() {
        fetch('php/api/meu_plano.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na resposta do servidor.');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'error') {
                    treinoDiv.textContent = data.message;
                    dietaDiv.textContent = '';
                } else {
                    treinoDiv.textContent = data.treino;
                    dietaDiv.textContent = data.dieta;
                }
            })
            .catch(error => {
                console.error('Erro ao buscar o plano:', error);
                treinoDiv.textContent = 'Não foi possível carregar o plano. Tente novamente mais tarde.';
                dietaDiv.textContent = 'Não foi possível carregar a dieta. Tente novamente mais tarde.';
            });
    }

    // Chama a função assim que a página é carregada
    carregarPlano();
});