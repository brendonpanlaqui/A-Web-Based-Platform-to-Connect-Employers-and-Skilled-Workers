document.addEventListener("DOMContentLoaded", function () {
  const searchForm = document.getElementById("searchForm");
  const searchInput = document.getElementById("searchInput");
  const searchResults = document.getElementById("searchResults");

  if (searchForm && searchInput && searchResults) {
    searchForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const query = searchInput.value.trim();

      if (!query) {
        searchResults.innerHTML = `<div class="alert alert-danger">Please enter a search term.</div>`;
        return;
      }

      fetch(`../controllers/SearchJobsController.php?q=${encodeURIComponent(query)}`)
        .then(res => res.text())
        .then(text => {
          console.log("Raw response:", text);

          let data;
          try {
            data = JSON.parse(text);
          } catch (err) {
            searchResults.innerHTML = `<div class="alert alert-danger">Invalid JSON response. See console for details.</div>`;
            console.error("JSON parse error:", err);
            return;
          }

          if (!data.success) {
            searchResults.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
            return;
          }

          if (data.jobs.length === 0) {
            searchResults.innerHTML = `<div class="alert alert-info">No jobs found for "${query}".</div>`;
            return;
          }

          const cards = data.jobs.map(job => `
            <div class="card mb-3 shadow-sm border border-dark">
              <div class="card-body">
                <h5 class="card-title">${escapeHtml(job.title)}</h5>
                <p class="card-text">${escapeHtml(job.category)}</p>
                <p class="card-text text-muted"><small>Service Type: ${escapeHtml(job.type)}</small></p>
                <p class="card-text"><small class="text-muted">Posted: ${new Date(job.created_at).toLocaleDateString()}</small></p>
                <button class="btn btn-danger" title="Login required to apply" onclick="window.location.href='login.php'">Login to apply</button>
              </div>
            </div>
          `).join("");

          searchResults.innerHTML = cards;
        })
        .catch(err => {
          searchResults.innerHTML = `<div class="alert alert-danger">Failed to search jobs: ${err.message}</div>`;
        });
    });
  }

  // XSS protection
  function escapeHtml(text) {
    if (!text) return '';
    return text.replace(/[&<>"']/g, m => ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;'
    })[m]);
  }
});
