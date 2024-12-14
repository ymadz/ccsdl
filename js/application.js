$(document).ready(function() {
    // Load subjects when year and semester are selected
    $('#yearLevel, #semester').on('change', function() {
        const yearLevel = $('#yearLevel').val();
        const semester = $('#semester').val();

        if (yearLevel && semester) {
            loadSubjects(yearLevel, semester);
        }
    });

    function loadSubjects(yearLevel, semester) {
        $('#loadingSubjects').show();
        
        $.ajax({
            url: 'handlers/get_prospectus_subjects.php',
            type: 'POST',
            data: {
                course: studentCourse,
                year_level: yearLevel,
                semester: semester
            },
            success: function(response) {
                $('#loadingSubjects').hide();
                if (response.subjects && response.subjects.length > 0) {
                    displaySubjectsTable(response.subjects);
                } else {
                    $('#subjectsContainer').html('<div class="alert alert-warning">No subjects found for the selected criteria.</div>');
                }
            },
            error: function() {
                $('#loadingSubjects').hide();
                $('#subjectsContainer').html('<div class="alert alert-danger">Error loading subjects.</div>');
            }
        });
    }

    function displaySubjectsTable(subjects) {
        let table = `
            <table class="table">
                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Descriptive Title</th>
                        <th>Units</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
        `;

        subjects.forEach(subject => {
            const totalUnits = parseFloat(subject.lec_units) + parseFloat(subject.lab_units);
            table += `
                <tr>
                    <td>${subject.subject_code}</td>
                    <td>${subject.descriptive_title}</td>
                    <td class="text-center">${totalUnits}</td>
                    <td>
                        <input type="number" 
                               class="form-control grade-input" 
                               name="grades[${subject.id}]"
                               min="1.00" 
                               max="5.00" 
                               step="0.25"
                               required>
                    </td>
                </tr>
            `;
        });

        table += '</tbody></table>';
        $('#subjectsContainer').html(table);
    }

    // Calculate GWA when grades change
    $(document).on('input', '.grade-input', calculateGWA);

    function calculateGWA() {
        let totalUnits = 0;
        let totalGradePoints = 0;
        let isValid = true;

        $('.grade-input').each(function() {
            const grade = parseFloat($(this).val());
            const units = parseFloat($(this).closest('tr').find('td:eq(2)').text());

            if (!isNaN(grade) && !isNaN(units)) {
                totalUnits += units;
                totalGradePoints += (grade * units);
            } else {
                isValid = false;
            }
        });

        if (isValid && totalUnits > 0) {
            const gwa = totalGradePoints / totalUnits;
            $('#totalUnits').text(totalUnits.toFixed(2));
            $('#gwa').text(gwa.toFixed(2));
            
            // Enable submit button if GWA is 1.75 or better
            $('#submitBtn').prop('disabled', gwa > 1.75);
        } else {
            $('#totalUnits').text('0.00');
            $('#gwa').text('0.00');
            $('#submitBtn').prop('disabled', true);
        }
    }
}); 