document.addEventListener("DOMContentLoaded", function () {
  const tbody = document.getElementById('applicationsTB');
  console.log('Table body:', tbody);

  const userHeading = document.getElementById("userHeading");

  const urlParams = new URLSearchParams(window.location.search);
  const userId = urlParams.get("user_id");

  if (!userId || isNaN(userId)) {
    tbody.innerHTML = `<tr><td colspan="7" class="text-danger">Invalid user ID.</td></tr>`;
    return;
  }

  fetch(`../controllers/GetUserApplicationsController.php?user_id=${userId}`)
    .then(response => response.json())
    .then(data => {
      console.log(data);
      if (data.error) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-danger">${data.error}</td></tr>`;
        return;
      }

      const { user, posts: applications } = data;

      if (userHeading) {
        userHeading.textContent = `${user.first_name} ${user.last_name}'s Applications`;
      }

      if (applications.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-muted text-center">No applications found for this user.</td></tr>`;
        return;
      }

      tbody.innerHTML = applications.map(app => `
        <tr>
          <td>${app.id}</td>
          <td>${user.first_name} ${user.last_name}</td>
          <td>${app.job_id}</td>
          <td>${app.cover_letter}</td>
          <td>${app.status}</td>
          <td>${new Date(app.date_applied).toLocaleDateString()}</td>
          <td class="text-nowrap">
              <button class="btn btn-sm btn-danger" onclick="deleteApplication(${app.id})">Delete</button>
            </td>
        </tr>
      `).join('');
    })
    .catch(error => {
      tbody.innerHTML = `<tr><td colspan="7" class="text-danger">Failed to load applications: ${error.message}</td></tr>`;
    });
});
