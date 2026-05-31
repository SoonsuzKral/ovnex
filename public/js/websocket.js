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

function loadNewsFeed() {
    fetch('/api/news/latest')
        .then(r => r.json())
        .then(items => {
            const container = document.getElementById('news-feed');
            if (!container) return;
            container.innerHTML = items.map(item => `
                <div class="news-item p-2 border-b border-[#21262d] text-xs">
                    <span class="text-[${item.category === 'deprem' ? '#ff8800' : item.category === 'guvenlik' ? '#ff3333' : item.category === 'hava' ? '#00f0ff' : '#8b949e'}] uppercase text-[10px]">${item.category || 'genel'}</span>
                    <a href="${item.url || '#'}" target="_blank" class="block text-white hover:text-[#00f0ff] mt-1">${item.title}</a>
                </div>
            `).join('');
        })
        .catch(() => {});
}
