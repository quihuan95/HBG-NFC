self.addEventListener('push', event => {
    let data = {};
    if (event.data) {
        try { data = event.data.json(); } catch (_error) { data = {body: event.data.text()}; }
    }
    event.waitUntil(self.registration.showNotification(data.title || 'Thông báo mới', {
        body: data.body || 'Bạn có một thông báo mới.', icon: '/favicon.ico', badge: '/favicon.ico', data: {url: data.url || '/'},
    }));
});
self.addEventListener('notificationclick', event => {
    event.notification.close();
    const targetUrl = new URL(event.notification.data?.url || '/', self.location.origin).href;
    event.waitUntil((async () => {
        const windows = await clients.matchAll({type: 'window', includeUncontrolled: true});
        const existingWindow = windows.find(client => client.url === targetUrl);
        if (existingWindow) return existingWindow.focus();
        return clients.openWindow(targetUrl);
    })());
});
