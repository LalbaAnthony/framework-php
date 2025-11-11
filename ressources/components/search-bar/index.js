document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("search-input");
    const searchForm = document.getElementById("search-form");

    if (!searchInput || !searchForm) return;
    if (isMobile()) return;

    window.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            searchForm.submit();
        }
    })
});
