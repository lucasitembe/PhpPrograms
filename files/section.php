 
 <style>
 .section_table{
     padding: 10px;
 }
 .section_table tr th{
     font-weight: bold;
     background: grey;
     border: 1px solid #000;
 }
 .section_table tr th{
        border: 1px solid #fff;
    }
    .section_table tr:nth-child(even){
        background-color: #eee;
    }
    .section_table tr:nth-child(odd){
        background-color: #fff;
        
    }
 </style>

 <?php if(isset($_POST['sectiondialog'])){?>
 <select name="section_required" id="section_required" style="width: 100%;">
        <option>Select Required Section</option>
        <option value='Biomedical'>Biomedical</option>
        <option value='Electrical'>Electrical</option>
        <option value='Mechanical'>Mechanical</option>
        <option value='Mason'>Mason</option>
        <option value='Plumber'>Plumber</option>
        <option value='Carpentry'>Carpentry</option>
        <option value='Welding'>Welding</option>
        <option value='Painting'>Painting</option>
    </select>
 <?php } ?>
 <table width="100%" class='section_table'>
    <tr>
        <th width='10%' style='text-align: center; padding:10px;'>S/N</th>
        <th width='70%' style='text-align: center; padding:10px;'>Name of Staff</th>
        <th width='10%' style='text-align: center; padding:10px;'>Open Jobs</th>
        <th width='10%' style='text-align: center; padding:10px;'>Closed Jobs</th>
    </tr>
    <tbody id="list_section">
    </tbody>
</table>

<script src="css/jquery.js"></script>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script>
    $(document).ready(() => {
        $('#section_required').change(function() {
            var option = $(this).children('option:selected').val(); 
            var text = $(this).children('option:selected').text(); 
            $.get(
                'Selected_section_details.php',{ 
                    option : option
                },(data) => {
                    $("#list_section").html(data);
                }
            );
        });
    });
</script>