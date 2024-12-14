<?php
$page_title = "CCS Ranking System - Applications";
session_start();

// Check if session is set
if (isset($_SESSION['account'])) {
    if (empty($_SESSION['account']['role_id'])) {
        error_log("Access attempt with missing or invalid role_id.");
        header('location: ../account/loginwcss.php?error=missing_role');
        exit;
    }
} else {
    error_log("Unauthorized access attempt to applications.");
    header('location: ../account/loginwcss.php?error=nosession');
    exit;
}

require_once('includes/header.php');
require_once('includes/sidebar.php');
require_once('../classes/application.class.php');

$applicationObj = new Application();
$pendingApplications = $applicationObj->getPendingApplications();
$approvedApplications = $applicationObj->getApprovedApplications();
$rejectedApplications = $applicationObj->getRejectedApplications();
?>

<div class="wrapper">
    <?php include('includes/sidebar.php') ?>
    <div class="main p-3">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title pb-2">Dean's List Applications</h4>
                <hr>
                <div class="search-filter-container">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-2">
                                <label for="searchInput" class="filter-label">🔍 Search Student</label>
                                <input type="text" id="searchInput" class="form-control" placeholder="Enter student name...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-2">
                                <label for="schoolYearFilter" class="filter-label">📅 School Year</label>
                                <select id="schoolYearFilter" class="form-select">
                                    <option value="">All School Years</option>
                                    <?php
                                    $schoolYears = array_unique(array_merge(
                                        array_column($pendingApplications, 'school_year'),
                                        array_column($approvedApplications, 'school_year'),
                                        array_column($rejectedApplications, 'school_year')
                                    ));
                                    foreach($schoolYears as $year) {
                                        if(!empty($year)) {
                                            echo "<option value='" . htmlspecialchars($year) . "'>" . htmlspecialchars($year) . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-2">
                                <label for="semesterFilter" class="filter-label">🗓 Semester</label>
                                <select id="semesterFilter" class="form-select">
                                    <option value="">All Semesters</option>
                                    <option value="First">First</option>
                                    <option value="Second">Second</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pending" type="button">
                            Pending <span class="badge bg-warning"><?= count($pendingApplications) ?></span>
                        </button>
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#approved" type="button">
                            Approved <span class="badge bg-success"><?= count($approvedApplications) ?></span>
                        </button>
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#rejected" type="button">
                            Rejected <span class="badge bg-danger"><?= count($rejectedApplications) ?></span>
                        </button>
                    </div>
                </nav>

                <div class="tab-content mt-3">
                    <!-- Pending Applications -->
                    <div class="tab-pane fade show active" id="pending">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>School Year</th>
                                        <th>Semester</th>
                                        <th>Average Rating</th>
                                        <th>Date Applied</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pendingApplications as $app): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($app['student_name']) ?></td>
                                            <td><?= htmlspecialchars($app['school_year']) ?></td>
                                            <td><?= htmlspecialchars($app['semester']) ?></td>
                                            <td><?= number_format($app['total_rating'], 2) ?></td>
                                            <td><?= date('M d, Y', strtotime($app['created_at'])) ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" onclick="viewDetails(<?= $app['id'] ?>)">View</button>
                                                <button class="btn btn-sm btn-success" onclick="approveApplication(<?= $app['id'] ?>)">Approve</button>
                                                <button class="btn btn-sm btn-danger" onclick="showRejectModal(<?= $app['id'] ?>)">Reject</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Approved Applications -->
                    <div class="tab-pane fade" id="approved">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>School Year</th>
                                        <th>Semester</th>
                                        <th>Average Rating</th>
                                        <th>Date Applied</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($approvedApplications as $app): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($app['student_name']) ?></td>
                                            <td><?= htmlspecialchars($app['school_year']) ?></td>
                                            <td><?= htmlspecialchars($app['semester']) ?></td>
                                            <td><?= number_format($app['total_rating'], 2) ?></td>
                                            <td><?= date('M d, Y', strtotime($app['created_at'])) ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" onclick="viewDetails(<?= $app['id'] ?>)">View</button>
                                                <button class="btn btn-sm btn-warning" onclick="window.location.href='edit_application.php?id=<?= $app['id'] ?>'">Edit</button>
                                                <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $app['id'] ?>)">Delete</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Rejected Applications -->
                    <div class="tab-pane fade" id="rejected">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>School Year</th>
                                        <th>Semester</th>
                                        <th>Average Rating</th>
                                        <th>Reason</th>
                                        <th>Date Applied</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rejectedApplications as $app): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($app['student_name']) ?></td>
                                            <td><?= htmlspecialchars($app['school_year']) ?></td>
                                            <td><?= htmlspecialchars($app['semester']) ?></td>
                                            <td><?= number_format($app['total_rating'], 2) ?></td>
                                            <td><?= htmlspecialchars($app['rejection_reason']) ?></td>
                                            <td><?= date('M d, Y', strtotime($app['created_at'])) ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" onclick="viewDetails(<?= $app['id'] ?>)">View</button>
                                                <button class="btn btn-sm btn-warning" onclick="window.location.href='edit_application.php?id=<?= $app['id'] ?>'">Edit</button>
                                                <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $app['id'] ?>)">Delete</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add this modal for viewing application details -->
<div class="modal fade" id="applicationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Application Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="applicationDetails">
                <!-- Details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Add this modal for rejection reason -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="applicationId">
                <div class="mb-3">
                    <label for="rejectionReason" class="form-label">Reason for Rejection</label>
                    <textarea class="form-control" id="rejectionReason" rows="3" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="rejectApplication()">Reject</button>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php') ?>

<script>
function viewDetails(applicationId) {
    fetch(`get_application_details.php?id=${applicationId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('applicationDetails').innerHTML = formatApplicationDetails(data);
            new bootstrap.Modal(document.getElementById('applicationModal')).show();
        });
}

function showRejectModal(applicationId) {
    document.getElementById('applicationId').value = applicationId;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}

function approveApplication(applicationId) {
    if (confirm('Are you sure you want to approve this application?')) {
        fetch('../admin/process_application.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'approve',
                applicationId: applicationId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}

function rejectApplication() {
    const applicationId = document.getElementById('applicationId').value;
    const reason = document.getElementById('rejectionReason').value;

    if (!reason.trim()) {
        alert('Please provide a reason for rejection');
        return;
    }

    fetch('../admin/process_application.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'reject',
            applicationId: applicationId,
            reason: reason
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    });
}

function formatApplicationDetails(data) {
    // Update to match new database structure
    let html = `
        <div class="application-details">
            <h5>Student Information</h5>
            <p>Name: ${data.first_name} ${data.last_name}</p>
            <p>School Year: ${data.school_year}</p>
            <p>Semester: ${data.semester}</p>
            
            <h5>Subjects</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>`;
    
    if (data.subjects && Array.isArray(data.subjects)) {
        data.subjects.forEach(subject => {
            html += `<tr>
                <td>${subject.code}</td>
                <td>${subject.grade}</td>
            </tr>`;
        });
    }
    
    html += `</tbody>
        </table>
        </div>`;
    
    return html;
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const schoolYearFilter = document.getElementById('schoolYearFilter');
    const semesterFilter = document.getElementById('semesterFilter');
    
    function filterTables() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedYear = schoolYearFilter.value;
        const selectedSemester = semesterFilter.value;
        
        // Function to filter each table
        function filterTable(tableId) {
            const rows = document.querySelectorAll(`#${tableId} .table tbody tr`);
            let visibleCount = 0;
            
            rows.forEach(row => {
                const studentName = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const schoolYear = row.querySelector('td:nth-child(2)').textContent;
                const semester = row.querySelector('td:nth-child(3)').textContent;
                
                const matchesSearch = studentName.includes(searchTerm);
                const matchesYear = !selectedYear || schoolYear.trim() === selectedYear;
                const matchesSemester = !selectedSemester || semester.trim() === selectedSemester;
                
                if (matchesSearch && matchesYear && matchesSemester) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Update badge count
            const badge = document.querySelector(`button[data-bs-target="#${tableId}"] .badge`);
            if (badge) {
                badge.textContent = visibleCount;
            }
        }
        
        // Filter all tables
        filterTable('pending');
        filterTable('approved');
        filterTable('rejected');
    }
    
    // Add event listeners
    searchInput.addEventListener('input', filterTables);
    schoolYearFilter.addEventListener('change', filterTables);
    semesterFilter.addEventListener('change', filterTables);

    // Initial filter to set up correct counts
    filterTables();
});

function confirmDelete(applicationId) {
    if (confirm('Are you sure you want to delete this application? This action cannot be undone.')) {
        deleteApplication(applicationId);
    }
}

function deleteApplication(applicationId) {
    fetch('../admin/delete_application.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            applicationId: applicationId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting the application');
    });
}
</script>