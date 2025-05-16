document.addEventListener('DOMContentLoaded', () => {
  fetchEmployeeApplications();
});

function fetchEmployeeApplications() {
  fetch('../controllers/ApplicationsController.php')
    .then(response => response.json())
    .then(applications => {
      console.log('Fetched applications:', applications);

      // Count status
      const counts = { pending: 0, accepted: 0, rejected: 0 };
      applications.forEach(app => {
        const status = app.status.toLowerCase();
        if (status === 'pending') counts.pending++;
        if (status === 'accepted') counts.accepted++;
        if (status === 'rejected') counts.rejected++;
      });

      document.getElementById('appliedCounter').textContent = counts.pending;
      document.getElementById('ongoingCounter').textContent = counts.rejected;
      document.getElementById('completedCounter').textContent = counts.accepted;

      // Filter recent (this month)
      const currentMonth = new Date().getMonth();
      const currentYear = new Date().getFullYear();

      const recentApps = applications.filter(app => {
        const date = new Date(app.dateApplied);
        return date.getMonth() === currentMonth && date.getFullYear() === currentYear;
      });

      const tableBody = document.getElementById('recentApplicationsTableBody');
      tableBody.innerHTML = '';

      if (recentApps.length === 0) {
        tableBody.innerHTML = `<tr><td colspan="4" class="text-center">No recent applications this month.</td></tr>`;
      } else {
        recentApps.forEach(app => {
          const row = document.createElement('tr');
          row.innerHTML = `
            <td>${app.category}</td>
            <td>${app.type}</td>
            <td>${formatShortDate(app.dateApplied)}</td>
            <td class="text-nowrap"><span class="badge bg-${getStatusColor(app.status)}">${app.status}</span></td>
          `;
          tableBody.appendChild(row);
        });
      }
    })
    .catch(error => {
      console.error('Error fetching applications:', error);
      document.getElementById('recentApplicationsTableBody').innerHTML = `<tr><td colspan="4" class="text-center text-danger">Failed to load applications.</td></tr>`;
    });
}

function formatShortDate(dateStr) {
  const date = new Date(dateStr);
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  return `${month}/${day}`;
}

function getStatusColor(status) {
  switch (status.toLowerCase()) {
    case 'pending': return 'primary';
    case 'accepted': return 'success';
    case 'rejected': return 'warning';
    default: return 'secondary';
  }
}
