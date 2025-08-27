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

document.addEventListener("DOMContentLoaded", function () {
    const loadMoreBtn = document.getElementById('load-more-btn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newRows = doc.querySelectorAll('#skills-table tbody tr');
                    const currentTable = document.querySelector('#skills-table tbody');
                    newRows.forEach(row => currentTable.appendChild(row));
                    const newLoadMoreBtn = doc.querySelector('#load-more-btn');
                    if (newLoadMoreBtn) {
                        loadMoreBtn.setAttribute('href', newLoadMoreBtn.getAttribute('href'));
                    } else {
                        loadMoreBtn.parentElement.remove();
                    }
                }).catch(error => console.error('Error:', error));
        });
    }
});
