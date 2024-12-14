<?php
$page_title = "CCS Ranking System - Dashboard";
session_start();

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

$account = $_SESSION['account'];
require_once 'includes/header.php';
require_once '../classes/user.class.php';
require_once '../classes/application.class.php';

$userObj = new User();
$applicationObj = new Application();

$userDetails = $userObj->getUserDetails($account['user_id']);
$latestApplication = $applicationObj->getLatestApplicationByUser($account['user_id']);
$name = $userDetails['firstname'];
?>

<!-- Include Navbar -->
<?php include_once "includes/navbar.php"; ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1>Welcome to CCS, <?= htmlspecialchars($name) ?>!</h1>
                    <p class="lead">Empowering Excellence in Computing Education</p>
                    <?php if (!$latestApplication || $latestApplication['status'] === 'Rejected'): ?>
                        <a href="apply.php" class="btn btn-primary btn-lg">
                            Apply for Dean's List <i class="lni lni-arrow-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="../img/AUTHENTICATION.jpg" alt="CCS Hero Image" class="hero-image">
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="lni lni-graduation"></i>
                    </div>
                    <h3>Your Program</h3>
                    <p><?= htmlspecialchars($userDetails['course_name'] ?? 'Not Available') ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="lni lni-star"></i>
                    </div>
                    <h3>Current GPA</h3>
                    <p><?= $latestApplication ? number_format($latestApplication['total_rating'], 2) : 'Not Available' ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="lni lni-certificate"></i>
                    </div>
                    <h3>Academic Status</h3>
                    <p><?= $latestApplication ? htmlspecialchars($latestApplication['status']) : 'Not Applied' ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <h2 class="section-title">Why Choose CCS?</h2>
        <div class="row">
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="lni lni-laptop-phone"></i>
                    </div>
                    <h3>Modern Curriculum</h3>
                    <p>State-of-the-art programs designed to meet industry demands</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="lni lni-users"></i>
                    </div>
                    <h3>Expert Faculty</h3>
                    <p>Learn from experienced professionals and industry experts</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="lni lni-network"></i>
                    </div>
                    <h3>Industry Links</h3>
                    <p>Strong connections with leading tech companies</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="lni lni-invention"></i>
                    </div>
                    <h3>Innovation Hub</h3>
                    <p>Access to cutting-edge technology and research facilities</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Application Status Section (if exists) -->
<?php if ($latestApplication): ?>
<section class="application-status-section">
    <div class="container">
        <div class="card status-card">
            <div class="card-header">
                <h2>Your Dean's List Application Status</h2>
            </div>
            <div class="card-body">
                <!-- Existing application status content -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="status-item">
                            <i class="lni lni-calendar"></i>
                            <h4>School Year</h4>
                            <p><?= htmlspecialchars($latestApplication['school_year']) ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="status-item">
                            <i class="lni lni-timer"></i>
                            <h4>Semester</h4>
                            <p><?= htmlspecialchars($latestApplication['semester']) ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="status-item">
                            <i class="lni lni-stats-up"></i>
                            <h4>GPA</h4>
                            <p><?= number_format($latestApplication['total_rating'], 2) ?></p>
                        </div>
                    </div>
                </div>
                
                <?php if ($latestApplication['status'] === 'Rejected'): ?>
                    <div class="alert alert-danger mt-4">
                        <i class="lni lni-warning"></i>
                        <strong>Reason for Rejection:</strong> 
                        <?= htmlspecialchars($latestApplication['rejection_reason']) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<style>
/* Hero Section */
.hero-section {
    position: relative;
    background-color: #004225;
    padding: 100px 0;
    color: white;
    overflow: hidden;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0,66,37,0.95) 0%, rgba(0,104,56,0.85) 100%);
}

.hero-content {
    position: relative;
    z-index: 1;
}

.hero-content h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
}

.hero-image {
    width: 100%;
    max-width: 500px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

/* Stats Section */
.stats-section {
    padding: 80px 0;
    background-color: #f8f9fa;
}

.stat-card {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    text-align: center;
    transition: transform 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    font-size: 2.5rem;
    color: #004225;
    margin-bottom: 1rem;
}

/* Features Section */
.features-section {
    padding: 80px 0;
}

.section-title {
    text-align: center;
    margin-bottom: 3rem;
    color: #004225;
}

.feature-card {
    text-align: center;
    padding: 2rem;
    margin-bottom: 2rem;
    border-radius: 15px;
    background: white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
}

.feature-icon {
    font-size: 2rem;
    color: #004225;
    margin-bottom: 1rem;
}

/* Application Status Section */
.application-status-section {
    padding: 80px 0;
    background-color: #f8f9fa;
}

.status-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.status-card .card-header {
    background-color: #004225;
    color: white;
    border-radius: 15px 15px 0 0;
    padding: 1.5rem;
}

.status-item {
    text-align: center;
    padding: 1.5rem;
}

.status-item i {
    font-size: 2rem;
    color: #004225;
    margin-bottom: 1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-content h1 {
        font-size: 2rem;
    }
    
    .hero-image {
        margin-top: 2rem;
    }
    
    .stat-card {
        margin-bottom: 1rem;
    }
}
</style>

<?php include('includes/footer.php') ?>
