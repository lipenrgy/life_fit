document.addEventListener('DOMContentLoaded', function() {

    // --- LÓGICA PARA CONTROLE DO MODAL ---
    const modalAcesso = document.getElementById('modal-acesso');
    const openModalBtn = document.getElementById('open-modal-btn');
    const closeModalBtn = document.querySelector('.close-modal');
    // ... (toda a lógica de abrir/fechar o modal continua igual)
    function openModal() { modalAcesso.classList.add('active'); }
    function closeModal() { modalAcesso.classList.remove('active'); }
    openModalBtn.addEventListener('click', openModal);
    closeModalBtn.addEventListener('click', closeModal);
    modalAcesso.addEventListener('click', (event) => { if (event.target === modalAcesso) closeModal(); });


    // --- LÓGICA DOS FORMULÁRIOS COM FETCH API ---
    const loginBtnHeader = document.getElementById('open-modal-btn');
    const userIcon = document.getElementById('user-icon');
    const logoutBtn = document.getElementById('logout-btn');
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');

    // -- FORMULÁRIO DE REGISTRO --
    registerForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Impede o recarregamento da página

        const formData = new FormData(registerForm);
        // Mapeia os names dos inputs para os nomes esperados pelo PHP
        formData.append('nome', document.getElementById('reg-nome').value);
        formData.append('email', document.getElementById('reg-email').value);
        formData.append('senha', document.getElementById('reg-senha').value);
        
        fetch('php/registrar.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message); // Mostra a mensagem de sucesso ou erro
            if (data.status === 'success') {
                registerForm.reset();
                closeModal();
            }
        })
        .catch(error => console.error('Erro:', error));
    });

    // -- FORMULÁRIO DE LOGIN --
    loginForm.addEventListener('submit', function(event) {
        event.preventDefault();
        
        const formData = new FormData();
        formData.append('email', document.getElementById('login-email').value);
        formData.append('senha', document.getElementById('login-senha').value);

        fetch('php/login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === 'success') {
                loginBtnHeader.classList.add('hidden');
                userIcon.classList.remove('hidden');
                closeModal();
            }
        })
        .catch(error => console.error('Erro:', error));
    });

    logoutBtn.addEventListener('click', (event) => {
        event.preventDefault();
        // Em um sistema real, aqui chamaríamos um script php/logout.php para destruir a sessão
        userIcon.classList.add('hidden');
        loginBtnHeader.classList.remove('hidden');
        alert("Você saiu da sua conta.");
    });


    // --- LÓGICA DA CALCULADORA DE IMC (sem alterações) ---
    const calcForm = document.getElementById('calc-form');
    const resultadoDiv = document.getElementById('resultado');

    calcForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const peso = parseFloat(document.getElementById('peso').value);
        const altura = parseFloat(document.getElementById('altura').value);

        if (isNaN(peso) || isNaN(altura) || peso <= 0 || altura <= 0) {
            exibirResultado('Por favor, insira valores válidos.', 'ruim');
            return;
        }

        const alturaEmMetros = altura / 100;
        const imc = peso / (alturaEmMetros * alturaEmMetros);
        const { categoria, classificacao } = getClassificacaoIMC(imc);
        
        const mensagem = `
            <h3>Seu resultado:</h3>
            <p>Seu IMC é <strong>${imc.toFixed(2)}</strong>.</p>
            <p>Classificação: <strong>${classificacao}</strong>.</p>
            <p style="margin-top: 20px;">Este é um ótimo primeiro passo! Que tal um plano feito sob medida para você?</p>
            <button id="open-register-modal-btn" class="btn btn-primary" style="margin-top: 10px;">Registre-se para um Plano Personalizado!</button>
        `;

        exibirResultado(mensagem, categoria);
        
        document.getElementById('open-register-modal-btn').addEventListener('click', openModal);
    });

    function getClassificacaoIMC(imc) {
        if (imc < 18.5) return { categoria: 'ruim', classificacao: 'Abaixo do peso (Precisa Melhorar)' };
        if (imc >= 18.5 && imc <= 24.9) return { categoria: 'bom', classificacao: 'Peso normal (Em forma)' };
        if (imc >= 25 && imc <= 29.9) return { categoria: 'medio', classificacao: 'Sobrepeso (Precisa Melhorar)' };
        return { categoria: 'ruim', classificacao: 'Obesidade (Precisa Melhorar)' };
    }

    function exibirResultado(mensagem, categoria) {
        resultadoDiv.innerHTML = mensagem;
        resultadoDiv.className = 'resultado';
        resultadoDiv.classList.add(categoria);
        resultadoDiv.classList.remove('hidden');
    }
});