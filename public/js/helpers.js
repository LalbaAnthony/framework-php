function isMobile() {
    return (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent));
}

function goTo(url) {
    if (!url) return;
    window.location.href = url;
}

function goBack() {
    window.history.back();
}

function goBackSafe() {
    const referrer = document.referrer;
    const currentOrigin = window.location.origin;

    if (referrer.startsWith(currentOrigin)) {
        window.history.back();
    } else {
        window.location.href = currentOrigin
    }
}