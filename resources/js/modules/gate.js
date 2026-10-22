import { getCategoryConfig } from './map.js';

const GATE_SESSION_KEY = 'nihonlink_category_gate_shown_v3';

/** Tampilkan gerbang dan tunggu sampai pengunjung memilih tujuan. */
export function initGate(skipGate = false) {
    return new Promise(resolve => {
        const gate = document.getElementById('category-gate');
        if (!gate || skipGate || sessionStorage.getItem(GATE_SESSION_KEY)) {
            if (gate && skipGate) sessionStorage.setItem(GATE_SESSION_KEY, '1');
            gate?.remove();
            resolve(null);
            return;
        }

        const logo = document.getElementById('category-gate-logo');
        const hint = document.getElementById('category-gate-hint');
        const list = document.getElementById('category-gate-categories');
        const enter = document.getElementById('category-gate-enter');
        const stage = gate.querySelector('.category-gate__stage');
        const chips = [...document.querySelectorAll('.chip[data-filter]')]
            .filter(chip => chip.dataset.filter !== 'semua');

        gate.hidden = false;
        const ringCenterY = stage.clientHeight * 0.45;
        const preferredRadius = Math.max(255, chips.length * 38);
        const radius = Math.max(130, Math.min(
            290,
            preferredRadius,
            ringCenterY - 70,
            stage.clientHeight - ringCenterY - 145,
            stage.clientWidth / 2 - 70,
        ));

        chips.forEach((chip, index) => {
            const slug = chip.dataset.filter;
            const label = chip.dataset.label || chip.textContent.trim();
            const markerStyle = getCategoryConfig(slug);
            const angle = (-Math.PI / 2) + ((Math.PI * 2 * index) / Math.max(chips.length, 1));
            const button = document.createElement('button');
            button.className = 'category-gate__item';
            button.type = 'button';
            button.style.setProperty('--category-color', markerStyle.color);
            button.style.setProperty('--item-delay', `${index * 60}ms`);
            button.style.setProperty('--orbit-x', `${Math.cos(angle) * radius}px`);
            button.style.setProperty('--orbit-y', `${Math.sin(angle) * radius}px`);
            button.setAttribute('aria-label', `Tampilkan kategori ${label}`);
            const icon = document.createElement('span');
            icon.className = 'category-gate__icon';
            icon.setAttribute('aria-hidden', 'true');
            icon.textContent = markerStyle.icon;
            const name = document.createElement('span');
            name.className = 'category-gate__label';
            name.textContent = label;
            button.append(icon, name);
            button.addEventListener('click', () => finish(slug));
            list.appendChild(button);
        });

        let expanded = false;
        let finished = false;
        let opener = null;

        const collapse = () => {
            expanded = false;
            gate.classList.remove('category-gate--expanded');
            logo.setAttribute('aria-expanded', 'false');
            logo.setAttribute('aria-label', 'Tampilkan kategori');
            list.setAttribute('aria-hidden', 'true');
            hint.textContent = 'Ketuk logo untuk melihat kategori';
            logo.focus();
        };

        const finish = (category = null) => {
            if (finished) return;
            finished = true;
            document.removeEventListener('keydown', handleKeydown);
            sessionStorage.setItem(GATE_SESSION_KEY, '1');
            gate.classList.add('category-gate--closing');
            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            window.setTimeout(() => {
                gate.classList.add('category-gate--fading');
                window.setTimeout(() => {
                    gate.remove();
                    if (opener?.isConnected) opener.focus();
                    resolve(category);
                }, reduceMotion ? 0 : 240);
            }, reduceMotion ? 0 : 560);
        };

        const toggle = () => {
            expanded = !expanded;
            gate.classList.toggle('category-gate--expanded', expanded);
            logo.setAttribute('aria-expanded', String(expanded));
            logo.setAttribute('aria-label', expanded ? 'Sembunyikan kategori' : 'Tampilkan kategori');
            list.setAttribute('aria-hidden', String(!expanded));
            hint.textContent = expanded ? 'Pilih kategori atau masuk ke peta' : 'Ketuk logo untuk melihat kategori';
            if (expanded) list.querySelector('button')?.focus();
        };

        opener = document.activeElement;
        sessionStorage.setItem(GATE_SESSION_KEY, '1');
        requestAnimationFrame(() => gate.classList.add('category-gate--visible'));
        logo.focus();
        logo.addEventListener('click', toggle);
        enter.addEventListener('click', () => finish(null));
        gate.addEventListener('click', event => {
            if (expanded && !event.target.closest('.category-gate__item, .category-gate__logo, .category-gate__enter')) collapse();
        });
        const handleKeydown = event => {
            if (event.key !== 'Escape' || finished) return;
            if (expanded) collapse();
            else finish(null);
        };
        document.addEventListener('keydown', handleKeydown);
    });
}
