(() => {
    [...document.querySelectorAll('.game-container')].map(el => {
        el.addEventListener('click', () => {
            const info = el.querySelector('.info');
            info.hidden = !info.hidden;
        });
    });
    [...document.querySelectorAll('[data-toggle-target]')].map(el => {
        el.addEventListener('click', () => {
            const info = document.querySelector('[data-toggle-id=' + el.dataset.toggleTarget + ']');
            info.hidden = !info.hidden;
        });
    });
    const imgSizeEl = document.querySelector('#setting-cover-size');
    imgSizeEl.addEventListener('change', () => {
        document.documentElement.style.setProperty('--cover-height', `${imgSizeEl.value}px`);
        document.documentElement.style.setProperty('--cover-width', `${parseInt(imgSizeEl.value) * .75}px`);
        const val = imgSizeEl.value === '100' ? 'add' : 'remove';
        document.documentElement.classList[val]('name-hidden');
    });
})();
