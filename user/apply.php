<?php
require_once "../includes/authentication.php";
check_user_login();

$page_title = "Dean's List Application";
include_once "includes/header.php";
?>

<link rel="stylesheet" href="../css/navbar.css">
<link rel="stylesheet" href="../css/application.css">

<body>
    <?php include_once "includes/navbar.php"; ?>

    <div class="container">
        <div class="application-form">
            <h2 class="mb-4">Dean's List Application</h2>
            <form id="deansListApplication" method="POST" action="submit_application.php">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="schoolYear" class="form-label">School Year</label>
                        <input type="text" 
                               class="form-control" 
                               id="schoolYear" 
                               name="school_year" 
                               required 
                               pattern="\d{4}-\d{4}"
                               placeholder="YYYY-YYYY"
                               title="Please enter in format: YYYY-YYYY (e.g., 2023-2024)">
                    </div>
                    <div class="col-md-4">
                        <label for="yearLevel" class="form-label">Year Level</label>
                        <select class="form-control" id="yearLevel" name="year_level" required>
                            <option value="">Select Year Level</option>
                            <option value="1">First Year</option>
                            <option value="2">Second Year</option>
                            <option value="3">Third Year</option>
                            <option value="4">Fourth Year</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="semester" class="form-label">Semester</label>
                        <select class="form-control" id="semester" name="semester" required>
                            <option value="">Select Semester</option>
                            <option value="First">First</option>
                            <option value="Second">Second</option>
                        </select>
                    </div>
                </div>

                <div id="subjectsContainer">
                    <!-- Subjects will be loaded here dynamically -->
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <h4>Total Units: <span id="totalUnits">0</span></h4>
                    </div>
                    <div class="col-md-6">
                        <h4>GWA: <span id="gwa">0.00</span></h4>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-submit mb-3" id="submitBtn" disabled>
                        Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/application.js"></script>
    <?php include_once "includes/footer.php"; ?>
</body>
</html> 