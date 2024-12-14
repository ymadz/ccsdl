$(document).ready(function() {
    // Function to filter table rows
    function filterTable() {
        var searchText = $('#searchInput').val().toLowerCase();
        var yearFilter = $('#yearFilter').val();
        var semesterFilter = $('#semesterFilter').val();
        var schoolYearFilter = $('#schoolYearFilter').val();

        // Hide all year level and semester headers initially
        $('tbody .table-primary, tbody .table-secondary').hide();
        
        // Filter through each regular row in tbody
        $('tbody tr:not(.table-primary):not(.table-secondary)').each(function() {
            var row = $(this);
            var code = row.find('td:eq(0)').text().toLowerCase();
            var title = row.find('td:eq(1)').text().toLowerCase();
            
            // Get year level and semester from previous headers
            var yearLevel = row.prevAll('.table-primary:first').find('th').text().replace('Year Level ', '');
            var semester = row.prevAll('.table-secondary:first').find('th').text().replace(' Semester', '');

            // Check if row matches all filters
            var matchesSearch = (code.includes(searchText) || title.includes(searchText));
            var matchesYear = !yearFilter || yearLevel.includes(yearFilter);
            var matchesSemester = !semesterFilter || semester.includes(semesterFilter);
            // Add school year filter when implemented in database
            
            if (matchesSearch && matchesYear && matchesSemester) {
                row.show();
                // Show the corresponding headers
                row.prevAll('.table-primary:first').show();
                row.prevAll('.table-secondary:first').show();
            } else {
                row.hide();
            }
        });
    }

    // Attach event listeners to all filter inputs
    $('#searchInput, #yearFilter, #semesterFilter, #schoolYearFilter').on('input change', function() {
        filterTable();
    });
}); 