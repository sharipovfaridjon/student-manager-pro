(function () {
    const savedTheme = localStorage.getItem("theme");

    if (savedTheme === "dark") {
        document.documentElement.classList.add("dark-mode");
    }
})();

function toggleDarkMode() {
    document.documentElement.classList.toggle("dark-mode");

    const isDark = document.documentElement.classList.contains("dark-mode");
    localStorage.setItem("theme", isDark ? "dark" : "light");

    updateThemeIcon();
}

function updateThemeIcon() {
    const btns = document.querySelectorAll("button[onclick='toggleDarkMode()']");

    btns.forEach(btn => {
        const isDark = document.documentElement.classList.contains("dark-mode");
        btn.textContent = isDark ? "☀️" : "🌙";
    });
}

document.addEventListener("DOMContentLoaded", updateThemeIcon);

window.addEventListener("load", () => {
    const loader = document.querySelector(".loader");
    if (loader) {
        setTimeout(() => loader.classList.add("hidden"), 200);
    }
});