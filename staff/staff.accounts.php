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
?>

    <div class="wrapper">
        <?php include('includes/sidebar.php') ?>
        <div class="main p-3">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title pb-2 mb-2">Account Management</h4>
                    <hr>
                    <div class="table-wrapper table-responsive">
                        <table id="accounts-table" class="table table-bordered" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Identifier</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th>Date Registered</th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php if (!empty($accounts) && is_array($accounts)): ?>
                                <?php foreach ($accounts as $account): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars(isset($account['identifier']) ? $account['identifier'] : 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($account['firstname'] . ' ' . (isset($account['middlename']) ? $account['middlename'] : '') . ' ' . $account['lastname']); ?></td>
                                        <td><?php echo htmlspecialchars(isset($account['email']) ? $account['email'] : 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars(isset($account['username']) ? $account['username'] : 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars(date('Y-m-d', strtotime(isset($account['date_registered']) ? $account['date_registered'] : ''))); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">No accounts found.</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include('includes/footer.php') ?>

<style>
    /* Card Styling */
    .card {
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
    }

    .card-body {
        background-image: url('../img/AUTHENTICATION.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        position: relative;
        border-radius: 12px;
        padding: 25px;
    }

    .card-body::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.95);
        border-radius: 12px;
        z-index: 0;
    }

    .card-title {
        position: relative;
        z-index: 1;
        color: #004225;
        font-weight: bold;
        font-size: 1.8rem;
        text-align: center;
        margin-bottom: 1.5rem;
    }

    /* Table Styling */
    .table-wrapper {
        position: relative;
        z-index: 1;
    }

    .table {
        background-color: transparent;
        margin-bottom: 0;
    }

    .table thead th {
        background-color: rgba(0, 66, 37, 0.1);
        color: #004225;
        font-weight: 600;
        border-bottom: 2px solid #004225;
        padding: 12px;
        text-align: center;
    }

    .table tbody tr {
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background-color: rgba(0, 66, 37, 0.05);
    }

    .table td {
        vertical-align: middle;
        padding: 12px;
        text-align: center;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .card-body {
            padding: 15px;
        }

        .table thead th {
            font-size: 0.9rem;
        }

        .table td {
            font-size: 0.9rem;
        }
    }

    /* Custom Scrollbar */
    .table-responsive::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #004225;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #003319;
    }

    /* Alternating Row Colors */
    .table tbody tr:nth-child(even) {
        background-color: rgba(0, 66, 37, 0.02);
    }

    /* Header Styling */
    hr {
        position: relative;
        z-index: 1;
        border-color: #004225;
        opacity: 0.2;
        margin: 1.5rem 0;
    }
</style>