    <?php
    include("includes/connection.php");
    session_start();
    if (isset($_POST['Payment_Item_Cache_List_ID'])) {
    $Payment_Item_Cache_List_ID=$_POST['Payment_Item_Cache_List_ID'];
    $Product_Name=$_POST['Product_Name'];
    $id=$_POST['id'];
    $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
    $count_run=1;


        $item_info = mysqli_fetch_Assoc(mysqli_query($conn, "SELECT Service_Date_And_Time FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'"))['Service_Date_And_Time'];
    $sql_select_machine_run_time_result=mysqli_query($conn,"SELECT td.tb_screen_ID, td.reason, td.previous , td.specimen, td.Microbiology, td.Xpert, td.HIV, td.month , td.date_added, td.DTLC_name, td.DTLC_email, td.RTLC_name, td.RTLC_email, pr.Registration_ID, td.Employee_ID, em.Employee_Name FROM tbl_tb_diagnosis td, tbl_patient_registration pr, tbl_employee em WHERE td.Registration_ID=pr.Registration_ID AND  td.Registration_ID = '$id' AND em.Employee_ID = td.Employee_ID ORDER BY tb_screen_ID DESC LIMIT 1") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_machine_run_time_result)>0){
        while($run_time_rows=mysqli_fetch_assoc($sql_select_machine_run_time_result)){
            $Patient_Name=$run_time_rows['Patient_Name'];
            $tb_screen_ID = $run_time_rows['tb_screen_ID'];
            $Doctor_namee = $run_time_rows['Employee_Name'];
            
            $Receiver_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT em.Employee_Name from tbl_employee em, tbl_specimen_results sr WHERE payment_item_ID = '$Payment_Item_Cache_List_ID' AND em.Employee_ID = sr.Employee_ID_receive"))['Employee_Name'];
            // die($Receiver_Name);
    ?>
    <fieldset style='height:1010px;'>
    <table width="100%" class="art-article" style="font-size:18px; background-color:#fff; line-height: 2.2; border: none !important;">
    <center>
    <div style="font-size: 20px;">
    <b>TB SCREENING FOR:- <?php echo $Patient_Name  ?></b>
    <div>
    </center>
        <tr>
            <td style="text-align: right;">
                <b>Reason for Examination</b>
            </td>
            <td>
                <?php echo $run_time_rows['reason'] ?> ~~ <i>if follow-Up, Month of Treatment</i> :- <b> <?php echo $run_time_rows['month']?> </b></td>
        </tr>
        <tr>
            <td style="text-align: right;">
                <b>HIV Status</b>
            </td>
            <td>
                <?php
                echo $run_time_rows['HIV'];
                ?>
            </td>
        </tr>
        <tr>
            <td style="text-align: right;">
                <b>Previously Treated for TB?</b>
            </td>
            <td>
                <?php
                echo "<b>".$run_time_rows['previous']."</b>";
                ?>
        </tr>
        <tr>
            <td style="text-align: right;">
                <b>Specimen Type</b>
            </td>
            <td>
                <?php
                echo $run_time_rows['specimen'];
                ?>
            </td>
        </tr>
        <tr>
            <td style="text-align: right;">
                <b>Test(s) Request</b>
            </td>
            <td>
                <?php
                echo $run_time_rows['Microbiology']." , ".$run_time_rows['Xpert'];
                ?>
            </td>
        </tr>
        <tr>
            <td style="text-align: right;">
                <b>Name of the Person Requesting Examination: </b>
            </td>
            <td>
                <input type='text' name='' id='' value="<?php echo $run_time_rows['Employee_Name']; ?>">
            </td>
        </tr>
        <tr>
        <td colspan='2'><hr></td>
        </tr>
        <tr>
            <td style="text-align: right;">
                <b>Laboratory Serial Number: </b>
            </td>
            <td>
                <input type='text' name='lab_Serial' id='lab_serial' placeholder='Laboratory Serial No'>
            </td>
        </tr>
        <tr>
        <td colspan='2'><hr></td>
        </tr>
    </table>
    <table width="100%" class="art-article" style="font-size:18px; background-color:#fff; line-height: 2.2; border-style: none;">
        <tr>
        <td colspan="2" style="text-align: center;">
            <b>Contacts for Results Feedback (If RR for Xpert MTB/RIF) DTLC / RTLC</b>
        </td>
        </tr>
        <?php
        

        echo"<tr>
            <td>
                <b>RTLC Name : </b><input type='text' name='RTLC_name' readonly='readonly' id='RTLC_name' placeholder='RTLC Name' style='display: inline; width: 82%;' value=".$run_time_rows['RTLC_name'].">
            </td>
            <td>
                <b>Email Contact : </b><input type='email' readonly='readonly' name='RTLC_email' id='RTLC_email' placeholder='Email Contact' style='display: inline; width: 82%;' value=".$run_time_rows['RTLC_email'].">
            </td>
        </tr>
        <tr>
            <td>
                <b>DTLC Name : </b><input type='text' readonly='readonly' name='DTLC_name' id='DTLC_name' placeholder='DTLC Name' style='display: inline; width: 82%;' value=".$run_time_rows['DTLC_name'].">
            </td>
            <td>
                <b>Email Contact : </b><input type='email' readonly='readonly' name='DTLC_email' id='DTLC_email' placeholder='Email Contact' style='display: inline; width: 82%;' value=".$run_time_rows['DTLC_email'].">
            </td>
        </tr>";
        ?>
        <tr>
        <td colspan='2'><hr></td>
        </tr>
        </table>
        
        <table width="100%" class="art-article" style="font-size:18px; background-color:#fff;">
        <tr>
            <td colspan="5" style='text-align: center;'>
            <span style="font-weight: bold; text-align: center; font-size: 19px; width:100%;">Results (To be completed in the Laboratory)</span>
            </td>
        </tr>
        <tr>
            <th>Date</th>
            <th>Specimen</th>
            <th>Received by</th>
            <th>Appearance*</th>
            <th>Result (Choose One)</th>
        </tr>
    <?php 
    echo "<tr>
            <td style='text-align: center;'>".$item_info."</td>
            <td style='text-align: center; font-weight: bold;'>A</td>
            <td><input type='text' style='width: 100;' name='receiver_A' id='receiver_A' value='$Receiver_Name' readonly='readonly'></td>
            <td><input type='text' style='width: 100; border: 2px solid #000 !important;' name='apperance_A' id='apperance_A'></td>
            <td>
                <input type='radio' name='results_A' class='results_A' value='Neg'>Neg &nbsp;&nbsp;&nbsp;&nbsp;
                <input type='radio' name='results_A' class='results_A' value='Scanty'>Scanty &nbsp;&nbsp;&nbsp;&nbsp;
                <input type='radio' name='results_A' class='results_A' value='1+'>1+ &nbsp;&nbsp;&nbsp;
                <input type='radio' name='results_A' class='results_A' value='2+'>2+ &nbsp;&nbsp;&nbsp;
                <input type='radio' name='results_A' class='results_A' value='3+'>3+
            </td>
        </tr>
        <tr>
            <td style='text-align: center;'>".$item_info."</td>
            <td style='text-align: center; font-weight: bold;'>B</td>
            <td><input type='text' style='width: 100;' name='receiver_B' id='receiver_B' value='$Receiver_Name' readonly='readonly'></td>
            <td><input type='text' style='width: 100; border: 2px solid #000 !important;' name='apperance_B' id='apperance_B'></td>
            <td>
                <input type='radio' name='results_B' class='results_B' value='Neg'>Neg &nbsp;&nbsp;&nbsp;&nbsp;
                <input type='radio' name='results_B' class='results_B' value='Scanty'>Scanty &nbsp;&nbsp;&nbsp;&nbsp;
                <input type='radio' name='results_B' class='results_B' value='1+'>1+ &nbsp;&nbsp;&nbsp;
                <input type='radio' name='results_B' class='results_B' value='2+'>2+ &nbsp;&nbsp;&nbsp;
                <input type='radio' name='results_B' class='results_B'  value='3+'>3+
            </td>
        </tr>
        <tr>
            <td style='text-align: center;'>".$item_info."</td>
            <td style='text-align: center; font-weight: bold;'>Xpert MTB/RIF</td>
            <td><input type='text' style='width: 100;' name='xpert_receiver' id='xpert_receiver' value='$Receiver_Name' readonly='readonly'></td>
            <td><input type='text' style='width: 100; border: 2px solid #000 !important;' name='xpert_apperance' id='xpert_apperance'></td>
            <td>
                <input type='radio' name='xpert_results' class='xpert_results' value='N#'>N# &nbsp;&nbsp;&nbsp;&nbsp;
                <input type='radio' name='xpert_results' class='xpert_results' value='T#'>T# &nbsp;&nbsp;&nbsp;&nbsp;
                <input type='radio' name='xpert_results' class='xpert_results' value='TI#'>TI# &nbsp;&nbsp;&nbsp;
                <input type='radio' name='xpert_results' class='xpert_results' value='RR#'>RR# &nbsp;&nbsp;&nbsp;
                <input type='radio' name='xpert_results' class='xpert_results'  value='I#'>I#
            </td>
        </tr> 
        <tr> 
            <td colspan='5'><hr>
        </tr>
        <tr>
            <td colspan='5'><span style='font-weight: bold;'>** Visual appearance of Sputum (Blood Stained, Mucous, Mucopurulent, Salivary)</td>
        </tr>
        <tr>
            <td colspan='5'><span style='font-weight: bold;'># N = MTB not Detected; T = MTB Detected rifampicin resistance not detected; <b>RR</b> = MTB Detected rifampicin resistance detected; <b>TI</b> = MTB Detected rifampicin resistance Indeterminate; <b>I</b> = Error/ No results/ Invalid</td>
        </tr>
        <tr>
        <td colspan='5'><hr></td>
        </tr>
    </table>
    <table width='100%' class='art-article' style='font-size:18px; background-color:#fff;'>
        <tr>
            <td colspan='2' style='text-align: center;'><span style='font-weight: bold;'>Skin smear Results (To be Completed in Laboratory)</td>
        </tr>
        <tr>
            <th>Ear Lobe</th>
            <th>Lesion</th>
        </tr>
        <tr>
            <td><input type='text' style='width: 100; border: 2px solid #000 !important;' name='ear_lobe' id='ear_lobe'></td>
            <td><input type='text' style='width: 100; border: 2px solid #000 !important;' name='lesion' id='lesion'></td>
        </tr>
        <tr>
            <td colspan='2'>Comments<td>
        </tr>
        <tr>
            <td colspan='2'><textarea name='comments' id='comments' cols='30' rows='2' style='border: 2px solid #000 !important;'></textarea><td>
        </tr>
        <tr>
            <td colspan='2' style='text-align: center;'><input type='button' class='art-button-green' value='SAVE RESULTS' onclick='save_tb_results(\"$Payment_Item_Cache_List_ID\",\"$id\",\"$Employee_ID\")'></textarea><td>
        </tr>";
        

        ?>
    </table>
    </fieldset>    

        <?php 
        }
    }else{
                echo "<fieldset><h3 style='color:red'>NO RESULT</h3></fieldset>";
            }
    }
    ?>
    <script>
    function save_tb_results(Payment_Item_Cache_List_ID,id,Employee_ID){
        var comments = document.getElementById('comments').value;
        var lesion = document.getElementById('lesion').value;
        var ear_lobe = document.getElementById('ear_lobe').value;
        var xpert_results = $('.xpert_results:checked').val();
        var xpert_apperance = document.getElementById('xpert_apperance').value;
        var xpert_receiver = document.getElementById('xpert_receiver').value;
        var results_B = $('.results_B:checked').val();
        var receiver_B = document.getElementById('receiver_B').value;
        var apperance_B = document.getElementById('apperance_B').value;
        var results_A = $('.results_A:checked').val();
        var apperance_A = document.getElementById('apperance_A').value;
        var receiver_A = document.getElementById('receiver_A').value;
        var lab_serial = document.getElementById('lab_serial').value;
        var tb_screen_ID = '<?= $tb_screen_ID ?>';
        if(confirm("Are you sure you want to save TB Results for this Patient?")){
                    $.ajax({
                    type:'POST',
                    url:'save_tb_results.php',
                    data:{bt_screenig:'yes',Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,comments:comments,lesion:lesion,ear_lobe:ear_lobe,xpert_results:xpert_results,xpert_apperance:xpert_apperance,xpert_receiver:xpert_receiver,results_B:results_B,receiver_B:receiver_B,apperance_B:apperance_B,results_A:results_A,apperance_A:apperance_A,results_A:results_A,apperance_A:apperance_A,receiver_A:receiver_A,id:id,tb_screen_ID:tb_screen_ID,Employee_ID:Employee_ID,lab_serial:lab_serial},
                    success:function(data){
                        alert(data)
                    }
                });
            }
    }
    </script>
