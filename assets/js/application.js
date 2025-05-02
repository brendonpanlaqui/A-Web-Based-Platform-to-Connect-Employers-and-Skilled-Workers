document.addEventListener('DOMContentLoaded', () => {
    fetch('../controllers/JobApplicationController.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('applicationsTableBody');
            tbody.innerHTML = '';

            if (!data || data.length === 0 || data.error) {
                tbody.innerHTML = `<tr><td colspan="4" class="text-center">No applications found.</td></tr>`;
                return;
            }

            data.forEach(app => {
                tbody.innerHTML += `
                    <tr>
                        <td>${app.worker_name}</td>
                        <td>${app.job_title}</td>
                        <td>${app.date_applied}</td>
                        <td>${app.application_status}</td>
                        <td>
                            <button class="btn btn-success" onclick="updateApplicationStatus(${app.application_id}, 'accepted')">Accept</button>
                            <button class="btn btn-danger" onclick="updateApplicationStatus(${app.application_id}, 'rejected')">Reject</button>
                        </td>
                    </tr>
                `;
            });
        })
        .catch(error => {
            console.error('Error fetching applications:', error);
            document.getElementById('applicationsTableBody').innerHTML = `
                <tr><td colspan="4" class="text-danger text-center">Failed to load applications.</td></tr>
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
            location.reload(); // Reload the page to fetch updated data
        } else {
            alert('Failed to update application status');
        }
    })
    .catch(error => {
        console.error('Error updating application status:', error);
        alert('Failed to update application status');
    });
}
