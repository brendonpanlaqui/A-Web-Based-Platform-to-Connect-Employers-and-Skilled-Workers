document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("searchForm");
    const input = document.getElementById("searchInput");
    const tableBody = document.getElementById("postsTableBody");
  
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      const query = input.value.trim();
  
      fetch("../controllers/SearchPostsController.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "query=" + encodeURIComponent(query)
      })
      .then(res => res.json())
      .then(data => {
        tableBody.innerHTML = "";
  
        if (data.length === 0) {
          tableBody.innerHTML = "<tr><td colspan='6'>No posts found.</td></tr>";
          return;
        }
  
        data.forEach(post => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${post.id}</td>
            <td>${post.employer_id}</td>
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
            <td class="text-nowrap">
              <a href="edit_post.php?id=${post.id}" class="me-2">Edit</a>
              <button class="btn btn-sm btn-danger" onclick="deletePost(${post.id})">Delete</button>
            </td>
          `;
          tableBody.appendChild(row);
        });
      });
    });
  
    form.dispatchEvent(new Event("submit")); // Load all posts on page load
  
  });
  
  function deletePost(postId) {
    if (!confirm("Are you sure you want to delete this post?")) return;
  
    fetch(`../controllers/DeletePostController.php?id=${postId}`, {
      method: "POST"
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert("Post deleted successfully.");
        document.getElementById("searchForm").dispatchEvent(new Event("submit"));
      } else {
        alert("Failed to delete post.");
      }
    });
  }
  