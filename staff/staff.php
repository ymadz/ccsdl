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

require_once '../classes/user.class.php'; // Include the User class
$userObj = new User(); // Instantiate the User class

// Fetch user details using the user ID stored in the session
$userDetails = $userObj->getUserDetails($account['user_id']);

// Check if user details are fetched successfully
if ($userDetails) {
    $username = isset($userDetails['username']) ? $userDetails['username'] : 'Guest'; // Replace 'username' with the actual column name in your DB
} else {
    // Log error message if user details are not fetched
    error_log("Failed to fetch user details for user ID: " . $account['user_id']);
    $username = "Unknown User";
}
$name = $userDetails['firstname'];

require_once '../classes/application.class.php';
$applicationObj = new Application();

// Get statistics
$stats = $applicationObj->getApplicationStats();
?>

<div class="wrapper">
    <?php require_once('includes/sidebar.php')?>
    <div class="main p-3">
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title">Dashboard</h4>
                <h1>Hello, <?= $name ?>!</h1>
                <p class="text-muted fs-5"><?= date('l, F j, Y') ?></p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Pending Applications</h5>
                        <h2 class="mb-0"><?= $stats['pending'] ?></h2>
                        <small>Awaiting Review</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Approved Applications</h5>
                        <h2 class="mb-0"><?= $stats['approved'] ?></h2>
                        <small>Dean's Listers</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h5 class="card-title">Rejected Applications</h5>
                        <h2 class="mb-0"><?= $stats['rejected'] ?></h2>
                        <small>Not Qualified</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Applications -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Recent Applications</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Status</th>
                                <th>Date Applied</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($applicationObj->getRecentApplications(5) as $app): ?>
                                <tr>
                                    <td><?= htmlspecialchars($app['student_name']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= 
                                            $app['status'] === 'Approved' ? 'success' : 
                                            ($app['status'] === 'Rejected' ? 'danger' : 'warning') 
                                        ?>">
                                            <?= htmlspecialchars($app['status']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($app['created_at'])) ?></td>
                                    <td>
                                        <a href="staff.applications.php" class="btn btn-sm btn-primary">View</a>
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

<?php require_once('includes/footer.php')?>