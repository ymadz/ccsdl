<?php
$page_title = "CCS Ranking System - Edit Account";
session_start();

// Check if session is set
if (!isset($_SESSION['account']) || empty($_SESSION['account']['role_id'])) {
    header('location: ../account/loginwcss.php?error=unauthorized');
    exit;
}

// Include necessary files
require_once '../classes/account.class.php';
require_once '../classes/user.class.php';
require_once '../classes/role.class.php';
require_once '../classes/course.class.php';
require_once '../classes/department.class.php';

// Initialize objects
$accountObj = new Account();
$userObj = new User();
$roleObj = new Role();
$courseObj = new Course();
$departmentObj = new Department();

// Get account ID from URL
$account_id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$account_id) {
    header('location: dashboard.accounts.php?error=1&message=Invalid account ID');
    exit;
}

// Get account and user details
$account = $accountObj->getAccount($account_id);
if (!$account) {
    header('location: dashboard.accounts.php?error=1&message=Account not found');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Determine course_id and department_id based on role
        $course_id = null;
        $department_id = null;
        
        if ($_POST['role'] == '3') { // Student
            $course_id = $_POST['course_id'];
        } elseif ($_POST['role'] == '2') { // Staff
            $department_id = $_POST['department_id'];
        }

        // Update user details
        $userObj->updateUser([
            'id' => $account['user_id'],
            'identifier' => $_POST['identifier'],
            'firstname' => $_POST['firstname'],
            'middlename' => $_POST['middlename'],
            'lastname' => $_POST['lastname'],
            'email' => $_POST['email'],
            'course_id' => $course_id,
            'department_id' => $department_id
        ]);

        // Update account details
        $accountObj->updateAccount([
            'id' => $account_id,
            'username' => $_POST['username'],
            'role_id' => $_POST['role']
        ]);

        // Redirect with success message
        header('location: dashboard.accounts.php?success=1&message=Account updated successfully');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Get roles, courses, and departments for dropdowns
$roles = $roleObj->getAllRoles();
$courses = $courseObj->getAllCourses();
$departments = $departmentObj->getAllDepartments();

include('includes/header.php');
?>

<div class="wrapper">
    <?php include('includes/sidebar.php') ?>

    <div class="main p-3">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Account</h4>
                <hr>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">ID Number</label>
                        <input type="text" class="form-control" name="identifier" value="<?= htmlspecialchars($account['identifier']) ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" name="firstname" value="<?= htmlspecialchars($account['firstname']) ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Middle Name</label>
                        <input type="text" class="form-control" name="middlename" value="<?= htmlspecialchars($account['middlename']) ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="lastname" value="<?= htmlspecialchars($account['lastname']) ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($account['email']) ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($account['username']) ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Role</label>
                        <select class="form-control" name="role" id="role" required>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['id'] ?>" <?= ($role['id'] == $account['role_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($role['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4" id="course-div" style="<?= $account['role_id'] == 3 ? '' : 'display: none;' ?>">
                        <label class="form-label">Course</label>
                        <select class="form-control" name="course_id" id="course">
                            <option value="">Select Course</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?= $course['id'] ?>" <?= ($course['id'] == $account['course_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($course['course_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4" id="department-div" style="<?= $account['role_id'] == 2 ? '' : 'display: none;' ?>">
                        <label class="form-label">Department</label>
                        <select class="form-control" name="department_id" id="department">
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $department): ?>
                                <option value="<?= $department['id'] ?>" <?= ($department['id'] == $account['department_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($department['department_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Update Account</button>
                        <a href="dashboard.accounts.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('role').addEventListener('change', function() {
    const courseDiv = document.getElementById('course-div');
    const departmentDiv = document.getElementById('department-div');
    
    if (this.value === '3') { // Student
        courseDiv.style.display = '';
        departmentDiv.style.display = 'none';
    } else if (this.value === '2') { // Staff
        courseDiv.style.display = 'none';
        departmentDiv.style.display = '';
    } else {
        courseDiv.style.display = 'none';
        departmentDiv.style.display = 'none';
    }
});
</script>

<?php include('includes/footer.php') ?>
