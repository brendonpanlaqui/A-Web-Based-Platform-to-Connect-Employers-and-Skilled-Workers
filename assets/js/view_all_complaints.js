document.addEventListener("DOMContentLoaded", function () {
  const tableBody = document.getElementById("complaintsTableBody");

  fetch("../controllers/GettAllComplaintsController.php")
    .then(res => res.json())
    .then(data => {
      tableBody.innerHTML = "";

      if (!Array.isArray(data) || data.length === 0) {
        tableBody.innerHTML = "<tr><td colspan='7'>No complaints found.</td></tr>";
        return;
      }

      data.forEach(complaint => {
        const row = document.createElement("tr");
        row.innerHTML = `
          <td>${complaint.id}</td>
          <td>${complaint.reporter_first} ${complaint.reporter_last}</td>
          <td>${complaint.reported_first} ${complaint.reported_last}</td>
          <td>${complaint.reason}</td>
          <td>${complaint.status}</td>
          <td>${complaint.created_at}</td>
          <td><button class="btn btn-sm btn-danger penalize-btn" data-user="${complaint.reported_id}">Penalize</button></td>
        `;
        tableBody.appendChild(row);
      });

      // Add event listeners for penalize buttons
      document.querySelectorAll(".penalize-btn").forEach(button => {
        button.addEventListener("click", function () {
          const userId = this.getAttribute("data-user");
          const row = this.closest("tr");

          if (!confirm("Are you sure you want to penalize this user for 7 days?")) return;

          fetch(`../controllers/PenalizeUserController.php`, {
            method: "POST",
            headers: {
              "Content-Type": "application/json"
            },
            body: JSON.stringify({ user_id: userId })
          })
            .then(res => res.json())
            .then(data => {
              if (data.success) {
                alert("User successfully penalized for 7 days.");
                row.remove(); // ✅ Remove row from table
              } else {
                alert("Failed to penalize user: " + (data.error || "Unknown error"));
              }
            })
            .catch(err => {
              console.error("Error:", err);
              alert("Request failed.");
            });
        });
      });
    })
    .catch(error => {
      console.error("Error fetching complaints:", error);
      tableBody.innerHTML = "<tr><td colspan='7'>Error loading complaints.</td></tr>";
    });
});
