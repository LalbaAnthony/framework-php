document.addEventListener("DOMContentLoaded", function () {
    const goBackButton = document.getElementById('goback');

    if (goBackButton) {
        goBackButton.addEventListener('click', function () {
            goBackSafe();
        });
    }
});