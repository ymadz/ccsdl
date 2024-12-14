<?php
$page_title = "CCS Ranking System - Prospectus";
session_start();

// Check if session exists
if (!isset($_SESSION['account'])) {
    header('location: ../account/loginwcss.php?error=unauthorized');
    exit;
}

require_once('../classes/database.class.php');
$db = new Database();

require_once('includes/header.php');
?>
<!-- Add jQuery UI CSS and JS -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<div class="wrapper">
    <?php require_once('includes/sidebar.php') ?>
    <div class="main p-3">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title pb-2">Course Prospectus</h4>
                <hr>
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#bscs" type="button">
                            <i class="lni lni-code-alt"></i> BSCS
                        </button>
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#bsit" type="button">
                            <i class="lni lni-network"></i> BSIT
                        </button>
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#act" type="button">
                            <i class="lni lni-laptop-phone"></i> ACT
                        </button>
                    </div>
                </nav>

                <div class="search-filter-container mt-3">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <div class="mb-2">
                                <label for="searchInput" class="filter-label">🔍 Search Subject</label>
                                <input type="text" id="searchInput" class="form-control" placeholder="Enter subject code or name...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-2">
                                <label for="schoolYearFilter" class="filter-label">📅 School Year</label>
                                <select id="schoolYearFilter" class="form-select">
                                    <option value="">All Years</option>
                                    <option value="2023-2024">2023-2024</option>
                                    <option value="2024-2025">2024-2025</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-2">
                                <label for="yearFilter" class="filter-label">📚 Year Level</label>
                                <select id="yearFilter" class="form-select">
                                    <option value="">All Years</option>
                                    <option value="1">1st Year</option>
                                    <option value="2">2nd Year</option>
                                    <option value="3">3rd Year</option>
                                    <option value="4">4th Year</option>
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
                                    <option value="Summer">Summer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                                <i class="lni lni-plus"></i> Add Subject
                            </button>
                        </div>
                    </div>
                </div>

                <div class="tab-content mt-3">
                    <!-- BSCS Tab -->
                    <div class="tab-pane fade show active" id="bscs">
                        <!-- Year Level Tabs -->
                        <nav>
                            <div class="nav nav-tabs" id="bscs-year-tab" role="tablist">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#bscs-1" type="button">1st Year</button>
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#bscs-2" type="button">2nd Year</button>
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#bscs-3" type="button">3rd Year</button>
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#bscs-4" type="button">4th Year</button>
                            </div>
                        </nav>

                        <!-- Year Level Content -->
                        <div class="tab-content mt-3">
                            <?php
                            for ($year = 1; $year <= 4; $year++) {
                                echo "<div class='tab-pane fade " . ($year == 1 ? "show active" : "") . "' id='bscs-$year'>";
                                echo "<div class='table-responsive'>";
                                echo "<table class='table table-hover'>";
                                // Table Header
                                echo "<thead>
                                        <tr>
                                            <th rowspan='2'><i class='lni lni-code'></i> Code</th>
                                            <th rowspan='2'><i class='lni lni-book'></i> Descriptive Title</th>
                                            <th rowspan='2'><i class='lni lni-link'></i> Prerequisite</th>
                                            <th colspan='3' class='text-center'><i class='lni lni-calculator'></i> Units</th>
                                            <th rowspan='2'><i class='lni lni-cog'></i> Actions</th>
                                        </tr>
                                        <tr>
                                            <th class='text-center'>Lec</th>
                                            <th class='text-center'>Lab</th>
                                            <th class='text-center'>Total</th>
                                        </tr>
                                    </thead>";
                                echo "<tbody>";

                                // Fetch subjects for this year
                                $query = "SELECT * FROM bscs_prospectus WHERE year_level = ? ORDER BY semester, subject_code";
                                $result = $db->fetchAll($query, [$year]);
                                
                                $current_semester = null;
                                foreach ($result as $row) {
                                    // Add semester header if it's a new semester
                                    if ($current_semester !== $row['semester']) {
                                        echo "<tr class='table-secondary'>";
                                        echo "<th colspan='7'>{$row['semester']} Semester</th>";
                                        echo "</tr>";
                                        $current_semester = $row['semester'];
                                    }
                                    
                                    // Display subject row
                                    echo "<tr>";
                                    echo "<td>{$row['subject_code']}</td>";
                                    echo "<td>{$row['descriptive_title']}</td>";
                                    echo "<td>" . ($row['prerequisite'] ? $row['prerequisite'] : 'None') . "</td>";
                                    echo "<td class='text-center'>{$row['lec_units']}</td>";
                                    echo "<td class='text-center'>{$row['lab_units']}</td>";
                                    echo "<td class='text-center'>" . ($row['lec_units'] + $row['lab_units']) . "</td>";
                                    echo "<td>
                                            <div class='d-flex gap-1'>
                                                <button class='btn btn-sm btn-primary edit-subject' data-id='{$row['id']}'>
                                                    <i class='lni lni-pencil'></i>
                                                </button>
                                                <button class='btn btn-sm btn-danger delete-subject' data-id='{$row['id']}'>
                                                    <i class='lni lni-trash-can'></i>
                                                </button>
                                            </div>
                                         </td>";
                                    echo "</tr>";
                                }
                                echo "</tbody></table></div></div>";
                            }
                            ?>
                        </div>
                    </div>

                    <!-- BSIT Tab -->
                    <div class="tab-pane fade" id="bsit">
                        <!-- Year Level Tabs -->
                        <nav>
                            <div class="nav nav-tabs" id="bsit-year-tab" role="tablist">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#bsit-1" type="button">1st Year</button>
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#bsit-2" type="button">2nd Year</button>
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#bsit-3" type="button">3rd Year</button>
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#bsit-4" type="button">4th Year</button>
                            </div>
                        </nav>

                        <!-- Year Level Content -->
                        <div class="tab-content mt-3">
                            <?php
                            for ($year = 1; $year <= 4; $year++) {
                                echo "<div class='tab-pane fade " . ($year == 1 ? "show active" : "") . "' id='bsit-$year'>";
                                echo "<div class='table-responsive'>";
                                echo "<table class='table table-hover'>";
                                // Table Header
                                echo "<thead>
                                        <tr>
                                            <th rowspan='2'><i class='lni lni-code'></i> Code</th>
                                            <th rowspan='2'><i class='lni lni-book'></i> Descriptive Title</th>
                                            <th rowspan='2'><i class='lni lni-link'></i> Prerequisite</th>
                                            <th colspan='3' class='text-center'><i class='lni lni-calculator'></i> Units</th>
                                            <th rowspan='2'><i class='lni lni-cog'></i> Actions</th>
                                        </tr>
                                        <tr>
                                            <th class='text-center'>Lec</th>
                                            <th class='text-center'>Lab</th>
                                            <th class='text-center'>Total</th>
                                        </tr>
                                    </thead>";
                                echo "<tbody>";

                                // Fetch subjects for this year
                                $query = "SELECT * FROM bsit_prospectus WHERE year_level = ? ORDER BY semester, subject_code";
                                $result = $db->fetchAll($query, [$year]);
                                
                                $current_semester = null;
                                foreach ($result as $row) {
                                    // Add semester header if it's a new semester
                                    if ($current_semester !== $row['semester']) {
                                        echo "<tr class='table-secondary'>";
                                        echo "<th colspan='7'>{$row['semester']} Semester</th>";
                                        echo "</tr>";
                                        $current_semester = $row['semester'];
                                    }
                                    
                                    // Display subject row
                                    echo "<tr>";
                                    echo "<td>{$row['subject_code']}</td>";
                                    echo "<td>{$row['descriptive_title']}</td>";
                                    echo "<td>" . ($row['prerequisite'] ? $row['prerequisite'] : 'None') . "</td>";
                                    echo "<td class='text-center'>{$row['lec_units']}</td>";
                                    echo "<td class='text-center'>{$row['lab_units']}</td>";
                                    echo "<td class='text-center'>{$row['total_units']}</td>";
                                    echo "<td>
                                            <div class='d-flex gap-1'>
                                                <button class='btn btn-sm btn-primary edit-subject' data-id='{$row['id']}'>
                                                    <i class='lni lni-pencil'></i>
                                                </button>
                                                <button class='btn btn-sm btn-danger delete-subject' data-id='{$row['id']}'>
                                                    <i class='lni lni-trash-can'></i>
                                                </button>
                                            </div>
                                         </td>";
                                    echo "</tr>";
                                }
                                echo "</tbody></table></div></div>";
                            }
                            ?>
                        </div>
                    </div>

                    <!-- ACT Tab -->
                    <div class="tab-pane fade" id="act">
                        <!-- ACT Course Tabs -->
                        <nav>
                            <div class="nav nav-tabs mb-3" id="act-course-tab" role="tablist">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#act-ad" type="button">
                                    <i class="lni lni-paint-roller"></i> ACT-AD
                                </button>
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#act-nw" type="button">
                                    <i class="lni lni-network"></i> ACT-NW
                                </button>
                            </div>
                        </nav>

                        <div class="tab-content">
                            <!-- ACT-AD Tab -->
                            <div class="tab-pane fade show active" id="act-ad">
                                <nav>
                                    <div class="nav nav-tabs" id="act-ad-year-tab" role="tablist">
                                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#act-ad-1" type="button">1st Year</button>
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#act-ad-2" type="button">2nd Year</button>
                                    </div>
                                </nav>

                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th rowspan="2"><i class="lni lni-code"></i> Code</th>
                                                <th rowspan="2"><i class="lni lni-book"></i> Descriptive Title</th>
                                                <th rowspan="2"><i class="lni lni-link"></i> Prerequisite</th>
                                                <th colspan="3" class="text-center"><i class="lni lni-calculator"></i> Units</th>
                                                <th rowspan="2"><i class="lni lni-cog"></i> Actions</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Lec</th>
                                                <th class="text-center">Lab</th>
                                                <th class="text-center">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $current_year = null;
                                            $current_semester = null;

                                            $query = "SELECT * FROM act_ad_prospectus WHERE year_level = ? ORDER BY semester, subject_code";
                                            
                                            // Loop through each year level (only 2 years for ACT)
                                            for ($year = 1; $year <= 2; $year++) {
                                                $result = $db->fetchAll($query, [$year]);
                                                
                                                // Add year level header
                                                echo "<tr class='table-primary'>";
                                                echo "<th colspan='7' class='text-center'>Year Level {$year}</th>";
                                                echo "</tr>";
                                                
                                                $current_semester = null;
                                                foreach ($result as $row) {
                                                    // Add semester header if it's a new semester
                                                    if ($current_semester !== $row['semester']) {
                                                        echo "<tr class='table-secondary'>";
                                                        echo "<th colspan='7'>{$row['semester']} Semester</th>";
                                                        echo "</tr>";
                                                        $current_semester = $row['semester'];
                                                    }
                                                    
                                                    // Display subject row
                                                    echo "<tr>";
                                                    echo "<td>{$row['subject_code']}</td>";
                                                    echo "<td>{$row['descriptive_title']}</td>";
                                                    echo "<td>" . ($row['prerequisite'] ? $row['prerequisite'] : 'None') . "</td>";
                                                    echo "<td class='text-center'>{$row['lec_units']}</td>";
                                                    echo "<td class='text-center'>{$row['lab_units']}</td>";
                                                    echo "<td class='text-center'>{$row['total_units']}</td>";
                                                    echo "<td>
                                                            <div class='d-flex gap-1'>
                                                                <button class='btn btn-sm btn-primary edit-subject' data-id='{$row['id']}'>
                                                                    <i class='lni lni-pencil'></i>
                                                                </button>
                                                                <button class='btn btn-sm btn-danger delete-subject' data-id='{$row['id']}'>
                                                                    <i class='lni lni-trash-can'></i>
                                                                </button>
                                                            </div>
                                                         </td>";
                                                    echo "</tr>";
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- ACT-NW Tab -->
                            <div class="tab-pane fade" id="act-nw">
                                <nav>
                                    <div class="nav nav-tabs" id="act-nw-year-tab" role="tablist">
                                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#act-nw-1" type="button">1st Year</button>
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#act-nw-2" type="button">2nd Year</button>
                                    </div>
                                </nav>

                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th rowspan="2"><i class="lni lni-code"></i> Code</th>
                                                <th rowspan="2"><i class="lni lni-book"></i> Descriptive Title</th>
                                                <th rowspan="2"><i class="lni lni-link"></i> Prerequisite</th>
                                                <th colspan="3" class="text-center"><i class="lni lni-calculator"></i> Units</th>
                                                <th rowspan="2"><i class="lni lni-cog"></i> Actions</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Lec</th>
                                                <th class="text-center">Lab</th>
                                                <th class="text-center">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $current_year = null;
                                            $current_semester = null;

                                            $query = "SELECT * FROM act_nw_prospectus WHERE year_level = ? ORDER BY semester, subject_code";
                                            
                                            // Loop through each year level (only 2 years for ACT)
                                            for ($year = 1; $year <= 2; $year++) {
                                                $result = $db->fetchAll($query, [$year]);
                                                
                                                // Add year level header
                                                echo "<tr class='table-primary'>";
                                                echo "<th colspan='7' class='text-center'>Year Level {$year}</th>";
                                                echo "</tr>";
                                                
                                                $current_semester = null;
                                                foreach ($result as $row) {
                                                    // Add semester header if it's a new semester
                                                    if ($current_semester !== $row['semester']) {
                                                        echo "<tr class='table-secondary'>";
                                                        echo "<th colspan='7'>{$row['semester']} Semester</th>";
                                                        echo "</tr>";
                                                        $current_semester = $row['semester'];
                                                    }
                                                    
                                                    // Display subject row
                                                    echo "<tr>";
                                                    echo "<td>{$row['subject_code']}</td>";
                                                    echo "<td>{$row['descriptive_title']}</td>";
                                                    echo "<td>" . ($row['prerequisite'] ? $row['prerequisite'] : 'None') . "</td>";
                                                    echo "<td class='text-center'>{$row['lec_units']}</td>";
                                                    echo "<td class='text-center'>{$row['lab_units']}</td>";
                                                    echo "<td class='text-center'>{$row['total_units']}</td>";
                                                    echo "<td>
                                                            <div class='d-flex gap-1'>
                                                                <button class='btn btn-sm btn-primary edit-subject' data-id='{$row['id']}'>
                                                                    <i class='lni lni-pencil'></i>
                                                                </button>
                                                                <button class='btn btn-sm btn-danger delete-subject' data-id='{$row['id']}'>
                                                                    <i class='lni lni-trash-can'></i>
                                                                </button>
                                                            </div>
                                                         </td>";
                                                    echo "</tr>";
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Subject Modal -->
        <div class="modal fade" id="editSubjectModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Subject</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editSubjectForm">
                        <div class="modal-body">
                            <input type="hidden" id="editSubjectId" name="id">
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editSubjectCode" class="form-label">Subject Code</label>
                                    <input type="text" class="form-control autocomplete-subject" id="editSubjectCode" 
                                           name="subject_code" required placeholder="Enter subject code">
                                </div>
                                <div class="col-md-6">
                                    <label for="editDescriptiveTitle" class="form-label">Descriptive Title</label>
                                    <input type="text" class="form-control autocomplete-title" id="editDescriptiveTitle" 
                                           name="descriptive_title" required placeholder="Enter descriptive title">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="editPrerequisite" class="form-label">Prerequisite</label>
                                    <input type="text" class="form-control autocomplete-prerequisite" id="editPrerequisite" 
                                           name="prerequisite" placeholder="Search for prerequisite or leave as 'None'">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="editLecUnits" class="form-label">Lecture Units</label>
                                    <input type="number" class="form-control numeric-input" id="editLecUnits" 
                                           name="lec_units" step="0.1" min="0" max="6" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="editLabUnits" class="form-label">Laboratory Units</label>
                                    <input type="number" class="form-control numeric-input" id="editLabUnits" 
                                           name="lab_units" step="0.1" min="0" max="6" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="editTotalUnits" class="form-label">Total Units</label>
                                    <input type="number" class="form-control" id="editTotalUnits" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label d-block">Year Level</label>
                                    <div class="btn-group year-level-group" role="group">
                                        <input type="radio" class="btn-check" name="year_level" id="year1" value="1" required>
                                        <label class="btn btn-outline-primary" for="year1">1st Year</label>

                                        <input type="radio" class="btn-check" name="year_level" id="year2" value="2">
                                        <label class="btn btn-outline-primary" for="year2">2nd Year</label>

                                        <input type="radio" class="btn-check" name="year_level" id="year3" value="3">
                                        <label class="btn btn-outline-primary" for="year3">3rd Year</label>

                                        <input type="radio" class="btn-check" name="year_level" id="year4" value="4">
                                        <label class="btn btn-outline-primary" for="year4">4th Year</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label d-block">Semester</label>
                                    <div class="btn-group semester-group" role="group">
                                        <input type="radio" class="btn-check" name="semester" id="first" value="First" required>
                                        <label class="btn btn-outline-primary" for="first">First</label>

                                        <input type="radio" class="btn-check" name="semester" id="second" value="Second">
                                        <label class="btn btn-outline-primary" for="second">Second</label>

                                        <input type="radio" class="btn-check" name="semester" id="summer" value="Summer">
                                        <label class="btn btn-outline-primary" for="summer">Summer</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addSubjectForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Course</label>
                        <select name="course" class="form-select" required>
                            <option value="BSCS">BSCS</option>
                            <option value="BSIT">BSIT</option>
                            <option value="ACT-AD">ACT-AD</option>
                            <option value="ACT-NW">ACT-NW</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">School Year</label>
                                <input type="text" name="school_year" class="form-control" required 
                                       pattern="\d{4}-\d{4}" placeholder="YYYY-YYYY">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Year Level</label>
                                <select name="year_level" class="form-select" required>
                                    <option value="">Select Year</option>
                                    <option value="1">1st Year</option>
                                    <option value="2">2nd Year</option>
                                    <option value="3">3rd Year</option>
                                    <option value="4">4th Year</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Semester</label>
                                <select name="semester" class="form-select" required>
                                    <option value="">Select Semester</option>
                                    <option value="First">First</option>
                                    <option value="Second">Second</option>
                                    <option value="Summer">Summer</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject Code</label>
                        <input type="text" name="subject_code" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descriptive Title</label>
                        <input type="text" name="descriptive_title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prerequisite</label>
                        <input type="text" name="prerequisite" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Lec Units</label>
                                <input type="number" name="lec_units" id="lecUnits" class="form-control" step="0.1" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Lab Units</label>
                                <input type="number" name="lab_units" id="labUnits" class="form-control" step="0.1" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Total Units</label>
                                <input type="number" name="total_units" id="totalUnits" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once('includes/footer.php') ?>

<!-- Essential Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- Custom Scripts -->
<script src="assets/js/prospectus.js"></script>

</body>
</html>
