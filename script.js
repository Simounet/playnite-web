(() => {
    [...document.querySelectorAll('.game-container')].map(el => {
        el.addEventListener('click', () => {
            const info = el.querySelector('.info');
            info.hidden = !info.hidden;
        });
    });
})();
