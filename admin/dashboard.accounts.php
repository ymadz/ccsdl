<?php
$page_title = "CCS Ranking System - Dashboard";
session_start();

// Check if session is set
if (isset($_SESSION['account'])) {
    // Verify role_id existence and value
    if (empty($_SESSION['account']['role_id'])) {
        // Log error message for debugging
        error_log("Access attempt with missing or invalid role_id.");
        // Redirect to login page with an error message
        header('location: ../account/loginwcss.php?error=missing_role');
        exit;
    }
} else {
    // Log error message for debugging
    error_log("Unauthorized access attempt. Session not set.");
    // Redirect to login page with an error message
    header('location: ../account/loginwcss.php?error=unauthorized');
    exit;
}
$account = $_SESSION['account'];

// Include header file with error handling
$header_file = 'includes/header.php';
if (file_exists($header_file)) {
    require_once($header_file);
} else {
    // Log error message for debugging
    error_log("Header file ($header_file) not found.");
    // Display user-friendly error message
    die("An error occurred while loading the dashboard. Please try again later.");
}
require_once '../classes/account.class.php'; // Include the User class
$accountObj = new Account();
$accounts = $accountObj->getAccounts();

// Initialize variables
$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_account'])) {
    // Validate and process the form submission
    try {
        if ($_POST['password'] !== $_POST['confirm_password']) {
            throw new Exception("Passwords do not match");
        }

        $account = new Account();
        $result = $account->add([
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'confirm_password' => $_POST['confirm_password'],
            'role_id' => $_POST['role_id']
        ]);

        if ($result) {
            $success_message = "Account created successfully!";
        } else {
            $error_message = "Error creating account. Please try again.";
        }
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>

    <div class="wrapper">
        <?php include('includes/sidebar.php') ?>

        <div class="main p-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title">Account Management</h4>
                        <a href="dashboard.add.accounts.php" class="btn btn-success">
                            <i class="lni lni-plus"></i> Add New User
                        </a>
                    </div>

                    <!-- Search and Filter Section -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search by name, email, or ID...">
                        </div>
                        
                        <!-- Department Filter (for Staff) -->
                        <div class="col-md-4" id="departmentFilterDiv">
                            <select class="form-select" id="departmentFilter">
                                <option value="">Show All Departments</option>
                                <?php
                                require_once '../classes/department.class.php';
                                $departmentObj = new Department();
                                $departments = $departmentObj->getAllDepartments();
                                foreach ($departments as $dept) {
                                    echo "<option value='" . htmlspecialchars($dept['id']) . "'>" . 
                                         htmlspecialchars($dept['department_name']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <!-- Course Filter (for Students) -->
                        <div class="col-md-4" id="courseFilterDiv">
                            <select class="form-select" id="courseFilter">
                                <option value="">Show All Courses</option>
                                <?php
                                require_once '../classes/course.class.php';
                                $courseObj = new Course();
                                $courses = $courseObj->getAllCourses();
                                foreach ($courses as $course) {
                                    echo "<option value='" . htmlspecialchars($course['id']) . "'>" . 
                                         htmlspecialchars($course['course_name']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Role Tabs -->
                    <ul class="nav nav-tabs" id="accountTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="student-tab" data-bs-toggle="tab" data-bs-target="#student-content" type="button" role="tab" aria-controls="student-content" aria-selected="true">
                                <i class="lni lni-graduation"></i> Students
                                <span class="badge bg-success ms-2">
                                    <?php echo count(array_filter($accounts, function($account) { return $account['role_id'] == 3; })); ?>
                                </span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="staff-tab" data-bs-toggle="tab" data-bs-target="#staff-content" type="button" role="tab" aria-controls="staff-content" aria-selected="false">
                                <i class="lni lni-users"></i> Staff
                                <span class="badge bg-success ms-2">
                                    <?php echo count(array_filter($accounts, function($account) { return $account['role_id'] == 2; })); ?>
                                </span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin-content" type="button" role="tab" aria-controls="admin-content" aria-selected="false">
                                <i class="lni lni-shield"></i> Administrators
                                <span class="badge bg-success ms-2">
                                    <?php echo count(array_filter($accounts, function($account) { return $account['role_id'] == 1; })); ?>
                                </span>
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content p-3" id="accountTabContent">
                        <!-- Students Tab -->
                        <div class="tab-pane fade show active" id="student-content" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Full Name</th>
                                            <th>Course</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($accounts as $account): ?>
                                            <?php if ($account['role_id'] == 3): ?>
                                                <tr data-course="<?= htmlspecialchars($account['course_id']) ?>">
                                                    <td><?= htmlspecialchars($account['identifier']) ?></td>
                                                    <td><?= htmlspecialchars($account['firstname'] . ' ' . $account['lastname']) ?></td>
                                                    <td><?= htmlspecialchars($account['course_name'] ?? 'N/A') ?></td>
                                                    <td><?= htmlspecialchars($account['email']) ?></td>
                                                    <td><?= htmlspecialchars($account['status'] ?? 'Active') ?></td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="edit.account.php?id=<?= htmlspecialchars($account['id']) ?>" 
                                                               class="btn btn-warning btn-sm">
                                                                <i class="lni lni-pencil"></i>
                                                            </a>
                                                            <a href="delete.account.php?id=<?= htmlspecialchars($account['id']) ?>" 
                                                               class="btn btn-danger btn-sm"
                                                               onclick="return confirm('Are you sure you want to delete this account?')">
                                                                <i class="lni lni-trash-can"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Staff Tab -->
                        <div class="tab-pane fade" id="staff-content" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Staff ID</th>
                                            <th>Full Name</th>
                                            <th>Department</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($accounts as $account): ?>
                                            <?php if ($account['role_id'] == 2): ?>
                                                <tr data-department="<?= htmlspecialchars($account['department_id']) ?>">
                                                    <td><?= htmlspecialchars($account['identifier']) ?></td>
                                                    <td><?= htmlspecialchars($account['firstname'] . ' ' . $account['lastname']) ?></td>
                                                    <td><?= htmlspecialchars($account['department_name'] ?? 'N/A') ?></td>
                                                    <td><?= htmlspecialchars($account['email']) ?></td>
                                                    <td><?= htmlspecialchars($account['status'] ?? 'Active') ?></td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="edit.account.php?id=<?= htmlspecialchars($account['id']) ?>" 
                                                               class="btn btn-warning btn-sm">
                                                                <i class="lni lni-pencil"></i>
                                                            </a>
                                                            <a href="delete.account.php?id=<?= htmlspecialchars($account['id']) ?>" 
                                                               class="btn btn-danger btn-sm"
                                                               onclick="return confirm('Are you sure you want to delete this account?')">
                                                                <i class="lni lni-trash-can"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Admin Tab -->
                        <div class="tab-pane fade" id="admin-content" role="tabpanel" aria-labelledby="admin-tab">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Admin ID</th>
                                            <th>Full Name</th>
                                            <th>Email</th>
                                            <th>Username</th>
                                            <th>Date Registered</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($accounts as $account): ?>
                                            <?php if ($account['role_id'] == 1): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($account['identifier']); ?></td>
                                                    <td><?php echo htmlspecialchars($account['firstname'] . ' ' . $account['lastname']); ?></td>
                                                    <td><?php echo htmlspecialchars($account['email']); ?></td>
                                                    <td><?php echo htmlspecialchars($account['username']); ?></td>
                                                    <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($account['created_at']))); ?></td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="edit.account.php?id=<?= htmlspecialchars($account['id']) ?>" 
                                                               class="btn btn-warning btn-sm">
                                                                <i class="lni lni-pencil"></i>
                                                            </a>
                                                            <a href="delete.account.php?id=<?= htmlspecialchars($account['id']) ?>" 
                                                               class="btn btn-danger btn-sm"
                                                               onclick="return confirm('Are you sure you want to delete this account?')">
                                                                <i class="lni lni-trash-can"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
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

<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" action="" method="post">
                    <div class="col-md-3">
                        <label class="form-label">Role</label>
                        <select class="form-control" id="role_id" name="role_id" required>
                            <option value="" disabled selected>Select Role</option>
                            <option value="1">Admin</option>
                            <option value="2">Staff</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">ID Number</label>
                        <input type="text" class="form-control" id="identifier" name="identifier" required>
                        <small class="form-text text-muted">Format: 9 digits for Staff</small>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">First name</label>
                        <input type="text" class="form-control" name="firstname" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Middle name</label>
                        <input type="text" class="form-control" name="middlename">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Last name</label>
                        <input type="text" class="form-control" name="lastname" required>
                    </div>

                    <div class="col-md-3" id="departmentDiv" style="display: none;">
                        <label class="form-label">Department</label>
                        <select class="form-control" id="department" name="other">
                            <option value="" disabled selected>Select Department</option>
                            <?php
                            $departments = $departmentObj->getAllDepartments();
                            foreach ($departments as $dept) {
                                echo "<option value='{$dept['id']}'>{$dept['department_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                        <small class="form-text text-muted">Use WMSU email: username@wmsu.edu.ph</small>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>

                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('role_id').addEventListener('change', function() {
    const departmentDiv = document.getElementById('departmentDiv');
    const departmentSelect = document.getElementById('department');
    
    // Show department selection only for Staff (role_id = 2)
    if (this.value === '2') {
        departmentDiv.style.display = 'block';
        departmentSelect.required = true;
    } else {
        departmentDiv.style.display = 'none';
        departmentSelect.required = false;
    }
});
</script>

<script>
$(document).ready(function() {
    // Function to filter table
    function filterTable() {
        const searchText = $("#searchInput").val().toLowerCase();
        const departmentFilter = $("#departmentFilter").val();
        const courseFilter = $("#courseFilter").val();
        const activeTab = $('.nav-link.active').attr('id');

        console.log('Filtering with:', { searchText, departmentFilter, courseFilter, activeTab }); // Debug line

        $('.tab-pane.active tbody tr').each(function() {
            let row = $(this);
            let showRow = true;

            // Text search
            if (searchText) {
                const textContent = row.text().toLowerCase();
                if (!textContent.includes(searchText)) {
                    showRow = false;
                }
            }

            // Department filter (for staff)
            if (activeTab === 'staff-tab' && departmentFilter) {
                const departmentId = row.attr('data-department');
                console.log('Department comparison:', departmentId, departmentFilter); // Debug line
                if (departmentId !== departmentFilter) {
                    showRow = false;
                }
            }

            // Course filter (for students)
            if (activeTab === 'student-tab' && courseFilter) {
                const courseId = row.attr('data-course');
                console.log('Course comparison:', courseId, courseFilter); // Debug line
                if (courseId !== courseFilter) {
                    showRow = false;
                }
            }

            row.toggle(showRow);
        });

        // Log visible rows count (for debugging)
        console.log('Visible rows:', $('.tab-pane.active tbody tr:visible').length);
    }

    // Event listeners
    $("#searchInput").on("keyup", filterTable);
    $("#departmentFilter").on("change", filterTable);
    $("#courseFilter").on("change", filterTable);

    // Handle tab changes
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        updateFilterVisibility();
        resetFilters();
    });

    // Function to update filter visibility
    function updateFilterVisibility() {
        const activeTab = $('.nav-link.active').attr('id');
        
        if (activeTab === 'student-tab') {
            $('#departmentFilterDiv').hide();
            $('#courseFilterDiv').show();
        } else if (activeTab === 'staff-tab') {
            $('#departmentFilterDiv').show();
            $('#courseFilterDiv').hide();
        } else {
            $('#departmentFilterDiv').hide();
            $('#courseFilterDiv').hide();
        }
    }

    // Function to reset filters
    function resetFilters() {
        $("#searchInput").val('');
        $("#departmentFilter").val('');
        $("#courseFilter").val('');
        $('.tab-pane.active tbody tr').show();
    }

    // Initial setup
    updateFilterVisibility();
});
</script>

<!-- Make sure you have jQuery included -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tabs
    var triggerTabList = [].slice.call(document.querySelectorAll('#accountTabs button'));
    triggerTabList.forEach(function(triggerEl) {
        new bootstrap.Tab(triggerEl);
    });

    // Search functionality
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $(".tab-pane.active tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Reset filters when changing tabs
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
        $("#searchInput").val('');
        $(".tab-pane.active tbody tr").show();
    });
});
</script>

<?php include('includes/footer.php') ?>