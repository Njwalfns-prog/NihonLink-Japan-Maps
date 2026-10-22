/** Intro perjalanan Nihon menuju lokasi NihonLink di Indonesia. */
import L from 'leaflet';
import { getMapIntroContext } from './map.js';

const INTRO_KEY = 'nihonlink_map_intro_shown';
const TOKYO = L.latLng(35.68, 139.69);
const FINAL_VIEW = { padding: [36, 36], maxZoom: 7 };

function bezierPoint(start, control, end, t) {
    const inverse = 1 - t;
    return L.latLng(
        inverse * inverse * start.lat + 2 * inverse * t * control.lat + t * t * end.lat,
        inverse * inverse * start.lng + 2 * inverse * t * control.lng + t * t * end.lng,
    );
}

function createLabel(text, variant) {
    return L.marker(TOKYO, {
        interactive: false,
        keyboard: false,
        icon: L.divIcon({
            className: 'map-intro-label-icon',
            html: `<span class="map-intro-label map-intro-label--${variant}">${text}</span>`,
            iconSize: [1, 1],
            iconAnchor: [-8, 20],
        }),
    });
}

function animateLabel(marker) {
    const label = marker.getElement()?.querySelector('.map-intro-label');
    if (!label) return;
    label.animate(
        [
            { opacity: 0, transform: 'translateY(5px) scale(.86)' },
            { opacity: 1, transform: 'translateY(0) scale(1)' },
        ],
        { duration: 360, easing: 'cubic-bezier(.2,.8,.2,1)', fill: 'forwards' },
    );
}

function revealMarker(marker, animations) {
    const element = marker.getElement();
    if (!element) return;
    const pin = element.querySelector('.jmap-marker-pin');
    if (pin) {
        const animation = pin.animate(
            [
                { transform: 'translateY(-26px) scale(.78) rotate(-45deg)', opacity: 0 },
                { transform: 'translateY(4px) scale(1.06) rotate(-45deg)', opacity: 1, offset: .78 },
                { transform: 'translateY(0) scale(1) rotate(-45deg)', opacity: 1 },
            ],
            { duration: 430, easing: 'cubic-bezier(.2,1.45,.4,1)', fill: 'both' },
        );
        animations.push(animation);
    }
    element.classList.remove('map-intro-marker-hidden');
}

/** Jalankan sekali setelah splash; mengembalikan false jika intro dilewati. */
export async function runMapIntro() {
    const { map, markers, bounds } = getMapIntroContext();
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!map || !markers.length || !bounds?.isValid() || reducedMotion || sessionStorage.getItem(INTRO_KEY)) {
        return false;
    }

    sessionStorage.setItem(INTRO_KEY, '1');

    const destination = bounds.getCenter();
    const control = L.latLng(
        (TOKYO.lat + destination.lat) / 2 + 16,
        (TOKYO.lng + destination.lng) / 2 + 12,
    );
    const curve = Array.from({ length: 81 }, (_, index) =>
        bezierPoint(TOKYO, control, destination, index / 80),
    );
    const animations = [];
    const timers = new Set();
    let frameId = 0;
    let done = false;
    let moveEndHandler;
    let resolveInterrupted;
    const interrupted = new Promise(resolve => { resolveInterrupted = resolve; });
    const mapElement = map.getContainer();
    const navbarLogo = document.querySelector('.navbar__logo-img');

    const pause = duration => new Promise(resolve => {
        const timer = setTimeout(() => {
            timers.delete(timer);
            resolve();
        }, duration);
        timers.add(timer);
    });

    const tokyoLabel = createLabel('日本 Nihon', 'nihon').setLatLng(TOKYO).addTo(map);
    const indonesiaLabel = createLabel('Indonesia', 'indonesia').setLatLng(destination).addTo(map);
    const glowLine = L.polyline(curve, {
        color: '#C1272D', weight: 11, opacity: .2, lineCap: 'round',
        interactive: false, className: 'map-intro-route-glow',
    }).addTo(map);
    const routeLine = L.polyline(curve, {
        color: '#C1272D', weight: 3, opacity: .95, lineCap: 'round',
        interactive: false, className: 'map-intro-route-line',
    }).addTo(map);
    const light = L.circleMarker(TOKYO, {
        radius: 7, color: '#fff', weight: 2, fillColor: '#C1272D', fillOpacity: 1,
        interactive: false, className: 'map-intro-beacon',
    }).addTo(map);

    const introLayers = [tokyoLabel, indonesiaLabel, glowLine, routeLine, light];

    const removeListeners = () => {
        mapElement.removeEventListener('pointerdown', interrupt, true);
        mapElement.removeEventListener('wheel', interrupt, true);
        mapElement.removeEventListener('touchstart', interrupt, true);
        mapElement.removeEventListener('click', interrupt, true);
        document.removeEventListener('keydown', onKeydown);
        if (moveEndHandler) map.off('moveend', moveEndHandler);
    };

    const removeIntroLayers = () => {
        introLayers.forEach(layer => map.removeLayer(layer));
    };

    const showMarkersImmediately = () => {
        markers.forEach(marker => {
            const element = marker.getElement();
            if (!element) return;
            element.classList.remove('map-intro-marker-hidden');
            element.querySelector('.jmap-marker-pin')?.getAnimations().forEach(animation => animation.cancel());
        });
    };

    const restoreLogo = () => {
        if (!navbarLogo) return;
        navbarLogo.classList.remove('map-intro-logo-hidden');
        navbarLogo.getAnimations().forEach(animation => animation.cancel());
    };

    const cleanup = () => {
        removeListeners();
        if (frameId) cancelAnimationFrame(frameId);
        timers.forEach(clearTimeout);
        timers.clear();
        animations.forEach(animation => animation.cancel());
        removeIntroLayers();
    };

    const interrupt = () => {
        if (done) return;
        done = true;
        map.stop();
        map.fitBounds(bounds, { ...FINAL_VIEW, animate: false });
        showMarkersImmediately();
        restoreLogo();
        cleanup();
        resolveInterrupted();
    };

    function onKeydown(event) {
        if (event.key === 'Escape') interrupt();
    }

    mapElement.addEventListener('pointerdown', interrupt, { capture: true, passive: true });
    mapElement.addEventListener('wheel', interrupt, { capture: true, passive: true });
    mapElement.addEventListener('touchstart', interrupt, { capture: true, passive: true });
    mapElement.addEventListener('click', interrupt, true);
    document.addEventListener('keydown', onKeydown);

    // Mulai dari Jepang dan sembunyikan marker/navbar sampai gilirannya.
    map.stop();
    map.setView(TOKYO, 5, { animate: false });
    markers.forEach(marker => marker.getElement()?.classList.add('map-intro-marker-hidden'));
    navbarLogo?.classList.add('map-intro-logo-hidden');
    requestAnimationFrame(() => animateLabel(tokyoLabel));
    const indonesiaTimer = setTimeout(() => animateLabel(indonesiaLabel), 1320);
    timers.add(indonesiaTimer);

    // Garis memakai dash SVG, sementara titik cahaya mengikuti kurva.
    requestAnimationFrame(() => {
        const path = routeLine.getElement();
        if (path) {
            path.setAttribute('pathLength', '1');
            path.style.strokeDasharray = '1';
            path.style.strokeDashoffset = '1';
            animations.push(path.animate(
                [{ strokeDashoffset: '1' }, { strokeDashoffset: '0' }],
                { duration: 1600, easing: 'ease-in-out', fill: 'forwards' },
            ));
        }
    });

    const routeStart = performance.now();
    const animateLight = now => {
        if (done) return;
        const progress = Math.min((now - routeStart) / 1600, 1);
        light.setLatLng(bezierPoint(TOKYO, control, destination, progress));
        if (progress < 1) frameId = requestAnimationFrame(animateLight);
    };
    frameId = requestAnimationFrame(animateLight);

    const movementFinished = new Promise(resolve => {
        moveEndHandler = () => resolve();
        map.once('moveend', moveEndHandler);
    });
    map.flyToBounds(bounds, { ...FINAL_VIEW, duration: 2.2 });

    const flow = async () => {
        await Promise.race([movementFinished, pause(2450), interrupted]);
        if (done) return;

        const stagger = markers.length > 1 ? Math.min(55, 700 / (markers.length - 1)) : 0;
        for (let index = 0; index < markers.length; index += 1) {
            if (done) return;
            revealMarker(markers[index], animations);
            if (index < markers.length - 1) {
                await Promise.race([pause(stagger), interrupted]);
            }
        }

        await Promise.race([pause(440), interrupted]);
        if (done) return;

        // Hilangkan rute sebelum logo navbar masuk.
        const fadeTargets = [
            tokyoLabel.getElement()?.querySelector('.map-intro-label'),
            indonesiaLabel.getElement()?.querySelector('.map-intro-label'),
            routeLine.getElement(), glowLine.getElement(), light.getElement(),
        ].filter(Boolean);
        fadeTargets.forEach(element => animations.push(element.animate(
            [{ opacity: 1 }, { opacity: 0 }],
            { duration: 220, easing: 'ease-out', fill: 'forwards' },
        )));
        await Promise.race([pause(230), interrupted]);
        if (done) return;

        // Samakan hasil akhir dengan fitBounds awal dan bersihkan overlay intro.
        map.fitBounds(bounds, { ...FINAL_VIEW, animate: false });
        removeIntroLayers();

        let logoAnimation;
        if (navbarLogo) {
            navbarLogo.classList.remove('map-intro-logo-hidden');
            logoAnimation = navbarLogo.animate(
                [
                    { opacity: 0, transform: 'translateY(-18px) scale(.86)' },
                    { opacity: 1, transform: 'translateY(3px) scale(1.04)', offset: .78 },
                    { opacity: 1, transform: 'translateY(0) scale(1)' },
                ],
                { duration: 440, easing: 'cubic-bezier(.2,1.45,.4,1)', fill: 'both' },
            );
        }
        if (logoAnimation) {
            await Promise.race([logoAnimation.finished.catch(() => {}), interrupted]);
            if (!done) logoAnimation.cancel();
        }
        if (done) return;

        done = true;
        cleanup();
        resolveInterrupted();
    };

    await Promise.race([flow(), interrupted]);
    return true;
}
