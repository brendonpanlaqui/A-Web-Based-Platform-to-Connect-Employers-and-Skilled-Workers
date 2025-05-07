document.addEventListener("DOMContentLoaded", function () {
    const postsContainer = document.getElementById("postsContainer");
    const userHeading = document.getElementById("userHeading");

    const urlParams = new URLSearchParams(window.location.search);
    const userId = urlParams.get("user_id");

    if (!userId || isNaN(userId)) {
        postsContainer.innerHTML = `<div class="alert alert-danger">Invalid user ID.</div>`;
        return;
    }

    fetch(`../controllers/GetUserPostsController.php?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                postsContainer.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                return;
            }

            const { user, posts } = data;
            userHeading.textContent = `${user.first_name} ${user.last_name}'s Posts`;

            if (posts.length === 0) {
                postsContainer.innerHTML = `<div class="alert alert-warning">No posts found for this user.</div>`;
                return;
            }

            const table = document.createElement("table");
            table.className = "table table-bordered border-dark";
            table.innerHTML = `
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Platform</th>
                        <th>Location</th>
                        <th>Estimate</th>
                        <th>Expertise</th>
                        <th>Salary</th>
                        <th>Description</th>
                        <th>Created</th>
                        <th>Updated</th>
                    </tr>
                </thead>
                <tbody>
                    ${posts.map(post => `
                        <tr>
                            <td>${post.id}</td>
                            <td>${post.title}</td>
                            <td>${post.category}</td>
                            <td>${post.type}</td>
                            <td>${post.platform}</td>
                            <td>${post.location}</td>
                            <td>${post.time_estimate}</td>
                            <td>${post.expertise_level}</td>
                            <td>${post.salary}</td>
                            <td>${post.description}</td>
                            <td>${post.created_at}</td>
                            <td>${post.updated_at}</td>
                        </tr>
                    `).join('')}
                </tbody>
            `;

            postsContainer.appendChild(table);
        })
       
});
