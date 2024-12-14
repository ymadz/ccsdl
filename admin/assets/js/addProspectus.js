$(document).ready(function () {
  // Handle course selection change
  $("#courseSelect").change(function () {
    // Update form fields based on selected course
    updateFormFields($(this).val());
  });

  // Initialize form fields based on default selection
  updateFormFields($("#courseSelect").val());

  function updateFormFields(courseType) {
    // Update subject code prefix based on course
    let prefix = "";
    switch (courseType) {
      case "bscs":
        prefix = "CS";
        break;
      case "bsit":
        prefix = "IT";
        break;
      case "act-ad":
        prefix = "AD";
        break;
      case "act-nw":
        prefix = "NW";
        break;
    }
    $("#subjectCodePrefix").text(prefix);
  }

  // Handle form submission
  $("#addProspectusForm").submit(function (e) {
    e.preventDefault();

    // Gather form data
    let formData = {
      course: $("#courseSelect").val(),
      subject_code: $("#subjectCode").val(),
      descriptive_title: $("#descriptiveTitle").val(),
      prerequisite: $("#prerequisite").val(),
      lec_units: $("#lecUnits").val(),
      lab_units: $("#labUnits").val(),
      year_level: $("#yearLevel").val(),
      semester: $("#semester").val(),
      school_year: $("#schoolYear").val(),
    };

    // Send AJAX request
    $.ajax({
      type: "POST",
      url: "handlers/add_prospectus.php",
      data: formData,
      success: function (response) {
        try {
          let result = JSON.parse(response);
          if (result.status === "success") {
            // Show success message
            Swal.fire({
              title: "Success!",
              text: "Subject added successfully",
              icon: "success",
              confirmButtonText: "OK",
            }).then((result) => {
              if (result.isConfirmed) {
                // Refresh the page or update the table
                location.reload();
              }
            });
          } else {
            throw new Error(result.message);
          }
        } catch (error) {
          Swal.fire({
            title: "Error!",
            text: error.message || "Failed to add subject",
            icon: "error",
            confirmButtonText: "OK",
          });
        }
      },
      error: function () {
        Swal.fire({
          title: "Error!",
          text: "Server error occurred",
          icon: "error",
          confirmButtonText: "OK",
        });
      },
    });
  });

  // Validate numeric inputs
  $(".numeric-input").on("input", function () {
    this.value = this.value.replace(/[^0-9.]/g, "");
  });

  // Calculate total units automatically
  $("#lecUnits, #labUnits").on("input", function () {
    let lec = parseFloat($("#lecUnits").val()) || 0;
    let lab = parseFloat($("#labUnits").val()) || 0;
    $("#totalUnits").val((lec + lab).toFixed(1));
  });
});
