document.addEventListener('DOMContentLoaded', () => {
  fetchRecentProjects();
});

function fetchRecentProjects() {
  // Fetch recent projects from the server (assuming you have a backend endpoint)
  fetch('../controllers/ProjectsController.php') // Adjust the path as needed
    .then(response => response.json())
    .then(allProjects => {
      console.log('Fetched projects:', allProjects);

      // ✅ 1. Count from ALL projects
      const counts = { open: 0, ongoing: 0, completed: 0 };
      allProjects.forEach(project => {
        const status = project.status.toLowerCase();
        if (status === 'open') counts.open++;
        if (status === 'ongoing') counts.ongoing++;
        if (status === 'completed') counts.completed++;
      });

      // ✅ Update the counters from allProjects
      document.getElementById('openCounter').textContent = counts.open;
      document.getElementById('ongoingCounter').textContent = counts.ongoing;
      document.getElementById('completedCounter').textContent = counts.completed;


      // Filter projects by current month and year
      const currentMonth = new Date().getMonth();
      const currentYear = new Date().getFullYear();

      const recentProjects = allProjects.filter(project => {
        const date = new Date(project.datePosted);
        return date.getMonth() === currentMonth && date.getFullYear() === currentYear;
      });

      // Dynamically generate the table rows
      const tableBody = document.getElementById('recentProjectsTableBody');
      tableBody.innerHTML = '';

      if (recentProjects.length === 0) {
        tableBody.innerHTML = `<tr><td colspan="4" class="text-center">No recent projects this month.</td></tr>`;
      } else {
        recentProjects.forEach(project => {
          const row = document.createElement('tr');
          console.log(project);
          row.innerHTML = `
            <td>${project.title}</td>
            <td>${project.type}</td>
            <td>${formatShortDate(project.datePosted)}</td>
            <td class="text-nowrap"><span class="badge bg-${getStatusColor(project.status)} status-badge">${project.status}</span></td>
          `;
          tableBody.appendChild(row);
        });
      }
    })
    .catch(error => {
      console.error('Error fetching projects:', error);
      // Show an error message if the fetch fails
      document.getElementById('recentProjectsTableBody').innerHTML = `<tr><td colspan="4" class="text-center text-danger">Failed to load projects. Please try again later.</td></tr>`;
    });
}

function formatShortDate(dateStr) {
  const date = new Date(dateStr);
  const month = String(date.getMonth() + 1).padStart(2, '0'); // e.g., "04"
  const day = String(date.getDate()).padStart(2, '0'); // e.g., 5
  return `${month}/${day}`; // or use "-" or space depending on your style
}

function getStatusColor(status) {
  switch (status.toLowerCase()) {
    case 'open': return 'primary';
    case 'ongoing': return 'warning';
    case 'completed': return 'success';
    default: return 'secondary';
  }
}
