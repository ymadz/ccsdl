<?php
require_once "../includes/authentication.php";
require_once '../classes/application.class.php';
require_once '../classes/user.class.php';

$page_title = "Dean's List Leaderboard";
include_once "includes/header.php";
include_once "includes/navbar.php";

$applicationObj = new Application();
$userObj = new User();

// Fetch all approved applications with user details
$leaderboard = $applicationObj->getApprovedApplicationsByCourse();

// Sort by total rating (highest to lowest)
usort($leaderboard, function($a, $b) {
    return $b['total_rating'] <=> $a['total_rating'];
});

// For now, we'll use the same data for both current and previous year
$currentYearLeaderboard = $leaderboard;
$previousYearLeaderboard = $leaderboard;
?>

<!-- Hero Section -->
<div class="leaderboard-hero">
    <div class="container">
        <h1 class="text-center">🏆 Dean's List Leaderboard</h1>
        <p class="text-center lead">Celebrating Academic Excellence and Achievement</p>
    </div>
</div>

<div class="container my-5">
    <!-- Year Toggle -->
    <div class="year-toggle mb-4">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-toggle active" data-year="current">
                Current Year (<?= date('Y') ?>)
            </button>
            <button type="button" class="btn btn-toggle" data-year="previous">
                Previous Year (<?= date('Y')-1 ?>)
            </button>
        </div>
    </div>

    <!-- Top 3 Winners Section -->
    <div class="top-winners mb-5">
        <div class="row">
            <?php 
            $topThree = array_slice($currentYearLeaderboard, 0, 3);
            $positions = ['🥇 First Place', '🥈 Second Place', '🥉 Third Place'];
            foreach ($topThree as $index => $winner): 
            ?>
            <div class="col-md-4 mb-4">
                <div class="winner-card position-<?= $index + 1 ?>">
                    <div class="winner-medal"><?= $positions[$index] ?></div>
                    <div class="winner-details">
                        <h3><?= htmlspecialchars($winner['firstname'] . ' ' . $winner['lastname']) ?></h3>
                        <p class="course"><?= htmlspecialchars($winner['course_name']) ?></p>
                        <div class="rating">
                            <span class="number"><?= number_format($winner['total_rating'], 2) ?></span>
                            <span class="label">GPA</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Leaderboard Tables -->
    <div id="currentYearTable" class="leaderboard-table active">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">Current Year Rankings</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Student Name</th>
                                <th>Course</th>
                                <th>School Year</th>
                                <th>Semester</th>
                                <th>GPA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($currentYearLeaderboard as $index => $student): ?>
                            <tr>
                                <td>
                                    <span class="rank-badge rank-<?= $index + 1 ?>">
                                        <?= $index + 1 ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($student['lastname'] . ', ' . $student['firstname']) ?></td>
                                <td><?= htmlspecialchars($student['course_name']) ?></td>
                                <td><?= htmlspecialchars($student['school_year']) ?></td>
                                <td><?= htmlspecialchars($student['semester']) ?></td>
                                <td>
                                    <span class="gpa-badge">
                                        <?= number_format($student['total_rating'], 2) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="previousYearTable" class="leaderboard-table">
        <!-- Similar structure as current year table but with previous year data -->
    </div>
</div>

<style>
/* Leaderboard Hero Section */
.leaderboard-hero {
    background: linear-gradient(135deg, #004225 0%, #006838 100%);
    color: white;
    padding: 3rem 0;
    margin-bottom: 2rem;
}

.leaderboard-hero h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

/* Year Toggle */
.year-toggle {
    text-align: center;
}

.btn-toggle {
    background-color: #f8f9fa;
    border: 2px solid #004225;
    color: #004225;
    padding: 0.5rem 1.5rem;
    transition: all 0.3s ease;
}

.btn-toggle.active {
    background-color: #004225;
    color: white;
}

/* Winner Cards */
.winner-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.winner-card:hover {
    transform: translateY(-5px);
}

.position-1 {
    border-top: 5px solid gold;
}

.position-2 {
    border-top: 5px solid silver;
}

.position-3 {
    border-top: 5px solid #cd7f32;
}

.winner-medal {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.winner-details h3 {
    font-size: 1.3rem;
    margin-bottom: 0.5rem;
}

.rating {
    margin-top: 1rem;
}

.rating .number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #004225;
}

/* Table Styles */
.leaderboard-table {
    display: none;
}

.leaderboard-table.active {
    display: block;
}

.rank-badge {
    background: #f8f9fa;
    padding: 0.5rem 0.8rem;
    border-radius: 50%;
    font-weight: 600;
}

.rank-1, .rank-2, .rank-3 {
    color: white;
}

.rank-1 { background-color: gold; }
.rank-2 { background-color: silver; }
.rank-3 { background-color: #cd7f32; }

.gpa-badge {
    background: #e8f5e9;
    color: #004225;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-weight: 600;
}

/* Responsive Design */
@media (max-width: 768px) {
    .leaderboard-hero h1 {
        font-size: 2rem;
    }
    
    .winner-card {
        margin-bottom: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Year toggle functionality
    const toggleButtons = document.querySelectorAll('.btn-toggle');
    const tables = document.querySelectorAll('.leaderboard-table');

    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const year = this.dataset.year;
            
            // Update buttons
            toggleButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Update tables
            tables.forEach(table => {
                table.classList.remove('active');
                if (table.id === `${year}YearTable`) {
                    table.classList.add('active');
                }
            });
        });
    });
});
</script>

<?php include_once "includes/footer.php"; ?>
