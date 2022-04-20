<?php
include("./includes/header.php");
include("./includes/connection.php");

?>
<!-- link menu -->
<script type="text/javascript">
    function gotolink(){
        var patientlist = document.getElementById('patientlist').value;
        if(patientlist=='Outpatient Credit'){
            document.location = "seachoutpatientcreditlablist.php?from=result&seachoutpatientcreditlablist=seachoutpatientcreditlablist";
        }else if(patientlist=='Outpatient Cash'){
            document.location = "seachoutpatientcashlablist.php?from=result&seachoutpatientcashlablist=seachoutpatientcreditlablist";
        }else if(patientlist=='Inpatient Credit'){
            document.location = "seachinpatientcashlablist.php?from=result&seachinpatientcashlablist=seachinpatientcashlablist";
        }else if(patientlist=='Inpatient Cash'){
            document.location = "seachinpatientcreditlablist.php?from=result&seachinpatientcreditlablist=seachinpatientcreditlablist";
        }else if (patientlist=='Patient From Revenue Center') {
            document.location = "searchpatientrevenuelablist.php?from=result&searchpatientlaboratorylist=searchpatientlaboratorylist";

        }else if(patientlist=='All Patients List'){
            document.location = "seachpatientfromspeciemenlist.php?from=result&searchpatientfromspecimenlist=searchpatientfromspecimenlist";
        }else{
            alert("Choose Type Of Patients To View");
        }
    }
</script>

<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
    <select id='patientlist' name='patientlist'>
        <option selected='selected'>
            Chagua Orodha Ya Wagonjwa
        </option>
        <option>
            Outpatient Credit
        </option>
        <option>
            Outpatient Cash
        </option>
        <option>
            Inpatient Credit
        </option>
        <option>
            Inpatient Cash
        </option>
        <option>
            Patient From Revenue Center
        </option>
        <option>
            All Patients List
        </option>
    </select>
    <input type='button' value='VIEW' onclick='gotolink()'>
</label>
<a href="seachpatientfromspeciemenlist_done.php?Search_Previous_Laboratory_ResultThisPage=ThisPage" class="art-button-green">BACK</a>


<?php
if(isset($_POST['Date_From'])){
    $Date_From = $_POST['Date_From'];
}else{
    $Date_To = '';
}

if(isset($_POST['Date_To'])){
    $Date_To = $_POST['Date_To'];
}else{
    $Date_To = '';
}
?>
<fieldset style="margin-top:10px;">
    <legend align=right><b>  PREVIOUS LABORATORY RESULT</b>  </legend>
    <script language="javascript" type="text/javascript">
        function searchPatient(Patient_Name=''){

            var DateGiven_From = "<?php echo $Date_From?>";
            var DateGiven_To = "<?php echo $Date_To?>";

            var DateGiven_From = document.getElementById('date_From').value;
            var DateGiven_To = document.getElementById('date_To').value;

            document.getElementById('Search_Iframe').src = "previous_laboratory_results_filter_Iframe2.php?Patient_Name="+Patient_Name+"&DateGiven_From="+DateGiven_From+"&DateGiven_To="+DateGiven_To+" ";

        }
    </script>
    <center>
        <form action="search_previous_result.php" method="POST">
            <table  class="hiv_table" style="width:100%;margin-top:5px;">
                <tr>
                    <!--                             <td width="100px" >
                                                <input type="text" name='date6' id='date6'  onchange="searchPatient()"
                                                onkeypress='searchPatient()' placeholder='  ~~~ Today ~~~~' style="padding-left:2px;font-size:14px;">
                                                </td> -->

                    <td style="text-align:right;width:80px"><b>Date From<b></td>
                    <td width="150px"><input type='text' name='Date_From' id='date_From'></td>
                    <td style="text-align:right;width:80px"><b>Date To<b></td>
                    <td width="150px"><input type='text' name='Date_To' id='date_To'></td>
                    <td width="50px"><input type="submit" name="submit" value="Filter" class="art-button-green"></td>
                    <td style="text-align:right;width: 80px;"><b>Search Patient<b></td>
                    <td width="150px"><input type="text" name="Patient_Name" id="Patient_Name" style="text-align: center" onkeyup="searchPatient(this.value)" placeholder="~~~~~Search Patient~~~~~"/></td>
                    <!--                        <td id="sampleForm"> -->
                    <!--                        <input type='text' style="width:350px" name='Search_Patient' id='Search_Patient' onclick='searchPatient(this.value)'-->
                    <!--                                                 onkeypress='searchPatient(this.value)' placeholder='   ~~~~~~~~~~~~Enter Patient Name~~~~~~~~~~~'>-->
                    <!--                        <img style="margin-left:0px;" src="images/barcode.png" alt="Scanning the barcode!" onclick="alert('Scanning the barcode!');" />-->
                    <!--                         </td>-->

                </tr>
            </table>
        </form>
    </center>

    <hr width="100%">
    <center>
        <?php
            if(isset($_POST['Date_From'])){
                $Date_From = $_POST['Date_From'];
            }else{
                $Date_To = '';
            }

            if(isset($_POST['Date_To'])){
                $Date_To = $_POST['Date_To'];
            }else{
                $Date_To = '';
            }
        ?>
        <table  class="hiv_table" style="width:100%">
            <tr>
                <td>
                    <iframe id="Search_Iframe" width='100%' height=420px src='previous_laboratory_results_filter_Iframe.php?Date_From=<?php echo $Date_From; ?>&Date_To=<?php echo $Date_To; ?>'></iframe>
                </td>
            </tr>
        </table>
    </center>
</fieldset>
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