<?php
include("includes/header.php");
include("includes/connection.php");
$DateGiven = date('Y-m-d');
?>
<?php
//get sub department id
$Sub_Department_ID='';
if(isset($_SESSION['Radiology'])){
    $Sub_Department_Name = $_SESSION['Radiology'];
    $select_sub_department = mysqli_query($conn,"select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name'");
    while($row = mysqli_fetch_array($select_sub_department)){
        $Sub_Department_ID = $row['Sub_Department_ID'];
    }
}else{
    $Sub_Department_ID = '';
}

$Date_From=$_POST['Date_From'];
$Date_To=$_POST['Date_To'];
?>
<a href='radiologyworkspage.php'class='art-button-green'>
    BACK
</a>

<br>
<br>
<fieldset style='margin-top:10px;'>
    <legend align="center"><b>  ALL RADIOLOGY PATIENT  LIST</b></legend>
    <script language="javascript" type="text/javascript">
        function searchPatient(Patient_Name=''){

            if(document.getElementById('date_From').value !='' && document.getElementById('date_To').value !=''){
                var DateFrom = document.getElementById('date_From').value;
                var DateTo = document.getElementById('date_To').value;
                var Sub_Department_ID="<?php echo $Sub_Department_ID;?>";

                document.getElementById('Search_Iframe').innerHTML =
                    "<iframe width='100%' height=320px src='search_patient_radiology_list_filter_iframe2.php?Sub_Department_ID="+Sub_Department_ID+"&Patient_Name="+Patient_Name+"&DateFrom="+DateFrom+"&DateTo="+DateTo+"'></iframe>";
            }else{
                var DateFrom ="<?php echo $Date_From?>";
                var DateTo = "<?php echo $Date_To?>";
                var Sub_Department_ID="<?php echo $Sub_Department_ID;?>";

                document.getElementById('Search_Iframe').innerHTML =
                    "<iframe width='100%' height=320px src='search_patient_radiology_list_filter_iframe2.php?Sub_Department_ID="+Sub_Department_ID+"&Patient_Name="+Patient_Name+"&DateFrom="+DateFrom+"&DateTo="+DateTo+"'></iframe>";
            }



        }
    </script>



    <center>
        <form action="PatientRadiologyFilter.php" method="post">
            <table  class="hiv_table" style="width:80%;margin-top:5px;">
                <tr>


                    <td style="text-align:right;width:80px"><b>Date From<b></td>
                    <td width="150px"><input type='text' name='Date_From' id='date_From' required="required"></td>
                    <td style="text-align:right;width:80px"><b>Date To<b></td>
                    <td width="150px"><input type='text' name='Date_To' id='date_To' required="required"></td>
                    <td><input type="submit" name="submit" value="Filter" class="art-button-green"/></td>
                    <td width='40%'>
                        <input type='text' name='Search_Patient' id='Search_Patient' onclick='searchPatient(this.value)'
                               onkeyup='searchPatient(this.value)' placeholder='~~~~~~~~~~~~~~Search Patient Name~~~~~~~~~~~~~'>
                    </td>

                </tr>
            </table>
        </form>



    </center>
    <center>
        <hr width="100%">
    </center>

    <center>
        <table  class="hiv_table" style="width:100%">
            <?php
                $DateFrom=$_POST['Date_From'];
                $DateTo=$_POST['Date_To'];
            ?>
            <tr>
                <td id='Search_Iframe'>
                    <iframe width='100%' height=420px src='search_patient_radiology_list_filter_iframe.php?DateFrom=<?php echo $DateFrom?>&DateTo=<?php echo $DateTo?>&Sub_Department_ID=<?php echo $Sub_Department_ID?>&DateGiven=<?php echo $DateGiven?>'></iframe>
                </td>
            </tr>
        </table>
    </center>
</fieldset>
<br/>
<?php
include("./includes/footer.php");
?>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#date_From').datetimepicker({
        dayOfWeekStart : 1,
        lang:'en',
        startDate:  'now'
    });
    $('#date_From').datetimepicker({value:'',step:30});
    $('#date_To').datetimepicker({
        dayOfWeekStart : 1,
        lang:'en',
        startDate:'now'
    });
    $('#date_To').datetimepicker({value:'',step:30});
</script>
<!--End datetimepicker-->