document.addEventListener('DOMContentLoaded', () => {
    fetch('../controllers/JobApplicationController.php')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('applicationCardContainer');
            container.innerHTML = '';

            if (!data || data.length === 0 || data.error) {
                container.innerHTML = `
                    <div class="alert alert-warning w-100 text-center" role="alert">
                        No applications found.
                    </div>
                `;
                return;
            }

            data.forEach((app, index) => {
                const col = document.createElement('div');
                col.className = 'col-12 col-md-6 mb-4';

                const collapseId = `collapseApplication-${index}`;

                col.innerHTML = `
                    <div class="card shadow-sm" style="cursor: pointer;">
                        <div class="card-body">
                            <h4 class="card-title text-dark mb-2">${app.job_title}</h4>
                            <h6 class="mb-1">Worker: ${app.worker_name}</h6>
                            <h6 class="mb-1">Date Applied: ${app.date_applied}</h6>
                            <h6 class="mb-1">Status: ${app.application_status}</h6>
                            <div class="collapse mt-3" id="${collapseId}">
                                <hr>
                                <p><strong>Cover Letter:</strong><br>${app.cover_letter}</p>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-success btn-sm" onclick="event.stopPropagation(); updateApplicationStatus(${app.application_id}, 'accepted')">Accept</button>
                                    <button class="btn btn-danger btn-sm" onclick="event.stopPropagation(); updateApplicationStatus(${app.application_id}, 'rejected')">Reject</button>
                                </div>
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
            console.error('Error fetching applications:', error);
            document.getElementById('applicationCardContainer').innerHTML = `
                <div class="alert alert-danger w-100 text-center">Failed to load applications.</div>
            `;
        });
});

function updateApplicationStatus(applicationId, action) {
    if (!['accepted', 'rejected'].includes(action)) {
        alert('Invalid action');
        return;
    }

    fetch('../controllers/JobApplicationController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            application_id: applicationId,
            action: action
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Application status updated');
            location.reload();
        } else {
            alert('Failed to update application status');
        }
    })
    .catch(error => {
        console.error('Error updating application status:', error);
        alert('Failed to update application status');
    });
}
