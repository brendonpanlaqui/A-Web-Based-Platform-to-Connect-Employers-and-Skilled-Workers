document.addEventListener('DOMContentLoaded', () => {
  const searchInput = document.getElementById('searchInput');
  const filterType = document.getElementById('filterType');
  const filterCategory = document.getElementById('filterCategory');
  const jobResultsTableBody = document.getElementById('jobResultsTableBody');
  const applicationForm = document.getElementById('applicationForm');
  const coverLetter = document.getElementById('coverLetter');
  const selectedJobId = document.getElementById('selectedJobId');
  const applyModal = new bootstrap.Modal(document.getElementById('applyModal'));

  // Category options depending on type
  const categoryOptionsByType = {
    online: [
      "Front-end design",
      "Web development",
      "Copywriting",
      "Virtual assistance",
      "Digital marketing"
    ],
    offline: [
      "Construction",
      "Delivery services",
      "House cleaning",
      "Electrical repair",
      "Gardening"
    ]
  };

  // Update category options based on selected type
  function updateCategoryOptions() {
    const selectedType = filterType.value;
    const categories = categoryOptionsByType[selectedType] || [];

    filterCategory.innerHTML = `<option value="">All Categories</option>`;
    categories.forEach(cat => {
      const option = document.createElement('option');
      option.value = cat;
      option.textContent = cat;
      filterCategory.appendChild(option);
    });
  }

  // Fetch and render jobs
  function fetchJobs() {
    const search = encodeURIComponent(searchInput.value.trim());
    const type = encodeURIComponent(filterType.value);
    const category = encodeURIComponent(filterCategory.value);

    let url = `../controllers/JobListController.php?search=${search}&type=${type}&category=${category}`;

    fetch(url)
      .then(response => response.json())
      .then(data => {
        jobResultsTableBody.innerHTML = '';

        const jobs = data.jobs || [];

        if (jobs.length === 0) {
          jobResultsTableBody.innerHTML = `
            <tr>
              <td colspan="4" class="text-center">No jobs found.</td>
            </tr>
          `;
        } else {
          jobs.forEach(job => {
            const row = document.createElement('tr');
            row.innerHTML = `
              <td>${job.title}</td>
              <td>${job.first_name} ${job.last_name}</td>
              <td>${job.type}</td>
              <td>${job.category}</td>
              <td>
                <button class="btn btn-sm btn-danger apply-btn" data-job-id="${job.id}">Apply</button>
              </td>
            `;
            jobResultsTableBody.appendChild(row);
          });

          document.querySelectorAll('.apply-btn').forEach(button => {
            button.addEventListener('click', () => {
              selectedJobId.value = button.getAttribute('data-job-id');
              coverLetter.value = '';
              applyModal.show();
            });
          });
        }
      })
      .catch(error => {
        console.error('Error fetching jobs:', error);
        jobResultsTableBody.innerHTML = `
          <tr>
            <td colspan="4" class="text-center text-danger">Error loading jobs.</td>
          </tr>
        `;
      });
  }

  // Submit job application
  applicationForm.addEventListener('submit', (e) => {
    e.preventDefault();

    const formData = new FormData();
    formData.append('job_id', selectedJobId.value);
    formData.append('cover_letter', coverLetter.value);

    fetch('../controllers/JobListController.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert('Application submitted successfully.');
        applyModal.hide();
        fetchJobs();
      } else {
        alert(data.error || 'Failed to apply.');
      }
    })
    
  });

  // Event listeners
  searchInput.addEventListener('input', fetchJobs);
  filterType.addEventListener('change', () => {
    updateCategoryOptions(); // Dynamically update categories when type changes
    fetchJobs();
  });
  filterCategory.addEventListener('change', fetchJobs);

  // Initial setup
  updateCategoryOptions();
  fetchJobs();
});
