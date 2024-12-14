$(document).ready(function() {
    console.log('Prospectus JS loaded'); // Debug log

    // Convert subject code to uppercase while typing
    $('#editSubjectCode').on('input', function() {
        $(this).val($(this).val().toUpperCase());
    });

    // Calculate total units function
    function calculateTotalUnits() {
        const lec = parseFloat($('#editLecUnits').val()) || 0;
        const lab = parseFloat($('#editLabUnits').val()) || 0;
        $('#editTotalUnits').val((lec + lab).toFixed(1));
    }

    // Unit calculation listeners
    $('#editLecUnits, #editLabUnits').on('input', calculateTotalUnits);

    // Edit button click handler with proper data fetching
    $(document).on('click', '.edit-subject', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const courseTab = $(this).closest('.tab-pane').attr('id');
        
        // Show loading state in modal
        $('#editSubjectModal').modal('show');
        $('#editSubjectForm').hide();
        $('.modal-body').append('<div class="text-center" id="loadingSpinner"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        
        $.ajax({
            url: 'handlers/get_subject.php',
            type: 'POST',
            data: { 
                id: id,
                course: courseTab
            },
            dataType: 'json',
            success: function(response) {
                $('#loadingSpinner').remove();
                $('#editSubjectForm').show();
                
                if (response.status === 'success' && response.data) {
                    const data = response.data;
                    
                    // Populate form fields
                    $('#editSubjectId').val(data.id);
                    $('#editSubjectCode').val(data.subject_code.toUpperCase());
                    $('#editDescriptiveTitle').val(data.descriptive_title);
                    $('#editPrerequisite').val(data.prerequisite || 'None');
                    $('#editLecUnits').val(parseFloat(data.lec_units).toFixed(1));
                    $('#editLabUnits').val(parseFloat(data.lab_units).toFixed(1));
                    
                    // Set year level
                    $(`input[name="year_level"][value="${data.year_level}"]`).prop('checked', true);
                    
                    // Set semester
                    $(`input[name="semester"][value="${data.semester}"]`).prop('checked', true);
                    
                    // Calculate and display total units
                    calculateTotalUnits();
                    
                    // Store original values for comparison
                    $('#editSubjectForm').data('original-values', {
                        subject_code: data.subject_code.toUpperCase(),
                        descriptive_title: data.descriptive_title,
                        prerequisite: data.prerequisite || 'None',
                        lec_units: parseFloat(data.lec_units).toFixed(1),
                        lab_units: parseFloat(data.lab_units).toFixed(1),
                        year_level: data.year_level,
                        semester: data.semester
                    });
                }
            },
            error: function() {
                $('#loadingSpinner').remove();
                $('#editSubjectForm').show();
            }
        });
    });

    // Form submission handler
    $('#editSubjectForm').on('submit', function(e) {
        e.preventDefault();
        
        // Get current values
        const formData = {
            id: $('#editSubjectId').val(),
            course: $('.tab-pane.active').attr('id'), // Get active tab/course
            subject_code: $('#editSubjectCode').val().toUpperCase(),
            descriptive_title: $('#editDescriptiveTitle').val(),
            prerequisite: $('#editPrerequisite').val(),
            lec_units: $('#editLecUnits').val(),
            lab_units: $('#editLabUnits').val(),
            year_level: $('input[name="year_level"]:checked').val(),
            semester: $('input[name="semester"]:checked').val()
        };
        
        // Submit form with loading state
        Swal.fire({
            title: 'Saving changes...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Submit the update
        $.ajax({
            url: 'handlers/update_subject.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Subject updated successfully',
                        confirmButtonColor: '#004225'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message || 'Failed to update subject'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to update subject. Please try again.'
                });
            }
        });
    });

    // Delete button click handler
    $(document).on('click', '.delete-subject', function() {
        const id = $(this).data('id');
        const courseTab = $(this).closest('.tab-pane').attr('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with deletion
                $.ajax({
                    url: 'handlers/delete_subject.php',
                    type: 'POST',
                    data: { 
                        id: id,
                        course: courseTab
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire(
                                'Deleted!',
                                'Subject has been deleted.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message || 'Failed to delete subject',
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'Something went wrong while deleting the subject.',
                            'error'
                        );
                    }
                });
            }
        });
    });

    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Search functionality (keep this part unchanged)
    $('#searchInput').on('input', function() {
        const searchText = $(this).val().toLowerCase();
        const activeTab = $('.tab-pane.active');
        
        // Search within the active tab only
        activeTab.find('tbody tr').each(function() {
            const row = $(this);
            if (row.hasClass('table-primary') || row.hasClass('table-secondary')) {
                return true;
            }
            
            const subjectCode = row.find('td:eq(0)').text().toLowerCase();
            const description = row.find('td:eq(1)').text().toLowerCase();
            
            if (subjectCode.includes(searchText) || description.includes(searchText)) {
                row.show();
                row.prevAll('tr.table-primary:first, tr.table-secondary:first').show();
            } else {
                row.hide();
            }
        });
        
        if (searchText === '') {
            activeTab.find('tbody tr').show();
        }
    });

    // Combined filter function for year and semester
    function applyFilters() {
        const selectedYear = $('#yearFilter').val();
        const selectedSemester = $('#semesterFilter').val();
        const activeTab = $('.tab-pane.active');
        
        console.log('Filters:', {
            year: selectedYear,
            semester: selectedSemester
        });

        // Show all rows if no filters selected
        if (selectedYear === '' && selectedSemester === '') {
            activeTab.find('tbody tr').show();
            return;
        }

        // First hide all rows
        activeTab.find('tbody tr').hide();

        // Process each row
        activeTab.find('tbody tr').each(function() {
            const row = $(this);
            const yearHeader = row.prevAll('tr.table-primary:first');
            const semesterHeader = row.prevAll('tr.table-secondary:first');
            
            let showRow = true;

            // Check year level if selected
            if (selectedYear !== '') {
                if (!yearHeader.text().includes('Year Level ' + selectedYear)) {
                    showRow = false;
                }
            }

            // Check semester if selected
            if (selectedSemester !== '' && showRow) {
                if (!semesterHeader.text().includes(selectedSemester + ' Semester') && 
                    !(selectedSemester === 'Summer' && semesterHeader.text().includes('Summer'))) {
                    showRow = false;
                }
            }

            // Show/hide rows based on filters
            if (showRow) {
                row.show();
                yearHeader.show();
                semesterHeader.show();
            }
        });
    }

    // Apply filters when either dropdown changes
    $('#yearFilter, #semesterFilter').on('change', function() {
        applyFilters();
    });

    // School year filter (keep this unchanged)
    $('#schoolYearFilter').on('change', function() {
        const schoolYear = $(this).val();
        if (schoolYear === '') {
            $('.tab-pane.active tbody tr').show();
            return;
        }
        // Add school year filtering logic here if needed
    });
}); 

// Add this to your existing JavaScript file
$(document).on('click', '.edit-subject', function() {
    const id = $(this).data('id');
    const row = $(this).closest('tr');
    
    // Get values from the current row
    const subjectCode = row.find('td:eq(0)').text();
    const descriptiveTitle = row.find('td:eq(1)').text();
    const prerequisite = row.find('td:eq(2)').text();
    const lecUnits = row.find('td:eq(3)').text();
    const labUnits = row.find('td:eq(4)').text();
    
    // Populate the edit modal fields
    $('#editSubjectId').val(id);
    $('#editSubjectCode').val(subjectCode);
    $('#editDescriptiveTitle').val(descriptiveTitle);
    $('#editPrerequisite').val(prerequisite === 'None' ? '' : prerequisite);
    $('#editLecUnits').val(lecUnits);
    $('#editLabUnits').val(labUnits);
    
    // Calculate total units
    const totalUnits = parseFloat(lecUnits) + parseFloat(labUnits);
    $('#editTotalUnits').val(totalUnits.toFixed(1));
    
    // Show the modal
    $('#editSubjectModal').modal('show');
});