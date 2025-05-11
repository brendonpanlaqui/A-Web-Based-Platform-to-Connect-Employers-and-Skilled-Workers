document.addEventListener('DOMContentLoaded', () => {
    fetch('../controllers/JobApplicationController.php?filter=accepted')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('applicationsTableBody');
            tbody.innerHTML = '';

            if (!data || data.length === 0 || data.error) {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center">No accepted employees found.</td></tr>`;
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
                            <button class="btn btn-danger" onclick="reportUser(${app.worker_id})">Report</button>

                        </td>
                    </tr>
                `;
            });
        })
        .catch(error => {
            console.error('Error fetching employees:', error);
            document.getElementById('applicationsTableBody').innerHTML = `
                <tr><td colspan="5" class="text-danger text-center">Failed to load employees.</td></tr>
            `;
        });
});
function reportUser(userId) {
    if (confirm("Are you sure you want to report this user?")) {
        window.location.href = `../views/report.php?type=user&id=${userId}`;
    }
}

