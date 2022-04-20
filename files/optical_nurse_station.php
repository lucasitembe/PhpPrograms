<?php
include("./includes/header.php");
include("./includes/connection.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    
}
if (isset($_GET['consultation_ID'])) {
    $consultation_ID = $_GET['consultation_ID'];
} else {
    $consultation_ID = 0;
}


if (isset($_GET['Admision_ID'])) {
    $Admision_ID = $_GET['Admision_ID'];
} else {
    $Admision_ID = 0;
}
// if (isset($_GET['Patient_Payment_Item_List_ID'])) {
//     $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
// } else {
//     $Patient_Payment_Item_List_ID = 0;
// }

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}
if (isset($_SESSION['userinfo'])) {
//    if (isset($_SESSION['userinfo']['Nurse_Station_Works'])) {
//        if ($_SESSION['userinfo']['Nurse_Station_Works'] != 'yes') {
//            header("Location: ./index.php?InvalidPrivilege=yes");
//        }
//    } else {
//        header("Location: ./index.php?InvalidPrivilege=yes");
//    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>
 <?php
         $select_patien_details = mysqli_query($conn,"
		SELECT Patient_Name, Registration_ID,Gender,Date_Of_Birth
			FROM
				tbl_patient_registration pr
			WHERE
				pr.Registration_ID = '" . $Registration_ID . "'
				") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_patien_details);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_patien_details)) {
            $Patient_Name = $row['Patient_Name'];
            $Registration_ID = $row['Registration_ID'];
            $Gender = $row['Gender'];
            $DOB = $row['Date_Of_Birth'];
        }
    } else {
        $Guarantor_Name  = '';
        $Member_Number = '';
        $Patient_Name = '';
        $Gender = '';
        $Registration_ID = 0;
    }
    $age = date_diff(date_create($DOB), date_create('today'))->y;
    // $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
    $optical=mysqli_query($conn,"SELECT optical_clinic_ID, VA_RE, VA_LE, Employee_ID, VA_WPIN_RE, VA_WPIN_LE, IOP_RE, IOP_LE, trainee, VA_GLASSES_RE, VA_GLASSES_LE, date_exam, return_date, picture_note, diabetes, Registration_ID, optical_image, filled_status, a_scan, k_scan FROM optical_clinic WHERE Registration_ID='$Registration_ID' and date(date_exam)=CURRENT_DATE");
	while($data=mysqli_fetch_assoc($optical)){
	 $VA_RE=$data['VA_RE'];
	 $VA_LE=$data['VA_LE'];
	 $VA_WPIN_RE=$data['VA_WPIN_RE'];
	 $VA_WPIN_LE=$data['VA_WPIN_LE'];
	 $IOP_RE=$data['IOP_RE'];
	 $IOP_LE=$data['IOP_LE'];
	 $trainee=$data['trainee'];
	 $VA_GLASSES_RE=$data['VA_GLASSES_RE'];
	 $VA_GLASSES_LE=$data['VA_GLASSES_LE'];
	 $date_exam=$data['date_exam'];
	 $diabetes=$data['diabetes'];
	 $a_scan=$data['a_scan'];
	 $k_scan=$data['k_scan'];
	}

        ?>
        <a  href="nursecommunicationpage.php?Registration_ID=<?php echo $Registration_ID; ?>&consultation_ID=<?php echo  $consultation_ID; ?>&Admision_ID=<?php echo $Admision_ID; ?>"     class='art-button-green' >
            BACK
         </a>
        <center>
<fieldset style='height: 600px;overflow-y:scroll;width:96%;' align="center">
    <legend align="center" style="text-align:center"><b>OPTICAL FORM NURSE STATION</b>
        <br/>
        <span style='color:yellow'><?php echo "<b>" . $Patient_Name . "</b>  | " . $Gender . "</b> | <b>" . $age . " years</b> | <b>" .  $Gender . "</b>"; ?></b></span>
    </legend>
    
    <html>
<head>
    <style>
        input[type="checkbox"]{
            width: 30px; 
            height: 30px;
            cursor: pointer;
    }
    </style>
</head>

<body>
    
    <div style="margin:0px;display:grid;grid-template-columns:1fr; gap:1em;text-align:right;">
        <div class="one">
            <input type="button" id="Previous_Records" name="Previous_Records" class="art-button-green" onclick="Previous_Records1();" value="Previous Records">
        </div>
    </div>
    <div style="margin:0px;display:grid;grid-template-columns:1fr 1fr; gap:1em">
        <div class="one">
        <input type="hidden" id="Patient_Payment_ID" name="Patient_Payment_ID" value="<?php echo $Patient_Payment_ID; ?>">
        <input type="hidden" id="Patient_Payment_Item_List_ID" name="Patient_Payment_Item_List_ID" value="<?php echo $Patient_Payment_Item_List_ID; ?>">
        </div>
    </div>

    <table class="table table-striped" style="margin-top:25px;">
        <thead class='thead-dark'>
            <tr>
                <th> PROCEDURE</th>
                <th> RE</th>
                <th> LE</th>
            </tr>
        </thead>
        <div id="take">
        <tbody>
            <tr>
                <td>VA</td>
                <td><input type='text' id='VA_RE' name='VA_RE' class="form-control" value="<?php echo $VA_RE;?>"></td>
                <td><input type='text' id='VA_LE' name='VA_LE' class="form-control" value="<?php echo $VA_LE;?>"></td>
            </tr>
            <tr>
                <td>VA W/PIN</td>
                <td><input type='text' id='VA_WPIN_RE' name='VA_WPIN_RE' class="form-control" value="<?php echo $VA_WPIN_RE;?>"></td>
                <td><input type='text' id='VA_WPIN_LE' name='VA_WPIN_LE' class="form-control" value="<?php echo $VA_WPIN_LE;?>"></td>
            </tr>
            <tr>
                <td>VA W/GLASSES</td>
                <td><input type='text' id='VA_WGLASSES_RE' name='VA_WGLASSES_RE' class="form-control" value="<?php echo $VA_GLASSES_RE;?>"></td>
                <td><input type="text" id="VA_WGLASSES_LE" name="VA_WGLASSES_LE" class="form-control" value="<?php echo $VA_GLASSES_LE;?>"></td>

            </tr>
            <tr>
                <td>IOP</td>
                <td><input type="text" id="IOP_RE"  name="IOP_RE" class="form-control" value="<?php echo $IOP_RE;?>"></td>
                <td><input type="text" id="IOP_LE" name="IOP_LE" class="form-control" value="<?php echo $IOP_LE;?>"></td>
            </tr>
            <tr>
                <td>A-SCAN</td>
                <td colspan="2"><textarea class="FORM-CONTROL" id="a_scan"><?php echo $a_scan;?></textarea></td>
            </tr>
            <tr>
                <td>K-SCAN</td>
                <td colspan="2"><textarea class="FORM-CONTROL" id="k_scan"><?php echo $k_scan;?></textarea></td>
            </tr>
            </tbody>
            <!-- </div> -->
    </table>
    <tr>
        <td><input type="button" value="Save" class="btn btn-primary  btn-block" name="btn_add" onclick="save_data()"></td>

    </tr>
    <div id="result"></div>
    <div id="result2"></div>
    <input type="hidden" id="Registration_ID" value="<?php echo $Registration_ID?>">
    <input type="hidden" id="Patient_Payment_ID" value="<?php echo $Patient_Payment_ID?>">
    <input type="hidden" id="Patient_Payment_Item_List_ID" value="<?php echo $Patient_Payment_Item_List_ID?>">
    <input type="hidden" class="Registration_ID" name="Registration_ID" value="<?php echo $Registration_ID?>">
    
  

   
    <div id="result"></div>
</fieldset>
</center>
<script>
    function save_data(){
        var Registration_ID = $(".Registration_ID").val();
        var Registration_ID1 = $(".Registration_ID").val();
        var VA_RE=$("#VA_RE").val();
        var VA_LE=$("#VA_LE").val();
        var VA_WPIN_RE=$("#VA_WPIN_RE").val();
        var VA_WPIN_LE=$("#VA_WPIN_LE").val();
        var IOP_LE=$("#IOP_LE").val();
        var IOP_RE=$("#IOP_RE").val();
        var VA_WGLASSES_LE=$("#VA_WGLASSES_LE").val();
        var VA_WGLASSES_RE=$("#VA_WGLASSES_RE").val();
        var k_scan=$("#k_scan").val();
        var a_scan=$("#a_scan").val();
         //alert(Registration_ID+'==='+Registration_ID1);
        if(VA_LE !='' || VA_RE !='' || VA_WPIN_RE !='' || VA_WPIN_LE !='' || IOP_LE !='' || IOP_RE !='' || VA_WGLASSES_LE !='' || VA_WGLASSES_RE){
            if(confirm("Are you Sure you want to Save")){
                $.ajax({
                type:'post',
                url: 'save_optical_nurse.php',
                data : {
                    VA_RE:VA_RE,
                    VA_LE:VA_LE,
                    VA_WPIN_RE:VA_WPIN_RE,
                    VA_WPIN_LE:VA_WPIN_LE,
                    VA_WGLASSES_LE:VA_WGLASSES_LE,
                    VA_WGLASSES_RE:VA_WGLASSES_RE,
                    IOP_RE:IOP_RE,
                    IOP_LE:IOP_LE,
                    Registration_ID:Registration_ID,
                    k_scan:k_scan,
                    a_scan:a_scan
                },
                success : function(response){
                    // $("#return_date").val('');
                    // $("#trainee").val('');
                    // $("#diabetes").val('');
                    // $("#VA_RE").val('');
                    // $("#VA_LE").val('');
                    // $("#VA_WPIN_RE").val('');
                    // $("#VA_WPIN_LE").val('');
                    // $("#IOP_LE").val('');
                    // $("#IOP_RE").val('');
                    // $("#VA_WGLASSES_LE").val('');
                    // $("#VA_WGLASSES_RE").val('');
                    // $("#k_scan").val('');
                    // $("#a_scan").val('');

                }
            });
            
            }
        }
        else{
            alert("Please fill atleast one of the field above");
        }   
    }
    function Previous_Records1(){
        var Registration_ID = $(".Registration_ID").val();
         //alert(Registration_ID);
        $.ajax({
                type:'post',
                url: 'optical_section_previous_result.php',
                data : {
                     Registration_ID:Registration_ID
               },
               success : function(data){
                $('#result').html(data);
                    $('#result').dialog({
                        autoOpen:true,
                        width:'85%',
                        
                        position: ['center',0],
                        title:'PATIENT RECORD:',
                        modal:true
                    });  
                    // $('#result').html(data);
               }
           });
     }
</script>
<!-- End of script of BMI -->	

<script type="text/javascript" language="javascript">

</script>


<?php
include("./includes/footer.php");
?>