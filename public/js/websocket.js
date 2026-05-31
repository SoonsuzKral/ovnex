/**
 * OVNEX — WebSocket Canlı Veri Akışı
 * Laravel Echo + Pusher.js ile gerçek zamanlı güncellemeler
 */

window.Laravel = window.Laravel || {};
window.Laravel.reverbKey = window.Laravel.reverbKey || document.querySelector('meta[name="reverb-key"]')?.getAttribute('content') || 'ovnex-key';
window.Laravel.reverbHost = window.Laravel.reverbHost || '127.0.0.1';
window.Laravel.reverbPort = window.Laravel.reverbPort || 8080;

document.addEventListener('DOMContentLoaded', function () {
    if (typeof Pusher === 'undefined') return;

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: window.Laravel.reverbKey,
        wsHost: window.Laravel.reverbHost,
        wsPort: parseInt(window.Laravel.reverbPort),
        wssPort: 443,
        forceTLS: false,
        encrypted: false,
        disableStats: true,
        enabledTransports: ['ws', 'wss'],
    });

    Echo.channel('aircraft.live')
        .listen('.aircraft.updated', () => {
            if (typeof loadAircraft === 'function') loadAircraft();
        });

    Echo.channel('earthquake.alerts')
        .listen('.earthquake.detected', () => {
            if (typeof loadEarthquakes === 'function') loadEarthquakes();
        });

    Echo.channel('news.live')
        .listen('.news.received', () => {
            if (typeof loadNewsFeed === 'function') loadNewsFeed();
        });
});
