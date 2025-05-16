const API = {
    getTotalOngoingProjects: async () => {
        const res = await fetch('/api/projects/ongoing-count');
        const data = await res.json();
        return data.count;
    },
    getTotalCompletedProjects: async () => {
        const res = await fetch('/api/projects/completed-count');
        const data = await res.json();
        return data.count;
    },
    getTotalJobsWithApplicants: async () => {
        const res = await fetch('/api/jobs/with-applicants-count');
        const data = await res.json();
        return data.count;
    },
    getTotalJobsPosted: async () => {
        const res = await fetch('/api/jobs/posted-count');
        const data = await res.json();
        return data.count;
    },
    getJobPosts: async () => {
        const res = await fetch('/api/jobs');
        const data = await res.json();
        return data.jobs;
    }
};

async function updateTotalOngoingProjects() {
    const ongoingProjects = await API.getTotalOngoingProjects();
    document.getElementById('ongoingCounter').textContent = ongoingProjects;
}

async function updateTotalCompletedProjects() {
    const completedProjects = await API.getTotalCompletedProjects();
    document.getElementById('completedCounter').textContent = completedProjects;
}

async function updateTotalJobsWithApplicants() {
    const jobsWithApplicants = await API.getTotalJobsWithApplicants();
    document.getElementById('jobswithapplicants').textContent = jobsWithApplicants;
}

async function updateTotalJobsPosted() {
    const totalJobs = await API.getTotalJobsPosted();
    document.getElementById('jobscounter').textContent = totalJobs;
}

function getStatusBadge(status) {
    switch (status) {
        case 'Open': return 'primary';
        case 'In Progress': return 'warning';
        case 'Completed': return 'success';
        default: return 'dark';
    }
}

async function renderJobCards() {
    const jobPosts = await API.getJobPosts();
    const container = document.getElementById("jobs-cards-container");
    container.innerHTML = '';

    jobPosts.forEach((job, index) => {
        const card = `
            <div class="col-12 col-md-6">
                <div class="card shadow-sm h-100 job-card" data-index="${index}" style="cursor:pointer;">
                    <div class="card-body">
                        <h5 class="card-title">${job.title}</h5>
                        <p class="card-text fw-bold">Posted: ${job.date}</p>
                        <span class="badge bg-${getStatusBadge(job.status)}">${job.status}</span>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += card;
    });

    document.querySelectorAll('.job-card').forEach(card => {
        card.addEventListener('click', function () {
            const jobIndex = this.getAttribute('data-index');
            showJobModal(jobPosts[jobIndex]);
        });
    });
}

function showJobModal(job) {
    document.getElementById('jobModalLabel').textContent = job.title;
    document.getElementById('modal-date').textContent = job.date;
    document.getElementById('modal-employee').textContent = job.employee;
    document.getElementById('modal-description').textContent = job.description || "No description provided.";
    document.getElementById('modal-type').textContent = job.type;
    document.getElementById('modal-platform').textContent = job.platform;
    document.getElementById('modal-ontransaction').textContent = job.ontransaction;
    document.getElementById('modal-location').textContent = job.location;
    document.getElementById('modal-oftransaction').textContent = job.oftransaction;
    const statusElement = document.getElementById('modal-status');
    const badgeColor = getStatusBadge(job.status);

    statusElement.textContent = job.status;
    statusElement.className = `fw-bold text-${badgeColor}`;

    const platformField = document.getElementById('modal-platform').parentElement;
    const onTransactionField = document.getElementById('modal-ontransaction').parentElement;
    const locationField = document.getElementById('modal-location').parentElement;
    const ofTransactionField = document.getElementById('modal-oftransaction').parentElement;

    if (job.type === 'online') {
        document.getElementById('modal-platform').textContent = job.platform || 'N/A';
        document.getElementById('modal-ontransaction').textContent = job.onlineTransaction || 'N/A';

        platformField.classList.remove('d-none');
        onTransactionField.classList.remove('d-none');
    } else if (job.type === 'offline') {
        document.getElementById('modal-location').textContent = job.location || 'N/A';
        document.getElementById('modal-oftransaction').textContent = job.offlineTransaction || 'N/A';

        locationField.classList.remove('d-none');
        ofTransactionField.classList.remove('d-none');
    }

    const modal = new bootstrap.Modal(document.getElementById('jobModal'));
    modal.show();
}

window.onload = async function () {
    await Promise.all([
        updateTotalJobsPosted(),
        updateTotalJobsWithApplicants(),
        updateTotalOngoingProjects(),
        updateTotalCompletedProjects()
    ]);
    await renderJobCards();
};
