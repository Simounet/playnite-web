(() => {
    [...document.querySelectorAll('.game-container')].map(el => {
        el.addEventListener('click', () => {
            const info = el.querySelector('.info');
            info.hidden = !info.hidden;
        });
    });
    [...document.querySelectorAll('[data-toggle-target]')].map(el => {
        el.addEventListener('click', () => {
            if(el.dataset.toggleHide) {
                document.querySelector('[data-toggle-id=' + el.dataset.toggleHide + ']').hidden = true;
            }
            const targetEl = document.querySelector('[data-toggle-id=' + el.dataset.toggleTarget + ']');
            const newState = el.dataset.toggleAction ?
                el.dataset.toggleAction !== 'show' : !targetEl.hidden;
            targetEl.hidden = newState;
        });
    });
    const imgSizeEl = document.querySelector('#setting-cover-size');
    imgSizeEl.addEventListener('change', () => {
        document.documentElement.style.setProperty('--cover-height', `${imgSizeEl.value}px`);
        document.documentElement.style.setProperty('--cover-width', `${parseInt(imgSizeEl.value) * .75}px`);
        if(settings.gameNameDisplay.state === true) {
            const show = imgSizeEl.value === '100';
            settings.gameNameDisplay.toggle(show);
        }
    });

    const settings = {
        gameNameDisplay: {
            state: true,

            toggle: function(action) {
                document.documentElement.classList[action ? 'add' : 'remove']('name-hidden');
                this.state = !action;
            }
        }
    };

    const viewButtons = {
        listClass: 'list-view',
        gridClass: 'grid-view',

        list: document.querySelector('[data-toggle-view="list"]'),
        grid: document.querySelector('[data-toggle-view="grid"]'),
        view: document.querySelector('[data-toggle-id="view"]'),

        select: function(view) {
            this.view.classList.add(view === 'list' ? this.listClass : this.gridClass);
            this.view.classList.remove(view === 'list' ? this.gridClass : this.listClass);
            const selected = view === 'list' ?
                this.list : this.grid;
            const unselected = view === 'list' ?
                this.grid : this.list;
            selected.classList.add('button-primary');
            unselected.classList.remove('button-primary');
            this.setCookie(view);
        },

        setCookie: function(view) {
            const date = new Date();
            date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
            document.cookie = "settings-view=" + view + "; expires=" + date.toUTCString() + "; path=/; samesite=strict";
        },

        init: function() {
            this.list.addEventListener('click', () => {
                this.select('list');
            });
            this.grid.addEventListener('click', () => {
                this.select('grid');
            });
        }
    }
    viewButtons.init();

    const gameNameDisplayedEl = document.querySelector('#setting-game-name-displayed');
    gameNameDisplayedEl.addEventListener('change', () => {
        settings.gameNameDisplay.toggle(!gameNameDisplayedEl.checked);
    });
    const input = document.getElementById('games-input');
    new Awesomplete(input, {
        replace: function(suggestion) {
            this.input.value = suggestion.label;
        }
    });

    document.addEventListener('awesomplete-selectcomplete', function(t) {
        window.location.hash = t.text.value;
    })
})();
