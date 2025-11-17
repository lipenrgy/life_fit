// Este script controla o CLIQUE no botão
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');

    if (themeToggle) {
        // Define o ícone inicial com base no estado atual
        if (document.documentElement.classList.contains('dark-mode')) {
            themeToggle.textContent = '☀️'; // Sol
        } else {
            themeToggle.textContent = '🌙'; // Lua
        }

        // Adiciona o evento de clique
        themeToggle.addEventListener('click', () => {
            // Alterna a classe no HTML
            document.documentElement.classList.toggle('dark-mode');

            // Salva a preferência e atualiza o ícone
            if (document.documentElement.classList.contains('dark-mode')) {
                localStorage.setItem('theme', 'dark');
                themeToggle.textContent = '☀️';
            } else {
                localStorage.setItem('theme', 'light');
                themeToggle.textContent = '🌙';
            }
        });
    }
});