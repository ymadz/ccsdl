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
    error_log("Unauthorized access attempt. Session not set.");
    header('location: ../account/loginwcss.php?error=unauthorized');
    exit;
}

require_once('includes/header.php');
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
                                <label for="semesterFilter" class="filter-label">🗓️ Semester</label>
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
                                                <button class="btn btn-sm btn-warning" onclick="showEditModal(<?= $app['id'] ?>)">Edit</button>
                                                <?php if($app['status'] === 'Pending'): ?>
                                                    <button class="btn btn-sm btn-success" onclick="approveApplication(<?= $app['id'] ?>)">Approve</button>
                                                    <button class="btn btn-sm btn-danger" onclick="showRejectModal(<?= $app['id'] ?>)">Reject</button>
                                                <?php endif; ?>
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
                                        <th>Approved By</th>
                                        <th>Date Approved</th>
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
                                            <td><?= htmlspecialchars($app['approved_by_name'] ?? 'N/A') ?></td>
                                            <td><?= $app['approved_at'] ? date('M d, Y', strtotime($app['approved_at'])) : 'N/A' ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" onclick="viewDetails(<?= $app['id'] ?>)">View</button>
                                                <button class="btn btn-sm btn-warning" onclick="showEditModal(<?= $app['id'] ?>)">Edit</button>
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
                                        <th>Rejected By</th>
                                        <th>Date Rejected</th>
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
                                            <td><?= htmlspecialchars($app['rejected_by_name'] ?? 'N/A') ?></td>
                                            <td><?= $app['rejected_at'] ? date('M d, Y', strtotime($app['rejected_at'])) : 'N/A' ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" onclick="viewDetails(<?= $app['id'] ?>)">View</button>
                                                <button class="btn btn-sm btn-warning" onclick="showEditModal(<?= $app['id'] ?>)">Edit</button>
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

<!-- Add this modal for editing application -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editApplicationId">
                <div class="mb-3">
                    <label for="editSchoolYear" class="form-label">School Year</label>
                    <input type="text" class="form-control" id="editSchoolYear">
                </div>
                <div class="mb-3">
                    <label for="editSemester" class="form-label">Semester</label>
                    <select class="form-select" id="editSemester">
                        <option value="First">First</option>
                        <option value="Second">Second</option>
                    </select>
                </div>
                <div id="editSubjectsContainer">
                    <!-- Subjects will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveApplicationChanges()">Save Changes</button>
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
        fetch('process_application.php', {
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

    fetch('process_application.php', {
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
    // Calculate average rating
    let totalRating = 0;
    data.subjects.forEach(subject => {
        totalRating += parseFloat(subject.rating);
    });
    const averageRating = (totalRating / data.subjects.length).toFixed(2);

    let html = `
        <div class="container">
            <h6>Student Information</h6>
            <p>Name: ${data.student_name || 'N/A'}</p>
            <p>School Year: ${data.school_year || 'N/A'}</p>
            <p>Semester: ${data.semester || 'N/A'}</p>
            <p>Adviser: ${data.adviser || 'N/A'}</p>
            
            <h6 class="mt-4">Subjects</h6>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    data.subjects.forEach(subject => {
        html += `
            <tr>
                <td>${subject.subject_code || 'N/A'}</td>
                <td>${subject.subject_name || 'N/A'}</td>
                <td>${subject.is_laboratory ? 'Laboratory' : 'Lecture'}</td>
                <td>${subject.rating || 'N/A'}</td>
            </tr>
        `;
    });
    
    html += `
                </tbody>
            </table>
            <p class="mt-3"><strong>Average Rating: ${averageRating}</strong></p>
        </div>
    `;
    
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
            const rows = document.querySelectorAll(`#${tableId} tbody tr`);
            let visibleCount = 0;
            
            rows.forEach(row => {
                const studentName = row.cells[0].textContent.toLowerCase();
                const schoolYear = row.cells[1].textContent;
                const semester = row.cells[2].textContent;
                
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
});

function showEditModal(applicationId) {
    // Fetch application details
    fetch(`get_application_details.php?id=${applicationId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editApplicationId').value = applicationId;
            document.getElementById('editSchoolYear').value = data.school_year;
            document.getElementById('editSemester').value = data.semester;
            
            // Load subjects
            const subjectsContainer = document.getElementById('editSubjectsContainer');
            let tableHTML = `
                <h6 class="mt-4">Subjects</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Rating</th>
                        </tr>
                    </thead>
                    <tbody>`;
            
            data.subjects.forEach(subject => {
                tableHTML += `
                    <tr>
                        <td>${subject.subject_code}</td>
                        <td>${subject.subject_name}</td>
                        <td>${subject.is_laboratory ? 'Laboratory' : 'Lecture'}</td>
                        <td>
                            <input type="number" class="form-control form-control-sm" 
                                   value="${subject.rating}" 
                                   min="1.0" max="5.0" step="0.1"
                                   data-subject-code="${subject.subject_code}">
                        </td>
                    </tr>`;
            });
            
            tableHTML += `
                    </tbody>
                </table>`;
                
            subjectsContainer.innerHTML = tableHTML;
            
            new bootstrap.Modal(document.getElementById('editModal')).show();
        });
}

function saveApplicationChanges() {
    const applicationId = document.getElementById('editApplicationId').value;
    const schoolYear = document.getElementById('editSchoolYear').value;
    const semester = document.getElementById('editSemester').value;
    
    // Collect subject ratings
    const subjectRatings = [];
    document.querySelectorAll('#editSubjectsContainer input[type="number"]').forEach(input => {
        if (input.value && input.dataset.subjectCode) {
            subjectRatings.push({
                subject_code: input.dataset.subjectCode,
                rating: parseFloat(input.value)
            });
        }
    });

    const data = {
        application_id: parseInt(applicationId),
        school_year: schoolYear,
        semester: semester,
        subject_ratings: subjectRatings
    };
    
    // Send update request
    fetch('update_application.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.text())  // First get the raw response
    .then(text => {
        try {
            return JSON.parse(text);  // Try to parse it as JSON
        } catch (e) {
            console.error('Server response:', text);  // Log the raw response if it's not valid JSON
            throw new Error('Invalid server response');
        }
    })
    .then(data => {
        if (data.success) {
            alert('Changes saved successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error saving changes: ' + error.message);
        console.error('Error:', error);
    });
}

function confirmDelete(applicationId) {
    if (confirm('Are you sure you want to delete this application? This action cannot be undone.')) {
        deleteApplication(applicationId);
    }
}

function deleteApplication(applicationId) {
    fetch('delete_application.php', {
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

<style>
.search-filter-container {
    background: rgba(0, 66, 37, 0.08);
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.filter-label {
    color: #003319;
    font-weight: 600;
    margin-bottom: 5px;
}

#searchInput, .form-select {
    border: 2px solid #004225;
    padding: 10px 15px;
    border-radius: 6px;
    transition: all 0.3s ease;
    font-weight: 500;
    color: #003319;
}

#searchInput:focus, .form-select:focus {
    border-color: #003319;
    box-shadow: 0 0 0 0.2rem rgba(0, 66, 37, 0.15);
    outline: none;
}

.form-select:hover {
    border-color: #003319;
}
</style>