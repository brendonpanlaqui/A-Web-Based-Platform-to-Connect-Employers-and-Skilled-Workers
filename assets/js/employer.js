document.addEventListener('DOMContentLoaded', () => {
    fetchRecentProjects();
  });
  
  function fetchRecentProjects() {
    const projects = [
      { category: 'Website Redesign', type: 'Online', datePosted: '2025-04-05', status: 'Completed' },
      { category: 'Logo Design', type: 'Online', datePosted: '2025-04-10', status: 'Ongoing' },
      { category: 'App Testing', type: 'Online', datePosted: '2025-04-15', status: 'Open' },
    ];
  
    const currentMonth = new Date().getMonth();
    const currentYear = new Date().getFullYear();
  
    const recentProjects = projects.filter(project => {
      const date = new Date(project.datePosted);
      return date.getMonth() === currentMonth && date.getFullYear() === currentYear;
    });
    
    const counts = { open: 0, ongoing: 0, completed: 0 };

    recentProjects.forEach(project => {
        const status = project.status.toLowerCase();
        if (status === 'open') counts.open++;
        if (status === 'ongoing') counts.ongoing++;
        if (status === 'completed') counts.completed++;
    });

    document.getElementById('openCounter').textContent = counts.open;
    document.getElementById('ongoingCounter').textContent = counts.ongoing;
    document.getElementById('completedCounter').textContent = counts.completed;




    const tableBody = document.getElementById('recentProjectsTableBody');
    tableBody.innerHTML = '';
  
    if (recentProjects.length === 0) {
      tableBody.innerHTML = `<tr><td colspan="4" class="text-center">No recent projects this month.</td></tr>`;
    } else {
      recentProjects.forEach(project => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td>${project.category}</td>
          <td>${project.type}</td>
          <td>${formatShortDate(project.datePosted)}</td>
          <td class="text-nowrap"><span class="badge bg-${getStatusColor(project.status)} status-badge">${project.status}</span></td>
        `;
        tableBody.appendChild(row);
      });
    }
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
  