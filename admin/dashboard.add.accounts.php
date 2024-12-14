<?php
$page_title = "CCS Ranking System - Add Account";
include_once 'includes/header.php';
require_once '../tools/functions.php';
require_once '../classes/user.class.php';
require_once '../classes/account.class.php';
require_once '../classes/role.class.php';
require_once '../classes/course.class.php';
require_once '../classes/department.class.php';

// Initialize objects
$userObj = new User();
$accountObj = new Account();
$roleObj = new Role();
$courseObj = new Course();
$departmentObj = new Department();

// Initialize variables
$identifier = $first_name = $middle_name = $last_name = $username = $password = $role_id = $email = $other = '';
$identifierErr = $first_nameErr = $middle_nameErr = $last_nameErr = $usernameErr = $passwordErr = $role_idErr = $emailErr = $otherErr = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Validate and clean input
        $identifier = clean_input($_POST['identifier']);
        $first_name = clean_input($_POST['firstname']);
        $middle_name = clean_input($_POST['middlename']);
        $last_name = clean_input($_POST['lastname']);
        $username = clean_input($_POST['username']);
        $password = clean_input($_POST['password']);
        $role_id = clean_input($_POST['role_id']);
        $email = clean_input($_POST['email']);
        $other = isset($_POST['other']) ? clean_input($_POST['other']) : '';

        // Validate role (must be Admin or Staff)
        if (!in_array($role_id, [1, 2])) {
            throw new Exception("Invalid role selected!");
        }

        // Store data in the User model
        $userObj->identifier = $identifier;
        $userObj->firstname = $first_name;
        $userObj->middlename = $middle_name;
        $userObj->lastname = $last_name;
        $userObj->email = $email;

        // Set department for Staff
        if ($role_id == 2) {
            $userObj->department_id = $_POST['other'];
        }

        // Save the user and get the inserted ID
        $userId = $userObj->store();
        if (!$userId) {
            throw new Exception("Failed to store user in the database.");
        }

        // Store account details
        $accountObj->user_id = $userId;
        $accountObj->username = $username;
        $accountObj->password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
        $accountObj->role_id = $role_id;
        $accountObj->status = 'Active';

        // Save the account
        if ($accountObj->store($userId)) {
            // Success message
            $_SESSION['success_message'] = "Account created successfully!";
            header("Location: dashboard.accounts.php"); // Redirect to accounts list
            exit();
        } else {
            throw new Exception("Failed to create account.");
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Add New Account</h4>
                    <div>
                        <a href="dashboard.accounts.php" class="btn btn-secondary">
                            <i class="lni lni-arrow-left"></i> Back to Accounts
                        </a>
                    </div>
                </div>
                
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <form class="row g-4 p-3" action="" method="post">
                    <!-- Role and ID Section -->
                    <div class="col-12 mb-4">
                        <h5 class="text-primary border-bottom pb-2">Account Type</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Role *</label>
                                <select class="form-select form-select-lg" id="role_id" name="role_id" required>
                                    <option value="" disabled selected>Select Role</option>
                                    <option value="1">Admin</option>
                                    <option value="2">Staff</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">ID Number *</label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       id="identifier" 
                                       name="identifier"
                                       value="<?= htmlspecialchars($identifier) ?>" 
                                       required>
                                <div class="form-text">Format: 9 digits for Staff</div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information Section -->
                    <div class="col-12 mb-4">
                        <h5 class="text-primary border-bottom pb-2">Personal Information</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">First Name *</label>
                                <input type="text" class="form-control form-control-lg" name="firstname" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Middle Name</label>
                                <input type="text" class="form-control form-control-lg" name="middlename">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Last Name *</label>
                                <input type="text" class="form-control form-control-lg" name="lastname" required>
                            </div>
                        </div>
                    </div>

                    <!-- Department Section (for Staff) -->
                    <div class="col-12 mb-4" id="departmentDiv" style="display: none;">
                        <h5 class="text-primary border-bottom pb-2">Department Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Department *</label>
                                <select class="form-select form-select-lg" id="department" name="other">
                                    <option value="" disabled selected>Select Department</option>
                                    <?php
                                    $departments = $departmentObj->getAllDepartments();
                                    foreach ($departments as $dept) {
                                        echo "<option value='{$dept['id']}'>{$dept['department_name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Account Information Section -->
                    <div class="col-12 mb-4">
                        <h5 class="text-primary border-bottom pb-2">Account Information</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Email Address *</label>
                                <input type="email" class="form-control form-control-lg" name="email" required>
                                <div class="form-text">Use WMSU email: username@wmsu.edu.ph</div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Username *</label>
                                <input type="text" class="form-control form-control-lg" name="username" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Password *</label>
                                <input type="password" class="form-control form-control-lg" name="password" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-end mt-4">
                        <button type="reset" class="btn btn-secondary btn-lg px-4 me-2">Reset</button>
                        <button type="submit" class="btn btn-primary btn-lg px-4">Create Account</button>
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
    
    if (this.value === '2') {
        departmentDiv.style.display = 'block';
        departmentSelect.required = true;
    } else {
        departmentDiv.style.display = 'none';
        departmentSelect.required = false;
    }
});
</script>

<?php include('../includes/_footer.php'); ?>