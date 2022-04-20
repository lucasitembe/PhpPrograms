<?php 
    include './includes/header.php';
    include './includes/connection.php';
?>
 
<style>
    #table_custom{
        background-color: #eee;
        font-size: 14px;
        text-align: center;
    }
    #table_custom tr td{
        padding: 9px;
    }
    .data{
        padding: 14px;
    }
    select{
        width: 100%;
        padding: 5px;
        font-family: Arial, Helvetica, sans-serif;
    }
    .tbody tr:hover {
        background-color: #eee;
        cursor: pointer;
    }
    #summary{
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
    }
    .overall{
        background-color: #006400;
        padding: 1em;
        color: #fff;
    }
</style>

<a href="managementworkspage.php?ManagementWorksPage=ManagementWorksPageThisPage" class="art-button-green" style="font-family: Arial, Helvetica, sans-serif;">BACK</a>

<br><br>

<table style="width:100%" id="table_custom">
    <tr>
        <td width='20%' class="data">
            <select name="employee" id="employee">
                <option value="">----- Employee Name -----</option>
                <?php 
                    $get_employee = mysqli_query($conn,"SELECT Employee_ID,Employee_Name FROM tbl_employee"); 
                    while($data = mysqli_fetch_assoc($get_employee)){ ?>
                    <option value="<?=$data['Employee_ID']?>"><?=$data['Employee_Name']?></option>
                <?php } ?>
            </select>
        </td>
        <td width='20%'>
            <input type="text" style="text-align: center;padding:6px" placeholder=" ----- Start Date ----- " id="Date_From" class="Date_From">
        </td>
        <td width='20%'>
            <input type="text" style="text-align: center;padding:6px" placeholder=" ----- End Date ----- " id="Start_From" class="Start_From">
        </td>
        <td width='20%' style="padding: 12px;">
            <a href="#" class="art-button-green" onclick="getFilteredData()" style="font-family: Arial, Helvetica, sans-serif;">FILTER</a>
        </td>
    </tr>
</table>

<br>

<fieldset style="height: 600px;overflow-y:scroll;overflow-x:hidden;padding:7px">
    <table id="table_custom" class="custom" style="width: 100%;">
        <thead style="background-color:#037CB0;color:#fff;">
            <tr>
                <td width="3%" style="text-align: center;">S/N</td>
                <td width="11%">EMPLOYEE NAME</td>
                <td width="11%">CLIENT INFO</td>
                <td width="11%">LOGIN TIME</td>
                <td width="11%">lOGOUT TIME</td>
                <td>ACTIVITIES</td>
            </tr> 
        </thead>
        <tbody class="tbody" id="get_data">
            <tr>
                <td colspan='7' style='text-align:center;padding:2em;color:red;font-size:20px'>Filter Data</td>
            </tr>
        </tbody> 
    </table>
</fieldset>

<script> 
    $(document).ready(() => {
        getFilteredData();
    });

    function getFilteredData() {  
        var Date_From = $('#Date_From').val();
        var Start_From = $('#Start_From').val();
        var employee = $('#employee').val();

        if(Date_From != "" && Date_From === ""){
            alert("Enter Start And End Date");
        }else{
            $.ajax({
                type: "GET",
                url: "get_audit_filtered_data.php",
                cache:false,
                data: {
                    Date_From:Date_From,
                    Start_From:Start_From,
                    employee:employee
                },
                success: (response) => {
                    $('#get_data').html(response);
                }
            });
        }
        
    }
</script>

<script>
   $(document).ready(function() {
      $('#Date_From').datetimepicker({
         dayOfWeekStart: 1,
         lang: 'en',
      });
      $('#Start_From').datetimepicker({
         value: '',
         step: 30
      });
   });
</script>
<script>
    $(document).ready(function (e){
        $("#employee").select2({
            sortField: 'text'
        });
    });
</script>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
 
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>

<?php include './includes/footer.php'; ?>
