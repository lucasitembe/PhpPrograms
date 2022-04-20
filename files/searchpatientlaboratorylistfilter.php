<?php
include("./includes/functions.php");
        include("./includes/laboratory_specimen_collection_header.php");
?>
<?php
//get sub department id
$Sub_Department_ID='';
if(isset($_SESSION['Laboratory'])){
    $Sub_Department_Name = $_SESSION['Laboratory'];
    $select_sub_department = mysqli_query($conn,"select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name'");
    while($row = mysqli_fetch_array($select_sub_department)){
        $Sub_Department_ID = $row['Sub_Department_ID'];
    }
}else{
    $Sub_Department_ID = '';
}
?>
<fieldset style='margin-top:10px;'>
                <?php
                    $Date_From=mysqli_real_escape_string($conn,$_POST['Date_From']);
                    $Date_To=mysqli_real_escape_string($conn,$_POST['Date_To']);
                 ?>
                <legend align=right><b>  ALL PATIENT LAB LIST</b></legend>
                <script language="javascript" type="text/javascript">
                    function searchPatient(Patient_Name=''){

                        //var DateGiven_From = document.getElementById('date_From').value;
                        //var DateGiven_To = document.getElementById('date_To').value;
                        var Date_From = '<?php echo $Date_From?>';
                        var Date_To = '<?php echo $Date_To?>';

                        document.getElementById('Search_Iframe').innerHTML = 
                             "<iframe width='100%' height=320px src='search_patient_laboratory_list__filter_iframe.php?Patient_Name="+Patient_Name+"&Date_From="+Date_From+"&Date_To="+Date_To+"'></iframe>";     
                        
                    }
                </script>



                <form action="searchpatientlaboratorylistfilter.php?searchpatientlaboratorylistfilterThisPage=ThisPage" method='POST'>
                    <center>
                     <table  class="hiv_table" style="width:100%;margin-top:5px;">
                        <tr> 
    <!--                             <td width="100px" >
                                <input type="text" name='date6' id='date6'  onchange="searchPatient()" 
                                onkeypress='searchPatient()' placeholder='  ~~~ Today ~~~~' style="padding-left:2px;font-size:14px;">
                                </td> -->
    
                            <td style="text-align:right;width:80px"><b>Date From<b></td>
                            <td width="150px"><input type='text' name='Date_From' id='date_From' required='required'></td>
                            <td style="text-align:right;width:80px"><b>Date To<b></td>
                            <td width="150px"><input type='text' name='Date_To' id='date_To' required='required'></td>
                            <td width="50px"><input type='submit' name='submit' value="Filter" class='art-button-green'/></td>
                            <td style="text-align:right;width: 80px;"><b>Search Patient<b></td>
                            <td width="150px"><input type="text" name="search_patient" id="search_patient" onkeyup="searchPatient(this.value)"/></td>
                            <!--<td id="sampleForm"> 
                            <input type='text' style="width:350px" name='Search_Patient' id='Search_Patient' onclick='searchPatient(this.value)'
                                                     onkeypress='searchPatient(this.value)' placeholder='   ~~~~~~~~~~~~Enter Patient Name~~~~~~~~~~~'>
                            <img style="margin-left:0px;" src="images/barcode.png" alt="Scanning the barcode!" onclick="alert('Scanning the barcode!');" />
                             </td>-->
    
                        </tr> 
                    </table>
                </center>    
                </form>                                
                         
                                                    


                                                    </center>
                                                    <center>
                                                        <hr width="100%">
                                                    </center>
                                                    <?php
                                                       $Date_From=mysqli_real_escape_string($conn,$_POST['Date_From']);
                                                       $Date_To=mysqli_real_escape_string($conn,$_POST['Date_To']);
                                                    ?>
                                                            <center>
                                                                <table  class="hiv_table" style="width:100%">
                                                                    <tr>
                                                                        <td id='Search_Iframe'>
                                                                                <iframe width='100%' height=420px src='Today_Lab_Patients_Iframe.php?Sub_Department_ID=<?php echo $Sub_Department_ID;?>&Date_From=<?php echo $Date_From?>&Date_To=<?php echo $Date_To?>'></iframe>
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