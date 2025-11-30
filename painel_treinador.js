document.addEventListener('DOMContentLoaded', function() {
    // Pegando os elementos da tela
    const listaAlunosUL = document.getElementById('alunos-ul');
    const nomeAlunoTitulo = document.getElementById('aluno-selecionado-nome');
    const formPlano = document.getElementById('form-plano');
    const inputAlunoId = document.getElementById('aluno-id-hidden');
    const textareaTreino = document.getElementById('treino');
    const textareaDieta = document.getElementById('dieta');
    let liAlunos = [];

    // 1. Carrega a lista de alunos ao abrir a página
    function carregarAlunos() {
        fetch('php/api/buscar_alunos.php')
            .then(response => response.json())
            .then(data => {
                listaAlunosUL.innerHTML = ''; 
                if (data.length > 0) {
                    data.forEach(aluno => {
                        const li = document.createElement('li');
                        li.textContent = aluno.nome;
                        li.dataset.id = aluno.id; 
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

    // 2. Adiciona o clique em cada nome da lista
    function adicionarEventoClickAlunos() {
        liAlunos.forEach(li => {
            li.addEventListener('click', function() {
                // Tira a cor do anterior e coloca no atual
                liAlunos.forEach(item => item.classList.remove('active'));
                this.classList.add('active');
                
                const alunoId = this.dataset.id;
                const alunoNome = this.textContent;
                selecionarAluno(alunoId, alunoNome);
            });
        });
    }

    // 3. Função que roda quando clica no aluno (AQUI ESTAVA O PROBLEMA)
// Função chamada ao clicar em um aluno
    function selecionarAluno(id, nome) {
        nomeAlunoTitulo.textContent = `Plano de ${nome}`;
        inputAlunoId.value = id;

        // Verifica se os campos existem na tela antes de tentar mexer
        if (textareaTreino) textareaTreino.value = 'Carregando...';
        if (textareaDieta) textareaDieta.value = 'Carregando...';

        // Busca o plano no banco de dados
        fetch(`php/api/buscar_plano.php?aluno_id=${id}`)
            .then(response => response.json())
            .then(data => {
                // Só preenche se o campo existir na tela ( !== null )
                if (textareaTreino) {
                    textareaTreino.value = data.treino || '';
                }
                
                if (textareaDieta) {
                    textareaDieta.value = data.dieta || '';
                }
                
                formPlano.classList.remove('hidden');
            })
            .catch(error => {
                console.error("Erro ao buscar plano:", error);
                if (textareaTreino) textareaTreino.value = '';
                if (textareaDieta) textareaDieta.value = '';
                formPlano.classList.remove('hidden'); 
            });
    }

    // 4. Salvar o plano com Alerta Bonito (SweetAlert)
   // Evento de envio do formulário (Versão Profissional com SweetAlert)
    formPlano.addEventListener('submit', function(event) {
        event.preventDefault(); // Impede o site de recarregar

        const formData = new FormData(formPlano);
        const btnSalvar = formPlano.querySelector('button');
        const textoOriginal = btnSalvar.innerText;

        // 1. Efeito visual no botão (feedback imediato)
        btnSalvar.innerText = "Salvando...";
        btnSalvar.disabled = true;
        btnSalvar.style.opacity = "0.7";

        fetch('php/api/salvar_plano.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Verifica se o tema escuro está ativo para ajustar a cor do alerta
            const isDarkMode = document.body.classList.contains('dark-mode');

            if (data.status === 'success') {
                // SUCESSO: Janela bonita com animação
                Swal.fire({
                    title: 'Tudo certo!',
                    text: 'O plano do aluno foi salvo com sucesso.',
                    icon: 'success',
                    confirmButtonText: 'Ótimo',
                    confirmButtonColor: '#8A2BE2', // Roxo do Life Fit
                    background: isDarkMode ? '#1e1e1e' : '#ffffff',
                    color: isDarkMode ? '#ffffff' : '#333333'
                });
            } else {
                // ERRO: Janela de aviso
                Swal.fire({
                    title: 'Ops...',
                    text: data.message || 'Erro ao salvar.',
                    icon: 'error',
                    confirmButtonColor: '#d33',
                    background: isDarkMode ? '#1e1e1e' : '#ffffff',
                    color: isDarkMode ? '#ffffff' : '#333333'
                });
            }
        })
        .catch(error => {
            console.error("Erro:", error);
            Swal.fire({
                title: 'Erro de Conexão',
                text: 'Verifique sua internet e tente novamente.',
                icon: 'warning',
                confirmButtonColor: '#8A2BE2'
            });
        })
        .finally(() => {
            // Volta o botão ao estado normal
            btnSalvar.innerText = textoOriginal;
            btnSalvar.disabled = false;
            btnSalvar.style.opacity = "1";
        });
    });

    carregarAlunos();
});