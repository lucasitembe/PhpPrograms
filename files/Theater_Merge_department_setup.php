<?php
    include("includes/header.php");
    include("includes/Surgery.Mode.php");
    
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];

    $response = json_decode(GetSubdepartmentForSurgery($conn,'Pharmacy','active'),true);
    // print_r($response);

?>
<a href="Theater_setup_Menu.php?TheaterSetup=Setup&theater=yes" class="art-button-green">BACK</a>

<br/>
<br/>

<fieldset>
    <legend align=center>SURGICAL STORE DEPARTMENT MANAGEMENT</legend>
    <div style="width: 49%; float: left;">
        <select name="SubDepartment" id="SubDepartment" style="width: 100%;">
            <?php 
                echo $display;
            ?>
        </select>
        <br><br>
        <div class="box box-primary" style="height: 535px;overflow-y: scroll;overflow-x: hidden">
            <table class="table table-collapse table-striped " style="border-collapse: collapse !important; width: 100% !important;">
                <tr>
                    <th style='width: 4%;'>ACTION</th>
                    <th>STORE/PHARMACY DEPARTMENT NAME</th>
                    <th>DEPARTMENT</th>
                </tr>
                <?php
                    foreach($response as $dept) :
                        $Department_Name = $dept['Department_Name'];
                        $Sub_Department_ID = $dept['Sub_Department_ID'];
                        $Sub_Department_Name = $dept['Sub_Department_Name'];
                        echo "
                            <tr>
                                <td style='text-align: center;'><input type='radio' name='v' value='".$Sub_Department_ID."' onclick='Add_Subdept(".$Sub_Department_ID.")' id='Subdept".$Sub_Department_ID."'></td>
                                <td>".$Sub_Department_Name."</td>
                                <td>".$Department_Name."</td>
                            </tr>";
                    endforeach;
                ?>
            </table>
        </div>
    </div>
    <div style="width: 49%; float: right;">
        <h2>Merged Surgical Departments</h2>
        <div class="box box-primary" style="height: 540px;overflow-y: scroll;overflow-x: hidden">
            <table class="table table-collapse table-striped " style="border-collapse: collapse !important; width: 100% !important;">
                <tr>
                    <th style='width: 5%;text-align: left;'>SN</th>
                    <th style='text-align: left;'>SURGICAL LOCATION/THEATRE</th>
                    <th style='text-align: left;'>STORE/PHARMACY DEPARTMENT NAME</th>
                    <th style='text-align: left;'>ACTION</th>
                </tr>
                <tbody id='Search_Iframe'>
            </table>
        </div>
    </div>
</fieldset>
<br>
<br>
<?php
include("includes/footer.php");
?>

<link rel="stylesheet" href="css/uploadfile.css" media="screen">
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<link rel="stylesheet" href="js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />   
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script><script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery-ui.js"></script>
<script src="css/scripts.js"></script>
<script src="js/jquery.form.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<link rel="stylesheet" href="css/ui.notify.css" media="screen">
<script src="js/jquery.notify.min.js"></script> 
<script src="js/select2.min.js"></script>


<script>
    $(document).ready(function() {
        $("#SubDepartment").select2();
        Display_Merged_data();
    });

    function Add_Subdept(SubDepartment_ID){
        Theater_ID = $("#SubDepartment").val();
        Employee_ID = '<?= $Employee_ID ?>';
        $.ajax({
            type: "GET",
            url: "includes/Surgery.request.handle.php",
            data: {
                Theater_ID:Theater_ID,
                Store_ID:SubDepartment_ID,
                Employee_ID: Employee_ID
            },
            cache: false,
            success: function (response) {
                // if(response == 200){
                //     alert("Surgical Department Location Merged Sucessfully!");
                    Display_Merged_data();
                // }else{
                    
                // }
            }
        });
    }

    function Remove_Dept(Attachement_ID){
        $.ajax({
            type: "GET",
            url: "includes/Surgery.request.handle.php",
            data: {
                Attachement_ID:Attachement_ID
            },
            cache: false,
            success: function (response) {
                // if(response == 200){
                //     alert("Surgical Department Location Merged Sucessfully!");
                    Display_Merged_data();
                // }else{
                    
                // }
            }
        });
    }

    function Display_Merged_data() {
        Action = 'request';

        if (window.XMLHttpRequest) {
            myObjectPost = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPost = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPost.overrideMimeType('text/xml');
        }

        myObjectPost.onreadystatechange = function () {
            dataPost = myObjectPost.responseText;
            if (myObjectPost.readyState == 4) {
                document.getElementById('Search_Iframe').innerHTML = dataPost;
                // $("#Submit_data").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectPost.open('GET', 'fetch_Surgical_Merged_Dept.php?Action='+Action, true);
        myObjectPost.send();
    }
</script>