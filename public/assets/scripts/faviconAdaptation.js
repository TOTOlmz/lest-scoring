const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
document.querySelector("link[rel='icon'][type='image/x-icon']").setAttribute("href", isDark ? "./assets/images/LT-white.ico" : "./assets/images/LT-black.ico");
console.log(isDark ? "Mode sombre détécté." : "Mode clair détécté.");