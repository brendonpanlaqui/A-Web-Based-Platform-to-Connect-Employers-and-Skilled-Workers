// assets/js/worker.js

document.addEventListener('DOMContentLoaded', () => {
  //loadWorkerApplications();
});

/*function loadWorkerApplications() {
  fetch('../controllers/WorkerApplicationsController.php')
    .then(response => {
      if (!response.ok) {
        throw new Error('Failed to fetch applications');
      }
      return response.json();
    })
    .then(data => {
      updateApplicationsTable(data);
    })
    /*.catch(error => {
      console.error('Error loading applications:', error);
      const tableBody = document.getElementById('recentApplicationsTableBody');
      tableBody.innerHTML = `<tr><td colspan="4" class="text-center text-danger">Failed to load applications.</td></tr>`;
    });*/
//}

/*function updateApplicationsTable(applications) {
  const tableBody = document.getElementById('recentApplicationsTableBody');
  const applicationCounter = document.getElementById('applicationsCounter');
  tableBody.innerHTML = '';
  
  if (applications.length === 0) {
    tableBody.innerHTML = `<tr><td colspan="4" class="text-center">No applications found.</td></tr>`;
    applicationCounter.textContent = '0';
    return;
  }

  applicationCounter.textContent = applications.length;

  applications.forEach(app => {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${app.job_title}</td>
      <td>${app.category}</td>
      <td>${formatDate(app.date_applied)}</td>
      <td><span class="badge bg-${getStatusColor(app.status)}">${capitalize(app.status)}</span></td>
    `;
    tableBody.appendChild(row);
  });
}
  */

function formatDate(dateStr) {
  const date = new Date(dateStr);
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  return `${month}/${day}`;
}

function capitalize(str) {
  if (!str) return '';
  return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
}

function getStatusColor(status) {
  switch (status.toLowerCase()) {
    case 'pending': return 'secondary';
    case 'accepted': return 'success';
    case 'rejected': return 'danger';
    default: return 'secondary';
  }
}
