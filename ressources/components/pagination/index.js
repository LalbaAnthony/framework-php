document.addEventListener("click", e => {
    if (e.target.dataset.page) {
        const page = parseInt(e.target.dataset.page);
        const url = new URL(window.location.href);
        url.searchParams.set('page', page);
        goTo(url.toString());
    }
});
