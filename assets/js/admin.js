document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("searchForm");
    const input = document.getElementById("searchInput");
    const tableBody = document.getElementById("userTableBody");

    function fetchUsers(query = "") {
        fetch('../controllers/SearchController.php', {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "query=" + encodeURIComponent(query)
        })
        .then(response => response.json())
        .then(data => {
            tableBody.innerHTML = "";

            if (!Array.isArray(data) || data.length === 0) {
                tableBody.innerHTML = "<tr><td colspan='5'>No users found.</td></tr>";
                return;
            }

            data.forEach(user => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${user.id}</td>
                    <td>${user.first_name}</td>
                    <td>${user.last_name}</td>
                    <td>${user.email}</td>  
                    <td class="text-nowrap">
                        <a href="view_user_posts.html?user_id=${user.id}" class="me-2">Posts</a>
                        <a href="view_user_applications.html?user_id=${user.id}" class="me-2">Applications</a>
                        <a href="view_user_complaints.html?user_id=${user.id}" class="me-2">Complaints</a>
                        <button class="btn btn-sm btn-danger" id="deleteUserBtn_${user.id}">Delete</button>
                    </td>
                `;
                tableBody.appendChild(row);

                document.getElementById(`deleteUserBtn_${user.id}`).addEventListener('click', function() {
                    deleteUser(user.id);
                });
            });
        });g
    }

    form.addEventListener("submit", function (e) {
        e.preventDefault(); 
        const searchTerm = input.value.trim();
        fetchUsers(searchTerm);
    });

    fetchUsers();

    function deleteUser(userId) {
        if (!confirm("Are you sure you want to delete this user?")) return;
    
        fetch(`../controllers/DeleteUserController.php?id=${userId}`, {
            method: "POST"
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert("User deleted successfully.");
                document.getElementById("searchForm").dispatchEvent(new Event("submit"));
            } else {
                alert("Failed to delete user.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("An error occurred.");
        });
    }
});
