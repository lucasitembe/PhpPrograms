$(document).ready(function () {
   display_info();
   $("#add_motitoring_doc").click(function (e) {
      e.preventDefault();
      var registration_id = $("#registration_id").val();
      var monitoring_date = $("#monitoring_date").val();
      var body_weight = $("#body_weight").val();
      var comment = $("#comment").val();
      var employee_id = $("#employee_id").val();

      $.ajax({
         url: './save_monitoring_burn_unit.php',
         type: 'post',
         data: { registration_id: registration_id, monitoring_date: monitoring_date, body_weight: body_weight, comment: comment, employee_id: employee_id },
         success: function (response) {
            display_info();
         }
      });
   });
});

$(document).ready(function () {
   $('#filter_motitoring_doc').click(function (e) {
      e.preventDefault();
      var monitoring_start_date_filter = $('#monitoring_start_date_filter').val();
      var monitoring_end_date_filter = $('#monitoring_end_date_filter').val();
      var registration_id = $("#registration_id").val();
      var employee_id = $("#employee_id").val();
      $.ajax({
         url: './burn_unit_monitoring_filter.php',
         type: 'POST',
         data: { 
            registration_id: registration_id,
            monitoring_start_date_filter: monitoring_start_date_filter, 
            monitoring_end_date_filter: monitoring_end_date_filter, 
            employee_id : employee_id
         },
         success: function (response) {
            $('#display_info').html(response);
         }
      })
   });
});

function display_info() {
   var registration_id = $("#registration_id").val();
   var employee_id = $("#employee_id").val();
   $.ajax({
      type: 'post',
      url: './load_monitoring_data.php',
      data: { registration_id: registration_id, employee_id: employee_id },
      success: function (response) {
         $('#display_info').html(response);
      }
   });
}