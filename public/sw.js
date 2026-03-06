const CACHE_NAME = 'sigap-v1'; 
self.addEventListener('install', event => { 
event.waitUntil(caches.open(CACHE_NAME).then(cache => cache.addAll(['/', 
'/manifest.json']))); 
}); 
self.addEventListener('fetch', event => { 
event.respondWith(caches.match(event.request).then(response => response 
|| fetch(event.request))); 
});
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cache => {
          if (cache !== CACHE_NAME) {
            return caches.delete(cache);
          }
        })
      );
    })
  );
});