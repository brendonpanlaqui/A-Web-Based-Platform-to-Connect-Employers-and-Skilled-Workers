document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("searchForm");
    const input = document.getElementById("searchInput");
    const tableBody = document.getElementById("applicationsTableBody");
  
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      const query = input.value.trim();
  
      fetch("../controllers/SearchApplicationsController.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "query=" + encodeURIComponent(query)
      })
      .then(res => res.json())
      .then(data => {
        tableBody.innerHTML = "";
  
        if (!Array.isArray(data) || data.length === 0) {
          tableBody.innerHTML = "<tr><td colspan='9'>No applications found.</td></tr>";
          return;
        }
  
        data.forEach(app => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${app.id}</td>
            <td>${app.worker_id}</td>
            <td>${app.job_id}</td>
            <td>${app.cover_letter}</td>
            <td>${app.status}</td>
            <td>${app.date_applied}</td>
            <td class="text-nowrap">
              <button class="btn btn-sm btn-danger" onclick="deleteApplication(${app.id})">Delete</button>
            </td>
          `;
          tableBody.appendChild(row);
        });
      });
    });
  
    form.dispatchEvent(new Event("submit")); // Auto-load on page open
  });
  
  function deleteApplication(appId) {
    if (!confirm("Are you sure you want to delete this application?")) return;
  
    fetch(`../controllers/DeleteApplicationController.php?id=${appId}`, {
      method: "POST"
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert("Application deleted successfully.");
        document.getElementById("searchForm").dispatchEvent(new Event("submit"));
      } else {
        alert("Failed to delete application.");
      }
    });
  }
  