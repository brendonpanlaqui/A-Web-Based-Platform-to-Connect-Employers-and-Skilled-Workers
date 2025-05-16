document.addEventListener('DOMContentLoaded', () => {
    fetch('../controllers/MyEmployersController.php')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('cardContainer');
            container.innerHTML = '';

            if (!data || data.length === 0 || data.error) {
                container.innerHTML = `
                    <div class="alert alert-warning w-100 text-center" role="alert">
                        No employers found.
                    </div>
                `;
                return;
            }

            data.forEach((item, index) => {
                const col = document.createElement('div');
                col.className = 'col-12 col-md-6 mb-4';

                const collapseId = `collapseEmployer-${index}`;

                col.innerHTML = `
                    <div class="card shadow-sm" style="cursor: pointer;">
                        <div class="card-body">
                            <h4 class="card-title text-dark mb-3">${item.job_title}</h4>
                            <h6 class="mb-2">Employer: ${item.employer_name}</h6>
                            <h6 class="mb-2">Date Hired: ${item.date_applied}</h6>
                            <h6 class="mb-2">Job Status: ${item.job_status}</h6>
                            <div class="collapse mt-3" id="${collapseId}">
                                <hr>
                                <button class="btn btn-danger" onclick="reportUser(${item.employer_id})">Report</button>
                            </div>
                        </div>
                    </div>
                `;

                const card = col.querySelector('.card');
                const collapseEl = col.querySelector(`#${collapseId}`);
                const bsCollapse = new bootstrap.Collapse(collapseEl, { toggle: false });

                card.addEventListener('click', () => {
                    bsCollapse.toggle();
                });

                container.appendChild(col);
            });
        })
        .catch(err => {
            console.error('Error loading employers:', err);
            document.getElementById('cardContainer').innerHTML = `
                <div class="alert alert-danger w-100 text-center">Failed to load employers. Please try again later.</div>
            `;
        });
});

function reportUser(userId) {
    if (confirm("Are you sure you want to report this employer?")) {
        window.location.href = `../views/report.php?type=user&id=${userId}`;
    }
}
