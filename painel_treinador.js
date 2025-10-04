document.addEventListener('DOMContentLoaded', function() {
    const listaAlunosUL = document.getElementById('alunos-ul');
    const nomeAlunoTitulo = document.getElementById('aluno-selecionado-nome');
    const formPlano = document.getElementById('form-plano');
    const inputAlunoId = document.getElementById('aluno-id-hidden');
    const textareaTreino = document.getElementById('treino');
    const textareaDieta = document.getElementById('dieta');
    let liAlunos = [];

    // Função para carregar a lista de alunos quando a página abre
    function carregarAlunos() {
        fetch('php/api/buscar_alunos.php')
            .then(response => response.json())
            .then(data => {
                listaAlunosUL.innerHTML = ''; // Limpa a lista antes de adicionar os novos
                if (data.length > 0) {
                    data.forEach(aluno => {
                        const li = document.createElement('li');
                        li.textContent = aluno.nome;
                        li.dataset.id = aluno.id; // Guarda o ID do aluno no elemento li
                        listaAlunosUL.appendChild(li);
                    });
                    liAlunos = document.querySelectorAll('#alunos-ul li');
                    adicionarEventoClickAlunos();
                } else {
                    listaAlunosUL.innerHTML = '<li>Nenhum aluno encontrado.</li>';
                }
            })
            .catch(error => console.error("Erro ao buscar alunos:", error));
    }

    function adicionarEventoClickAlunos() {
        liAlunos.forEach(li => {
            li.addEventListener('click', function() {
                // Remove a classe 'active' de todos os outros
                liAlunos.forEach(item => item.classList.remove('active'));
                // Adiciona a classe 'active' ao clicado
                this.classList.add('active');
                
                const alunoId = this.dataset.id;
                const alunoNome = this.textContent;
                selecionarAluno(alunoId, alunoNome);
            });
        });
    }

    // Função chamada ao clicar em um aluno
    function selecionarAluno(id, nome) {
        nomeAlunoTitulo.textContent = `Plano de ${nome}`;
        inputAlunoId.value = id; // Preenche o ID no input escondido do formulário

        // Busca o plano existente do aluno
        fetch(`php/api/buscar_plano.php?aluno_id=${id}`)
            .then(response => response.json())
            .then(data => {
                textareaTreino.value = data.treino || '';
                textareaDieta.value = data.dieta || '';
                formPlano.classList.remove('hidden'); // Mostra o formulário
            })
            .catch(error => console.error("Erro ao buscar plano:", error));
    }

    // Evento de envio do formulário para salvar/atualizar o plano
    formPlano.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(formPlano);

        fetch('php/api/salvar_plano.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
            } else {
                alert('Erro: ' + data.message);
            }
        })
        .catch(error => console.error("Erro ao salvar plano:", error));
    });

    // Inicia tudo carregando a lista de alunos
    carregarAlunos();
});