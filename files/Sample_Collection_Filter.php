<?php
include("./includes/functions.php");
        include("./includes/header.php");
                $DateGiven = date('Y-m-d');
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
<a href="Sample_Collection.php?SampleCollectionThisPage=ThisPage" class="art-button-green">BACK</a>
<?php
//get date from the form post
if($_POST['Date_From']!=''){
    $Date_From=$_POST['Date_From'];
}else{
    $Date_From='';
}

if($_POST['Date_To']!=''){
    $Date_To=$_POST['Date_To'];
}else{
    $Date_To='';
}
?>

<fieldset style='margin-top:10px;'>
                <legend align=right><b>TODAY'S LABORATORY SAMPLE REPORT</b></legend>
                <script language="javascript" type="text/javascript">
                    function searchPatient(Patient_Name=''){

                        //var DateGiven_From = document.getElementById('date_From').value;
                        //var DateGiven_To = document.getElementById('date_To').value;
                        
                        var DateGiven_From = '<?php echo $DateGiven?>';
                        var DateGiven_To = '<?php echo $DateGiven?>';
                        var Sub_Department_ID='<?php echo $Sub_Department_ID;?>';

                        document.getElementById('Search_Iframe').innerHTML = 
                             "<iframe width='100%' height=420px src='search_patient_laboratory_list_iframe.php?Patient_Name="+Patient_Name+"&DateGiven_From="+DateGiven_From+"&DateGiven_To="+DateGiven_To+"&Sub_Department_ID="+Sub_Department_ID+"'></iframe>";
                        
                    }
                </script>



                <form action="Sample_Collection_Filter.php?SampleCollectionThisPage=ThisPage" method='POST'>
                        <center>
                                <table  class="hiv_table" style="width:100%;margin-top:5px;">
                                        <tr> 
        <!--                             <td width="100px" >
                                    <input type="text" name='date6' id='date6'  onchange="searchPatient()" 
                                    onkeypress='searchPatient()' placeholder='  ~~~ Today ~~~~' style="padding-left:2px;font-size:14px;">
                                    </td> -->
        
                                                <td style="text-align:right;width: 80px;"><b>Date From<b></td>
                                                <td width="150px"><input type='text' name='Date_From' id='date_From' required='required'></td>
                                                <td style="text-align:right;width: 80px;"><b>Date To<b></td>
                                                <td width="150px"><input type='text' name='Date_To' id='date_To' required='required'></td>
                                                <td width="50px"><input type="submit" name="submit" value="Filter" class="art-button-green" /></td>
                                                <!--<td style="text-align:right;width: 80px;"><b>Search Patient<b></td>
                                                <td width="150px"><input type="text" name="search_patient" id="search_patient" onkeyup="searchPatient(this.value)"/></td>-->
                                                <!--<td id="sampleForm"> 
                                                <input type='text' style="width:350px" name='Search_Patient' id='Search_Patient' onclick='searchPatient(this.value)'
                                                                         onkeypress='searchPatient(this.value)' placeholder='   ~~~~~~~~~~~~Enter Patient Name~~~~~~~~~~~'>
                                                <img style="margin-left:0px;" src="images/barcode.png" alt="Scanning the barcode!" onclick="alert('Scanning the barcode!');" />
                                                 </td>-->
        
                                        </tr>
                                        <tr>
                                            <td colspan="9" style="text-align: center">
                                                <fieldset>
                                                    <b style="font-size: 14px">SAMPLE REPORT FROM <?php echo date('j F, Y H:i:s',strtotime($Date_From))?> TO <?php echo date('j F, Y H:i:s',strtotime($Date_To))?></b>
                                                </fieldset>
                                            </td>
                                        </tr>
                                </table>
                </center>
                </form>                                    
                         
                                                    


                                                    </center>
                                                    <center>
                                                        <hr width="100%">
                                                    </center>

                                                            <center>
                                                                <table  class="hiv_table" style="width:100%">
                                                                    <tr>
                                                                        <td id='Search_Iframe'>
                                                                                <iframe width='100%' height=420px src='Sample_Collection_Filter_Iframe.php?Sub_Department_ID=<?php echo $Sub_Department_ID;?>&Date_From=<?php echo $Date_From?>&Date_To=<?php echo $Date_To?>'></iframe>
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