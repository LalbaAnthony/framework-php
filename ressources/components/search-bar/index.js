document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("search-input");
    const searchForm = document.getElementById("search-form");

    if (!searchInput || !searchForm) return;
    if (isMobile()) return;

    function submitSearch() {
        searchForm.submit();
    }

    function clearSearch() {
        searchInput.value = '';
    }

    function focusSearchInput() {
        searchInput.focus();
        const length = searchInput.value.length;
        searchInput.setSelectionRange(length, length);
    }

    function handleKeyDown(e) {
        if (e.key === 'Enter') {
            submitSearch();
        }
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') { // Ctrl + F
            e.preventDefault();
            focusSearchInput();
        }
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') { // Ctrl + K
            e.preventDefault();
            clearSearch();
            focusSearchInput();
        }
    }


    window.addEventListener('keydown', handleKeyDown);
});
