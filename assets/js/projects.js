const urlParams = new URLSearchParams(window.location.search);
const projectStatus = urlParams.get('status') || 'open';
document.getElementById('projectStatusTitle').innerHTML = 
    `${projectStatus.charAt(0).toUpperCase() + projectStatus.slice(1)} Projects`;

const cardContainer = document.getElementById('cardContainer');

// Fetch the projects from the server
fetch(`../controllers/ProjectController.php?status=${projectStatus}&t=${Date.now()}`)
    .then(response => response.json())
    .then(allProjects => {
         console.log("Fetched Projects:", allProjects);
        const filtered = allProjects.filter(p => p.status === projectStatus || !p.status);

        if (filtered.length === 0) {
            cardContainer.innerHTML = `
                <div class="alert alert-warning w-100 text-center" role="alert">
                    No ${projectStatus} projects found.
                </div>
            `;
            return; // stop here
        }

        filtered.forEach((project, index) => {
            const col = document.createElement('div');
            col.className = 'col-12 col-md-6';

            const projectId = `project-${index}`;
            const isOnline = (project.type || '').toLowerCase() === "online";
            col.innerHTML = `
                <div class="card shadow-sm" style="cursor: pointer;">
                    <div class="card-body">
                        <h4 class="card-title text-dark mb-3">${project.title}</h4>
                        <h5 class="card-title text-dark mb-2">Date: ${project.datePosted}</h5>
                        <h5 class="card-title mb-2">Status: ${project.status.charAt(0).toUpperCase() + project.status.slice(1)}</h5>
                        <div class="collapse mt-3" id="${projectId}">
                            <hr>
                            <p><strong>Category:</strong> ${project.category}</p>
                            <p><strong>Time:</strong> ${project.time}</p>
                            <p><strong>Expertise Level:</strong> ${project.expertise}</p>
                            <p><strong>Type:</strong> ${project.type}</p>
                            <p><strong>Description:</strong> ${project.description}</p>
                            <p><strong>Salary:</strong> ${project.salary}</p>
                            ${
                                isOnline
                                    ? `
                                        <p><strong>Platform:</strong> ${project.platform}</p>
                                        <p><strong>Transaction Mode:</strong> ${project.transactionMode}</p>
                                      `
                                    : `
                                        <p><strong>Site of Operation:</strong> ${project.location}</p>
                                        <p><strong>Transaction Mode:</strong> ${project.transactionMode}</p>
                                      `
                            }
                            <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${project.id}" onclick="event.stopPropagation(); deleteProject(${project.id}, this)">Delete</button>
                            ${project.status !== 'completed' ? `
                                <button class="btn btn-sm btn-outline-success" data-id="${project.id}" onclick="event.stopPropagation(); markAsCompleted(${project.id}, this)">Done</button>
                            ` : ''}

                        </div>
                    </div>
                </div>
            `;

            const card = col.querySelector('.card');
            const collapseEl = col.querySelector(`#${projectId}`);
            const bsCollapse = new bootstrap.Collapse(collapseEl, { toggle: false });

            card.addEventListener('click', () => {
                bsCollapse.toggle();
            });

            cardContainer.appendChild(col);
        });
    })
    .catch(error => {
        console.error('Error fetching projects:', error);
        cardContainer.innerHTML = `<div class="alert alert-danger">Failed to load projects. Please try again later.</div>`;
    });
    function deleteProject(projectId, button) {
        if (!confirm("Are you sure you want to delete this project?")) return;
    
        fetch('../controllers/DeletePostController.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ project_id: projectId })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                const card = button.closest('.col-12.col-md-6');
                card.remove();
            } else {
                alert(result.error || 'Failed to delete project.');
            }
        })
    }
    function markAsCompleted(projectId, button) {
    if (!confirm("Mark this project as completed?")) return;

    fetch('../controllers/JobApplicationController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            project_id: projectId,
            action: 'complete'
        })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            // Update the card visually
            const card = button.closest('.card-body');
            const statusEl = card.querySelector('h5:nth-of-type(2)');
            statusEl.textContent = "Status: Completed";
            button.remove(); // remove the button after marking as done
        } else {
            alert(result.error || "Failed to mark project as completed.");
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("An error occurred while updating the project status.");
    });
}

