document.addEventListener("DOMContentLoaded", function () {
    const tableBody = document.getElementById("userapplicationsTableBody");
  
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      const query = input.value.trim();
  

        data.forEach(app => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${app.id}</td>
            <td>${app.first_name} ${app.last_name}</td>
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