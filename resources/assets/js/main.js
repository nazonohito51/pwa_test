const applicationServerPublicKey = 'BJbwhdyPzgvLnBmxYat8cGJSck_wy0Ph_vRTPHemglPtSrmiLZ1R05yFbnfQJen-MbS97RejCn3xm6Y4v1ZvZ1Q';
const applicationServerPrivateKey = 'bOHQ5fmaaNfZiwsc2xhFFH8_iJVCsYbnfScaMPTuQQw';

let isSubscribed = false;
let swRegistration = null;

function checkSubscription() {
    swRegistration.pushManager.getSubscription()
        .then(function (subscription) {
            isSubscribed = !(subscription === null);

            if (isSubscribed) {
                console.log('User IS subscribed.');
            } else {
                console.log('User is NOT subscribed.');
            }
        });
}

function urlB64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

function updateSubscriptionOnServer(subscription) {
    if (subscription) {
        const key = subscription.getKey('p256dh');
        const token = subscription.getKey('auth');
        let contentEncoding;
        if ('supportedContentEncodings' in PushManager) {
            contentEncoding = PushManager.supportedContentEncodings.includes('aes128gcm') ? 'aes128gcm' : 'aesgcm';
        } else {
            contentEncoding = 'aesgcm';
        }

        axios.put("/api/user/test/notification", {
            endpoint: subscription.endpoint,
            key: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
            token: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null,
            contentEncoding: contentEncoding
        }).then(
            response => {
                console.log(response);

                if (response.error) {
                    console.log('updating subscription on server is failed.');
                } else {
                    console.log('updating subscription on server is succeeded.');
                }
            }
        ).catch(function (err) {
            // if update subscription on server is failed, unsubscribe subscription
            subscription.unsubscribe().then(function (successful) {
                console.log('unsubscribing is succeeded.', successful);
            });
        });
    } else {
        console.log('updating subscription on server is failed.');
    }
}

function subscribeUser() {
    const applicationServerKey = urlB64ToUint8Array(applicationServerPublicKey);
    swRegistration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: applicationServerKey
    })
        .then(function (subscription) {
            console.log('User is subscribed:', subscription);
            console.log('User is subscribed:', subscription.getKey('p256dh'));
            console.log('User is subscribed:', subscription.getKey('auth'));

            updateSubscriptionOnServer(subscription);

            isSubscribed = true;
        })
        .catch(function (err) {
            console.log('Failed to subscribe the user: ', err);
        });
}

if ('serviceWorker' in navigator && 'PushManager' in window) {
    console.log('Service Worker and Push is supported');

    navigator.serviceWorker.register('js/sw.js')
        .then(function (swReg) {
            console.log('Service Worker is registered', swReg);

            swRegistration = swReg;
            subscribeUser();
        })
        .catch(function (error) {
            console.error('Service Worker Error', error);
        });
} else {
    console.warn('Push messaging is not supported');
    pushButton.textContent = 'Push Not Supported';
}
