function googleTranslateElementInit() {
    new google.translate.TranslateElement({
    pageLanguage: 'it',
    layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
    includedLanguages: 'en,es,fr,de,it',
    // excludedLanguages: 'hi,ja',
}, 'google_translate_element_modern');
}

(function monitorGootransCookie() {
function getCookie(name) {
const match = document.cookie.match(new RegExp(`(^| )${name}=([^;]+)`));
return match ? match[2] : null;
}

let lastCookieValue = getCookie("googtrans");

setInterval(() => {
let currentCookieValue = getCookie("googtrans");
if (currentCookieValue !== lastCookieValue) {
console.log("`gootrans` cookie changed. Reloading...");
location.reload();
}
lastCookieValue = currentCookieValue;
}, 1000); // Check every second
})();
