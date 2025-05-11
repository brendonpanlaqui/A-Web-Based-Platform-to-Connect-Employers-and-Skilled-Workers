document.addEventListener("DOMContentLoaded", function() {
    // Fetch complaints data from the server
    fetch('../controllers/ComplaintsController.php')
        .then(response => response.json()) // Parse the JSON response
        .then(complaints => {
                        console.log('Complaints:', complaints);  // Log the response to inspect it

            const tableBody = document.getElementById('complaintsTableBody');

            // If no complaints found
            if (complaints.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center">No complaints found.</td></tr>';
                return;
            }

            complaints.forEach((complaint, index) => {
                const row = document.createElement('tr');
                
                // Fill in the row with complaint data
                row.innerHTML = `
                    <td>${complaint.id}</td>
                    <td>${complaint.first_name} ${complaint.last_name}</td>
                    <td>${complaint.reported_type.charAt(0).toUpperCase() + complaint.reported_type.slice(1)}</td>
                    <td>${complaint.reported_id}</td>
                    <td>${complaint.reason}</td>
                    <td>${complaint.status}</td>
                    <td>${complaint.created_at}</td>
                    <td class="text-nowrap">
                        <button class="btn btn-sm btn-danger" onclick="">Manage</button>
                    </td>
                `;
                // Append the row to the table body
                tableBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error fetching complaints:', error);
            const tableBody = document.getElementById('complaintsTableBody');
            tableBody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Failed to load complaints.</td></tr>`;
        });
});
