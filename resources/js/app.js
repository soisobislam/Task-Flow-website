import './bootstrap';

const drawer = document.querySelector('[data-drawer-open]');
const drawerPanel = document.querySelector('#taskflow-drawer');
const drawerCloseButtons = document.querySelectorAll('[data-drawer-close]');

const setDrawerState = (isOpen) => {
    if (!drawerPanel || !drawer) {
        return;
    }

    drawerPanel.classList.toggle('is-open', isOpen);
    document.body.classList.toggle('drawer-is-open', isOpen);
    drawerPanel.setAttribute('aria-hidden', String(!isOpen));
    drawerPanel.inert = !isOpen;
    drawer.setAttribute('aria-expanded', String(isOpen));
};

drawer?.addEventListener('click', () => setDrawerState(true));
drawerCloseButtons.forEach((button) => button.addEventListener('click', () => setDrawerState(false)));
document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        setDrawerState(false);
    }
});
