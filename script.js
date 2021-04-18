(() => {
    [...document.querySelectorAll('.game-container')].map(el => {
        el.addEventListener('click', () => {
            const info = el.querySelector('.info');
            info.hidden = !info.hidden;
        });
    });
    const imgSizeEl = document.querySelector('#cover-size-range');
    imgSizeEl.addEventListener('change', () => {
        document.documentElement.style.setProperty('--cover-height', `${imgSizeEl.value}px`);
        document.documentElement.style.setProperty('--cover-width', `${parseInt(imgSizeEl.value) * .75}px`);
        const val = imgSizeEl.value === '100' ? 'add' : 'remove';
        document.documentElement.classList[val]('name-hidden');
    });
})();
