
<?php 
    include("./includes/connection.php");
    $Registration_ID=$_POST['Registration_ID'];
    $Patient_Payment_ID=$_POST['Patient_Payment_ID'];
    $Patient_Payment_Item_List_ID=$_POST['Patient_Payment_Item_List_ID'];

    $allegies=mysqli_query($conn,"SELECT Allegies,Special_Condition FROM tbl_nurse WHERE Registration_ID='$Registration_ID'");
	while($res=mysqli_fetch_assoc($allegies)){
	 $allege=$allege.' '.$res['Allegies'];
	 $Special_Condition=$Special_Condition.' '.$res['Special_Condition'];
    }
    // die("SELECT optical_clinic_ID, VA_RE, VA_LE, Employee_ID, VA_WPIN_RE, VA_WPIN_LE, IOP_RE, IOP_LE, trainee, VA_GLASSES_RE, VA_GLASSES_LE, date_exam, return_date, picture_note, diabetes, Registration_ID, optical_image, filled_status, a_scan, k_scan FROM optical_clinic WHERE Registration_ID='$Registration_ID' and date(date_exam)=CURRENT_DATE");
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
	
	if($allege=='' || $allege==null){
		$patient_allege='None';
	} else {
		$patient_allege=$allege;
    }
    if($Special_Condition=='' || $Special_Condition==null){
		$Special_Condition1='None';
	} else {
		$Special_Condition1=$Special_Condition;
	}
?>

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
            Return Date<td><input type='date' id='return_date' class="form-control" >
        </div>
        
        <div class="three">
        Assistant<input type="text" id="trainee" name="trainee" class="form-control" value="<?php echo $trainee; ?>">
        </div>
    </div>
    <div style="margin:0px;display:grid;grid-template-columns:1fr; gap:1em;margin-top:10px;">
        <!-- <div class="one">
            Pressure<td><input type='text' id='pressure' name='pressure' class="form-control" >
        </div> -->
        <!-- <div class="two">
        Hypertention <select id="hypertention" name="hypertention" class="form-control" >
                    <option value=""></option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                <select>   
        </div> -->
        <div class="three">
        Allergies<input type="text" id="allergies" name="allergies" class="form-control" value="<?php echo $patient_allege;?>" readonly>
        </div>
    </div>
    <div style="margin:0px;display:grid;grid-template-columns:1fr; gap:1em;margin-top:10px;">
        <div class="three">
        Pre Existing Condition<textarea class="form-control" readonly><?php echo $Special_Condition1;?></textarea>
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
                <td><input type='text' id='VA_RE' name='VA_RE' class="form-control"value="<?php echo $VA_RE; ?>"></td>
                <td><input type='text' id='VA_LE' name='VA_LE' class="form-control"value="<?php echo $VA_LE; ?>"></td>
            </tr>
            <tr>
                <td>VA W/PIN</td>
                <td><input type='text' id='VA_WPIN_RE' name='VA_WPIN_RE' class="form-control" value="<?php echo $VA_WPIN_RE; ?>"></td>
                <td><input type='text' id='VA_WPIN_LE' name='VA_WPIN_LE' class="form-control" value="<?php echo $VA_WPIN_RE; ?>"></td>
            </tr>
            <tr>
                <td>VA W/GLASSES</td>
                <td><input type='text' id='VA_WGLASSES_RE' name='VA_WGLASSES_RE' class="form-control" value="<?php echo $VA_GLASSES_RE; ?>"></td>
                <td><input type="text" id="VA_WGLASSES_LE" name="VA_WGLASSES_LE" class="form-control" value="<?php echo $VA_GLASSES_LE; ?>"></td>

            </tr>
            <tr>
                <td>IOP</td>
                <td><input type="text" id="IOP_RE"  name="IOP_RE" class="form-control" value="<?php echo $IOP_RE; ?>"></td>
                <td><input type="text" id="IOP_LE" name="IOP_LE" class="form-control" value="<?php echo $IOP_LE; ?>"></td>

            </tr>
            <tr>
                <form method="POST"  action="" enctype="multipart/form-data" autocomplete="off">
                    <td>NOTE(Captured by camera)</td>
                    <td colspan="2"><input type="file" id="file_input" class="file_input"  class="form-control" onchange="readURL(this);"  required></td>
                    <!-- <td ><input type="file" id="file-input"   class="form-control"  required multiple></td> -->
                    <!-- <td><div id="dynamicCheck">
                    <input type="button" value="Add" onclick="createNewElement()"; class="form-control"/> -->
                    <!-- </div> -->
                </form>
            </tr>
            <tr>
                <td>Draw eye Diagram</td>
                <td><a target="_blank" href="../optical_esign/signature2.php?Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID;?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>" class="art-button-green">TAKE PATIENT EYE IMAGE</a></td>

            </tr>
            <tr>
                <td></td>
                <td colspan="2"><img id="blah" src="#" alt="Your Image Here" /></td>
            </tr>
            </tbody>
            <!-- </div> -->
    </table>
    <tr>
        <td><input type="button" value="Save" class="btn btn-primary  btn-block" name="btn_add" onclick="upload_picture()"></td>

    </tr>
    <div id="result"></div>
    <div id="result2"></div>
    <input type="hidden" id="Registration_ID" value="<?php echo $Registration_ID?>">
    <input type="hidden" id="Patient_Payment_ID" value="<?php echo $Patient_Payment_ID?>">
    <input type="hidden" id="Patient_Payment_Item_List_ID" value="<?php echo $Patient_Payment_Item_List_ID?>">
    <input type="hidden" class="Registration_ID" name="Registration_ID" value="<?php echo $Registration_ID?>">
    
  
<script>
    function upload_picture(){
        var file_input= $("#file_input").val();
        var Registration_ID = $(".Registration_ID").val();
        var Registration_ID1 = $(".Registration_ID").val();
        var return_date=$("#return_date").val();
        var trainee=$("#trainee").val();
        //var diabetes=$("#diabetes").val();
        var VA_RE=$("#VA_RE").val();
        var VA_LE=$("#VA_LE").val();
        var VA_WPIN_RE=$("#VA_WPIN_RE").val();
        var VA_WPIN_LE=$("#VA_WPIN_LE").val();
        var IOP_LE=$("#IOP_LE").val();
        var IOP_RE=$("#IOP_RE").val();
        var VA_WGLASSES_LE=$("#VA_WGLASSES_LE").val();
        var VA_WGLASSES_RE=$("#VA_WGLASSES_RE").val();
        var fd = new FormData();
        var files = $('#file_input')[0].files[0];
        fd.append('file',files);

        $.ajax({
            url: 'ajax_upload_picture.php',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                if(response!=0){
                    save_data(response)
                }else{
                    //alert("Process Fail...Try again");
                    save_data(response)
                }
            },
        });
     }

   function save_data(file_input){
        var Registration_ID = $(".Registration_ID").val();
        var Registration_ID1 = $(".Registration_ID").val();
        var return_date=$("#return_date").val();
        var trainee=$("#trainee").val();
        //var diabetes=$("#diabetes").val();
        var VA_RE=$("#VA_RE").val();
        var VA_LE=$("#VA_LE").val();
        var VA_WPIN_RE=$("#VA_WPIN_RE").val();
        var VA_WPIN_LE=$("#VA_WPIN_LE").val();
        var IOP_LE=$("#IOP_LE").val();
        var IOP_RE=$("#IOP_RE").val();
        var VA_WGLASSES_LE=$("#VA_WGLASSES_LE").val();
        var VA_WGLASSES_RE=$("#VA_WGLASSES_RE").val();
        var Patient_Payment_ID=$("#Patient_Payment_ID").val();
        var Patient_Payment_Item_List_ID=$("#Patient_Payment_Item_List_ID").val();
         //alert(Registration_ID+'==='+Registration_ID1);
        if(VA_LE !='' || VA_RE !='' || VA_WPIN_RE !='' || VA_WPIN_LE !='' || IOP_LE !='' || IOP_RE !='' || VA_WGLASSES_LE !='' || VA_WGLASSES_RE){
            if(confirm("Are you Sure you want to Save")){
                $.ajax({
                type:'post',
                url: 'save_optical.php',
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
                    return_date:return_date,
                    trainee:trainee,
                    Patient_Payment_ID:Patient_Payment_ID,
                    Patient_Payment_Item_List_ID:Patient_Payment_Item_List_ID,
                    file_input:file_input


                },
                success : function(response){
                    //alert("data Successfully saved");
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
                    //$('#result').html(response);

                }
            });
            
            }
        }
        else{
            alert("Please fill atleast one of the field above");
        }   
    }
       
    </script>
    <script>
        function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#blah')
                    .attr('src', e.target.result)
                    .width(910)
                    .height(200);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>
    <script type="text/JavaScript">
    function createNewElement() {
        // First create a DIV element.
        var txtNewInputBox = document.createElement('div');
        // Then add the content (a new input box) of the element.

        txtNewInputBox.innerHTML = "<input type='file' id='files[]' class='notes' name='files[]' class='form-control' onchange='readURL(this);'  required>";
        // <input type='button' id='picture' onclick='picture_validate();'>
        // Finally put it where it is supposed to appear.
        document.getElementById("newElementId").appendChild(txtNewInputBox);
    }
    function picture_validate(){
        var files=[];
        var picture=document.getElementById('files[]');
        for(var i=0;i<picture.length;i++){
            var pic=picture[i];
            files.push(pic.value);  
        }
        // var notes=$(".notes").val();
        alert(files);
    }
</script>

 <script>
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
                    $('#result').html(data);
               }
           });
     }
 </script>
    <!--script suploadrc="ajax_opical.js"></script-->

    <body>

</html>