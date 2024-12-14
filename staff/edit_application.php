<?php
$page_title = "Edit Application";
session_start();

if (!isset($_SESSION['account'])) {
    header('location: ../account/loginwcss.php?error=nosession');
    exit;
}

include('includes/header.php');
include('../classes/application.class.php');

if (!isset($_GET['id'])) {
    header('location: staff.applications.php');
    exit;
}

$applicationObj = new Application();
$application = $applicationObj->getApplicationDetails($_GET['id']);

if (!$application) {
    header('location: staff.applications.php');
    exit;
}
?>

<div class="wrapper">
    <div class="main">
        <div class="container-fluid">
            <div class="mb-3">
                <h4>Edit Application</h4>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <form id="editApplicationForm">
                        <input type="hidden" name="application_id" value="<?= $_GET['id'] ?>">
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Student Name</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($application['student_name']) ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">School Year</label>
                                <input type="text" class="form-control" name="school_year" value="<?= htmlspecialchars($application['school_year']) ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Semester</label>
                                <select class="form-select" name="semester" required>
                                    <option value="First" <?= $application['semester'] == 'First' ? 'selected' : '' ?>>First</option>
                                    <option value="Second" <?= $application['semester'] == 'Second' ? 'selected' : '' ?>>Second</option>
                                </select>
                            </div>
                        </div>

                        <div class="card mt-4">
                            <div class="card-header">
                                <h6 class="mb-0">Subjects</h6>
                            </div>
                            <div class="card-body">
                                <div id="subjectsContainer">
                                    <?php foreach ($application['subjects'] as $subject): ?>
                                        <div class="subject-entry border rounded p-3 mb-3">
                                            <input type="hidden" name="subject_ids[]" value="<?= $subject['id'] ?>">
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label class="form-label">Subject Code</label>
                                                    <input type="text" class="form-control" name="subject_codes[]" 
                                                           value="<?= htmlspecialchars($subject['subject_code']) ?>" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Subject Name</label>
                                                    <input type="text" class="form-control" name="subject_names[]" 
                                                           value="<?= htmlspecialchars($subject['subject_name']) ?>" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Rating</label>
                                                    <input type="number" class="form-control" name="ratings[]" 
                                                           value="<?= $subject['rating'] ?>" step="0.01" min="1" max="5" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Type</label>
                                                    <select class="form-select" name="is_laboratory[]">
                                                        <option value="0" <?= !$subject['is_laboratory'] ? 'selected' : '' ?>>Lecture</option>
                                                        <option value="1" <?= $subject['is_laboratory'] ? 'selected' : '' ?>>Laboratory</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <a href="staff.applications.php" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('editApplicationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        application_id: formData.get('application_id'),
        school_year: formData.get('school_year'),
        semester: formData.get('semester'),
        subjects: []
    };
    
    const subjectIds = formData.getAll('subject_ids[]');
    const subjectCodes = formData.getAll('subject_codes[]');
    const subjectNames = formData.getAll('subject_names[]');
    const ratings = formData.getAll('ratings[]');
    const isLaboratory = formData.getAll('is_laboratory[]');
    
    for (let i = 0; i < subjectIds.length; i++) {
        data.subjects.push({
            id: subjectIds[i],
            subject_code: subjectCodes[i],
            subject_name: subjectNames[i],
            rating: ratings[i],
            is_laboratory: isLaboratory[i] === '1'
        });
    }
    
    fetch('../admin/update_application.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Application updated successfully');
            window.location.href = 'staff.applications.php';
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the application');
    });
});
</script>

<?php include('includes/footer.php'); ?> 