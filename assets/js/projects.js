const urlParams = new URLSearchParams(window.location.search);
const projectStatus = urlParams.get('status') || 'open';
document.getElementById('projectStatusTitle').innerHTML = 
    `${projectStatus.charAt(0).toUpperCase() + projectStatus.slice(1)} Projects`;

const allProjects = [
    {
        title: "I need a Logo designer",
        category:"Online Logo Design",
        type: "Online",
        time: "3 months",
        expertise: "Intermmediate",
        date: "2025-04-20",
        description: "Design a logo for a new startup.",
        status: 'open',
        salary: "₱5,000",
        platform: "Upwork",
        transactionMode: "GCash"
    },
    {
        title: "I need a Logo designer",
        category:"Online Logo Design",
        type: "Online",
        time: "3 months",
        expertise: "Intermmediate",
        date: "2025-04-20",
        description: "Design a logo for a new startup.",
        status: 'ongoing',
        salary: "₱5,000",
        platform: "Upwork",
        transactionMode: "GCash"
    }
];

const filtered = allProjects.filter(p => p.status === projectStatus || !p.status);
const cardContainer = document.getElementById('cardContainer');

filtered.forEach((project, index) => {
    const col = document.createElement('div');
    col.className = 'col-12 col-md-6';

    const projectId = `project-${index}`;
    const isOnline = project.type.toLowerCase() === "online";

    col.innerHTML = `
        <div class="card shadow-sm" style="cursor: pointer;">
            <div class="card-body">
                <h4 class="card-title text-dark mb-3">${project.title}</h4>
                <h5 class="card-title text-dark mb-2">Date: ${project.date}</h4>
                <h5 class="card-title mb-2">Status: ${project.status.charAt(0).toUpperCase() + project.status.slice(1)}</h5>
                <div class="collapse mt-3" id="${projectId}">
                    <hr>
                    <p><strong>Description:</strong> ${project.category}</p>
                    <p><strong>Salary:</strong> ${project.time}</p>
                    <p><strong>Description:</strong> ${project.expertise}</p>
                    <p><strong>Salary:</strong> ${project.type}</p>
                    <p><strong>Description:</strong> ${project.description}</p>
                    <p><strong>Salary:</strong> ${project.salary}</p>
                    ${
                        isOnline
                            ? `
                                <p><strong>Platform:</strong> ${project.platform}</p>
                                <p><strong>Transaction Mode:</strong> ${project.transactionMode}</p>
                              `
                            : `
                                <p><strong>Site of Operation:</strong> ${project.site}</p>
                                <p><strong>Transaction Mode:</strong> ${project.transactionMode}</p>
                              `
                    }
                </div>
            </div>
        </div>
    `;

    // Add click listener to toggle collapse
    setTimeout(() => {
        const card = col.querySelector('.card');
        const collapseEl = col.querySelector(`#${projectId}`);
        const bsCollapse = new bootstrap.Collapse(collapseEl, { toggle: false });

        card.addEventListener('click', () => {
            bsCollapse.toggle();
        });
    });

    cardContainer.appendChild(col);
});
