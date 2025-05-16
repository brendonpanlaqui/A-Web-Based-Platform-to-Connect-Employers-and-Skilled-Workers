document.addEventListener('DOMContentLoaded', () => {
    fetch(`../controllers/get_applications.php?status=${applicationStatus}`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('cardContainer');
            container.innerHTML = '';

            if (data.length === 0) {
                container.innerHTML = `
                    <div class="alert alert-warning w-100 text-center" role="alert">
                        No ${applicationStatus} applications found.
                    </div>
                `;
                return;
            }

            data.forEach((app, index) => {
                const col = document.createElement('div');
                col.className = 'col-12 col-md-6 mb-4';

                const collapseId = `collapseApp-${index}`;

                col.innerHTML = `
                    <div class="card shadow-sm" style="cursor: pointer;">
                        <div class="card-body">
                            <h4 class="card-title text-dark mb-3">${app.job_title}</h4>
                            <h6 class="mb-2">Date Applied: ${app.date_applied}</h6>
                            <h6 class="mb-2">Status: ${app.application_status.charAt(0).toUpperCase() + app.application_status.slice(1)}</h6>
                            <div class="collapse mt-3" id="${collapseId}">
                                <hr>
                                <p><strong>Employer:</strong> ${app.employer_name}</p>
                                <p><strong>Category:</strong> ${app.category}</p>
                                <p><strong>Salary:</strong> ${app.salary}</p>
                                <p><strong>Cover letter:</strong> ${app.cover_letter}</p>
                                <p><strong>Job status:</strong> ${app.job_status}</p>
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
            console.error('Error loading applications:', err);
            document.getElementById('cardContainer').innerHTML = `
                <div class="alert alert-danger w-100 text-center">Failed to load applications. Please try again later.</div>
            `;
        });
});
