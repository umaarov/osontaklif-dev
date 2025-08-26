document.addEventListener('DOMContentLoaded', function () {
    const toastElement = document.getElementById('infoToast');
    if (toastElement) {
        const bsToast = new bootstrap.Toast(toastElement, {delay: 3000});
        const toastBody = toastElement.querySelector('.toast-body');

        document.querySelectorAll('.toast-trigger').forEach(card => {
            card.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                const professionName = this.dataset.professionName;
                if (toastBody) {
                    toastBody.textContent = '"' + professionName + '" has no questions yet.';
                }
                bsToast.show();
            });
        });
    }
});