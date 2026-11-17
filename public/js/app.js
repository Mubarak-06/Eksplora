document.querySelector('.menu-toggle')?.addEventListener('click', e => {
    const button = e.currentTarget;
    const open = button.getAttribute('aria-expanded') !== 'true';
    button.setAttribute('aria-expanded', String(open));
    document.querySelector('#navigation').classList.toggle('open', open);
});
document.querySelector('.search-toggle')?.addEventListener('click', e => {
    const form = document.querySelector('#nav-search');
    form.hidden = !form.hidden;
    e.currentTarget.setAttribute('aria-expanded', String(!form.hidden));
    if (!form.hidden) form.querySelector('input').focus();
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        const form = document.querySelector('#nav-search');
        if (form && !form.hidden) {
            form.hidden = true;
            document.querySelector('.search-toggle').setAttribute('aria-expanded', 'false');
            document.querySelector('.search-toggle').focus();
        }
    }
});
document.querySelectorAll('[data-tabs]').forEach(group => {
    const tabs = [...group.querySelectorAll('[role="tab"]')];
    const activate = tab => {
        tabs.forEach(t => {
            const selected = t === tab;
            t.setAttribute('aria-selected', String(selected));
            t.tabIndex = selected ? 0 : -1;
            document.getElementById(t.getAttribute('aria-controls')).hidden = !selected;
        });
    };
    tabs.forEach((tab, i) => {
        tab.addEventListener('click', () => activate(tab));
        tab.addEventListener('keydown', e => {
            let next;
            if (e.key === 'ArrowRight') next = tabs[(i+1)%tabs.length];
            if (e.key === 'ArrowLeft') next = tabs[(i-1+tabs.length)%tabs.length];
            if (e.key === 'Home') next = tabs[0];
            if (e.key === 'End') next = tabs[tabs.length-1];
            if (next) { e.preventDefault(); activate(next); next.focus(); }
        });
    });
    const fromHash = tabs.find(t => '#'+t.getAttribute('aria-controls') === location.hash);
    if (fromHash) activate(fromHash);
});
document.querySelector('.swap')?.addEventListener('click', () => {
    const first = document.querySelector('#first'), second = document.querySelector('#second');
    [first.value, second.value] = [second.value, first.value];
});
document.querySelectorAll('.category-options input').forEach(input => input.addEventListener('change', () => input.form.requestSubmit()));
