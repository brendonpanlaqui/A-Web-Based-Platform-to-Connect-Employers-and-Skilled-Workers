document.addEventListener('DOMContentLoaded', () => {
    fetch('../controllers/JobApplicationController.php?filter=accepted')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('employeeCardContainer');
            container.innerHTML = '';

            if (!data || data.length === 0 || data.error) {
                container.innerHTML = `
                    <div class="alert alert-warning w-100 text-center">
                        No accepted employees found.
                    </div>
                `;
                return;
            }

            data.forEach((app, index) => {
                const col = document.createElement('div');
                col.className = 'col-12 col-md-6 mb-4';

                const collapseId = `collapseEmployee-${index}`;

                col.innerHTML = `
                    <div class="card shadow-sm" style="cursor: pointer;">
                        <div class="card-body">
                            <h4 class="card-title text-dark mb-2">${app.job_title}</h4>
                            <div class="d-flex align-items-center mb-1">
                                <h6 class="mb-0">Employee: ${app.worker_name}</h6>
                                <a href="profile.php?user_id=${app.worker_id}" class="ms-2 text-dark" data-bs-toggle="tooltip" title="View Profile">
                                    <i class="bi bi-person-fill fs-5"></i>
                                </a>
                            </div>
                            <h6 class="mb-1">Date Applied: ${app.date_applied}</h6>
                            <h6 class="mb-1">Status: ${app.application_status}</h6>
                            <div class="collapse mt-3" id="${collapseId}">
                                <hr>
                                <button class="btn btn-danger btn-sm" onclick="event.stopPropagation(); reportUser(${app.worker_id})">Report</button>
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
        .catch(error => {
            console.error('Error fetching employees:', error);
            document.getElementById('employeeCardContainer').innerHTML = `
                <div class="alert alert-danger w-100 text-center">Failed to load employees.</div>
            `;
        });
});

function reportUser(userId) {
    if (confirm("Are you sure you want to report this user?")) {
        window.location.href = `../views/report.php?type=user&id=${userId}`;
    }
}
