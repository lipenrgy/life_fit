document.addEventListener('DOMContentLoaded', function() {
    // Pegamos o checkbox pelo novo ID
    const toggleSwitch = document.querySelector('#checkbox-theme');
    const currentTheme = localStorage.getItem('theme');

    // 1. Verifica o tema salvo ao carregar a página
    if (currentTheme) {
        document.documentElement.classList.add(currentTheme === 'dark' ? 'dark-mode' : 'light-mode');
        
        // Se o tema for escuro, marcamos o checkbox como "checked"
        if (currentTheme === 'dark') {
            toggleSwitch.checked = true;
        }
    }

    // 2. Função que troca o tema
    function switchTheme(e) {
        if (e.target.checked) {
            document.documentElement.classList.add('dark-mode');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark-mode');
            localStorage.setItem('theme', 'light');
        }
    }

    // 3. Adiciona o ouvinte de evento 'change' (mudança de estado)
    if (toggleSwitch) {
        toggleSwitch.addEventListener('change', switchTheme, false);
    }
});