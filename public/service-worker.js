self.addEventListener('push', function (event) {

console.log('[Service Worker] Push received');

let data = {};

try {
    data = event.data
        ? event.data.json()
        : {};
} catch (error) {
    console.error(
        '[Service Worker] Failed to parse push data:',
        error
    );
}

const title =
    data.title ||
    'Stock Signal';

const options = {

    body:
        data.body ||
        'Ada notification baru.',

    icon:
        data.icon ||
        '/icons/icon-192.png',

    badge:
        data.badge ||
        '/icons/badge-72.png',

    data:
        data.data ||
        {},

    tag:
        data.tag ||
        'stock-signal',

    renotify: true,

    requireInteraction: false,

    vibrate: [
        200,
        100,
        200
    ]
};

event.waitUntil(
    self.registration.showNotification(
        title,
        options
    )
);


});

/*
|--------------------------------------------------------------------------

Ketika user menekan notification
*/

self.addEventListener(
'notificationclick',
function (event) {

    console.log(
        '[Service Worker] Notification clicked'
    );

    event.notification.close();

    const notificationData =
        event.notification.data || {};

    const url =
        notificationData.url || '/';

    event.waitUntil(

        clients.matchAll({
            type: 'window',
            includeUncontrolled: true
        })
        .then(function (clientList) {

            /*
            |--------------------------------------------------------------------------
            | Jika website sudah terbuka,
            | fokus ke tab/window tersebut.
            |--------------------------------------------------------------------------
            */

            for (
                const client of clientList
            ) {

                if (
                    client.url === url &&
                    'focus' in client
                ) {

                    return client.focus();

                }
            }


            /*
            |--------------------------------------------------------------------------
            | Jika website belum terbuka,
            | buka halaman notification.
            |--------------------------------------------------------------------------
            */

            if (
                clients.openWindow
            ) {

                return clients.openWindow(
                    url
                );

            }

        })
    );
}


);

/*
|--------------------------------------------------------------------------

Service Worker activated
*/

self.addEventListener(
'activate',
function (event) {

    console.log(
        '[Service Worker] Activated'
    );

    event.waitUntil(
        self.clients.claim()
    );

}


);

/*
|--------------------------------------------------------------------------

Service Worker installed
*/

self.addEventListener(
'install',
function () {

    console.log(
        '[Service Worker] Installed'
    );

}


);