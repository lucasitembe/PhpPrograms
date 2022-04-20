$(document).ready(function () {
   display_info_chat();
   $("#btn_document_chat").click(function (e) {
      e.preventDefault();
      var date_time = $('#Date_From').val();
      var seizure = $("#seizure option:selected").text();
      var registration_id = $('#registration_id').val();
      var duration = $('#duration').val();
      var drugs_given = $('#drugs_given').val();
      var employee_id = $('#employee_id').val();
      $.ajax({
         url: './save_convulsion_data.php',
         type: 'post',
         data: { 
            date_time : date_time,
            registration_id: registration_id,
            seizure: seizure,
            duration: duration,
            drugs_given : drugs_given,
            employee_id : employee_id },
         success: function (response) {
            display_info_chat();
            alert("Data Saved");
         }
      });
   });
});

function display_info_chat(){
   var employee_id = $('#employee_id').val();
   var registration_id = $("#registration_id").val();
   $.ajax({
      type: 'post',
      url: './load_convulsion_data.php',
      data: { employee_id : employee_id, registration_id : registration_id },
      success: function (response) {
         $( '#display_info_test_1' ).html(response);
      }
   });
}