<?php
include("./includes/header.php");
include("./includes/connection.php");
?>

<script language="javascript" type="text/javascript">
    function searchEmployee(Employee_Name) {
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=430px src='editEmployeeIframe.php?Employee_Name=" + Employee_Name + "'></iframe>";
    }
</script>
<input type="button" value="BACK" onclick="history.go(-1)"  class="art-button-green" >
<br/><br/>
<style>
    .rows_list{
        cursor: pointer;
    }
    .rows_list:active{
        color: #328CAF!important;
        font-weight:normal!important;
    }
    .rows_list:hover{
        color:#00416a;
        background: #dedede;
        font-weight:bold;
    }
    a{
        text-decoration: none;
    }
</style>
<fieldset>
    <center>
        <table class="table">
            <tr>
                <td>
                    <input type="text" style="width: 10%; text-align:center;" placeholder="Start Date" id='start_date' />
                    <input type="text" style="width: 10%; text-align:center;" placeholder="End Date" id='end_date'/>
                    <b>Report Type</b>
                    <select style="width: 5%;" id="reporttype" onchange="reportChange(this);">
                        <option>OPD</option>
                        <option>IPD</option>
                    </select>
                    <b>Y - Axis</b>
                    <select style="width: 8%;" id="y_axis">
                    </select>
                  
                    <b>Filter Clinic</b>
                    <select style="width: 12%;" id="clinics" class="foropd">
                        <option value="">All</option>
                        <?php
                        $query = mysqli_query($conn, "SELECT Clinic_Name, Clinic_ID FROM tbl_clinic order by Clinic_Name") or die(mysqli_error($conn));
                        while ($rows = mysqli_fetch_assoc($query)) {
                            echo ' <option value="' . $rows['Clinic_ID'] . '">' . $rows['Clinic_Name'] . '</option>';
                        }
                        ?>
                    </select>
                    
                    <b>Visit Type</b>                            
                    <select style="width: 10%;" id="visittype" class="foropd">
                        <option value="">All</option>
                        <option value="new">New</option>
                        <option value="return">Return</option>
                        <option value="referral">Referral</option>
                    </select>
                     <b>Address</b>

                    <?php
                    //get initial region
                    $Control_Region = 'yes';
                    $slct = mysqli_query($conn,"select Region_ID, Region_Name from tbl_regions where Region_Status = 'Selected'") or die(mysqli_error($conn));
                    $num = mysqli_num_rows($slct);
                    if ($num > 0) {
                        while ($data = mysqli_fetch_array($slct)) {
                            $Selected_Region = $data['Region_Name'];
                            $Region_ID = $data['Region_ID'];
                        }
                    } else { // select Dar es salaam
                        $Control_Region = 'no';
                        $Selected_Region = 'Dar es salaam';
                    }
                    ?>
                    
                    <select name='region' id='region' onchange='getDistricts()'>
                        <option selected='selected'><?php echo $Selected_Region; ?></option>
                        <option value="">All</option>
                        <?php
                        $data = mysqli_query($conn,"select * from tbl_regions where Region_Status = 'Not Selected' and Country_ID = (select Country_ID from tbl_regions where Region_Status = 'Selected')");
                        while ($row = mysqli_fetch_array($data)) {
                            ?>
                            <option value='<?php echo $row['Region_Name']; ?>'>
                                <?php echo $row['Region_Name']; ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>   
                    <b>District</b>
                    <select name='District' id='District' required='required'   required>
                    <option selected='selected'></option>
                    
                    <?php if ($Control_Region == 'no') { ?>
                        <option>Kinondoni</option>
                        <option>Ilala</option>
                        <option>Temeke</option>
                        <?php
                    } else {
                        $select_districts = mysqli_query($conn,"select District_Name from tbl_district where Region_ID = '$Region_ID'") or die(mysqli_error($conn));
                        $num_districts = mysqli_num_rows($select_districts);
                        if ($num_districts > 0) {
                            while ($dt = mysqli_fetch_array($select_districts)) {
                                ?>
                                <option><?php echo $dt['District_Name']; ?></option>
                                <?php
                            }
                        }
                    }
                    ?>
                </select>       
               
            <br/>
                
                
                <b>Filter Ward</b>
                            
                    <select style="width: 15%;" id="wards" class="foripd">
                        <option value="">All</option>
                        <?php
                        $query = mysqli_query($conn, "select Hospital_Ward_Name, Hospital_Ward_ID from tbl_hospital_ward order by Hospital_Ward_Name") or die(mysqli_error($conn));
                        while ($rows = mysqli_fetch_assoc($query)) {
                            echo ' <option value="' . $rows['Hospital_Ward_ID'] . '">' . $rows['Hospital_Ward_Name'] . '</option>';
                        }
                        ?>
                    </select>
                    <b>IPD Status</b>                            
                    <select style="width: 10%;" id="ipdstatus" class="foripd">
                        <option value="">All</option>
                        <option value="admitted">Admitted</option>
                        <option value="normal">Normal Discharge</option>
                        <option value="Absconded">Absconded</option>
                        <option value="Refferal">Referral</option>
                        <option value="Death">Death</option>
                        <option value="transferin">Transfer In</option>
                        <option value="transferout">Transfer Out</option>
                    </select>
                    <b>Age Range</b>
                    <input type="number" style="width: 10%;" id='lowerage' placeholder="Minimum age" />
                    <input type="number" style="width: 10%;" id='higherage' placeholder="Maxmum age"/>
                        
                        <select id='agetype' style='text-align:center;padding:4px; width:10%;display:inline'>
                            <option value='YEAR'>Year</option>
                            <option value='MONTH'>Month</option>
                            <option value='DAY'>Days</option>
                        </select>
                        
                    <input type="button" value="PDF" onclick="medical_report_pdf()" class="art-button-green"/>
                    <input type="button" value="EXCEL" onclick="medical_report_excel()" class="art-button-green"/>
               
                    <input type="button" value="FILTER" onclick="filter_report()" class="art-button-green"/>
                  
                </td>
            </tr>
        </table>
        <table style="width:90%">
            <tr>
                <td style="width: 90%;"><canvas id="myChart" ></canvas></td>
                <!-- <td style="width:40%">
                    <table style="width:100%;">
                        <tr>
                            <td><b>Report Type</b></td>
                            <td colspan="3">
                                <select style="width: 100%;" id="reporttype" onchange="reportChange(this);">
                                    <option>OPD</option>
                                    <option>IPD</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Y - Axis</b></td>
                            <td colspan="3">
                                <select style="width: 100%;" id="y_axis">
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Filter Clinic</b></td>
                            <td colspan="3">
                                <select style="width: 100%;" id="clinics" class="foropd">
                                    <option value=""></option>
                                    <?php
                                    $query = mysqli_query($conn, "SELECT Clinic_Name, Clinic_ID FROM tbl_clinic order by Clinic_Name") or die(mysqli_error($conn));
                                    while ($rows = mysqli_fetch_assoc($query)) {
                                        echo ' <option value="' . $rows['Clinic_ID'] . '">' . $rows['Clinic_Name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Filter Address</b></td>
                            <td colspan="3">
                                <select style="width: 100%;" id="clinics" class="foropd">
                                    <option value=""></option>
                                    <option value="District">District</option>
                                    <option value=""></option>
                                    <option value=""></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Visit Type</b></td>
                            <td colspan="3">
                                <select style="width: 100%;" id="visittype" class="foropd">
                                    <option value=""></option>
                                    <option value="new">New</option>
                                    <option value="return">Return</option>
                                    <option value="referral">Referral</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Filter Ward</b></td>
                            <td colspan="3">
                                <select style="width: 100%;" id="wards" class="foripd">
                                    <option value=""></option>
                                    <?php
                                    $query = mysqli_query($conn, "select Hospital_Ward_Name, Hospital_Ward_ID from tbl_hospital_ward order by Hospital_Ward_Name") or die(mysqli_error($conn));
                                    while ($rows = mysqli_fetch_assoc($query)) {
                                        echo ' <option value="' . $rows['Hospital_Ward_ID'] . '">' . $rows['Hospital_Ward_Name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><b>IPD Status</b></td>
                            <td colspan="3">
                                <select style="width: 100%;" id="ipdstatus" class="foripd">
                                    <option value="admitted"></option>
                                    <option value="admitted">Admitted</option>
                                    <option value="normal">Normal Discharge</option>
                                    <option value="Absconded">Absconded</option>
                                    <option value="Refferal">Referral</option>
                                    <option value="Death">Death</option>
                                    <option value="transferin">Transfer In</option>
                                    <option value="transferout">Transfer Out</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Date Range</b></td>
                            <td><input type="text" style="width: 100%;" id='start_date' /></td>
                            <td><input type="text" style="width: 100%;" id='end_date'/></td>
                        </tr>
                        <tr>
                            <td><b>Age Range</b></td>
                            <td><input type="number" style="width: 100%;" id='lowerage' placeholder="Minimum age" /></td>
                            <td><input type="number" style="width: 100%;" id='higherage' placeholder="Maxmum age"/></td>
                            <td> 
                                <select id='agetype' style='text-align:center;padding:4px; width:100%;display:inline'>
                              <option value='all'>ALL</option>-->
                      <!--                <option value='YEAR'>Year</option>
                                    <option value='MONTH'>Month</option>
                                    <option value='DAY'>Days</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </td> -->
            </tr>
        </table>
    </center>
    <!-- <center>
        <table>
            <tr>
                <td>
                    <input type="button" value="PDF" onclick="medical_report_pdf()" class="art-button-green"/>
                </td>
                <td>
                    <input type="button" value="EXCEL" onclick="medical_report_excel()" class="art-button-green"/>
                </td>
                <td>
                    <input type="button" value="FILTER" onclick="filter_report()" class="art-button-green"/>
                </td>
            </tr>
        </table>
    </center> -->
</fieldset>
<table style="width:100%;background: whitesmoke">
    <tr><td colspan="2" id="progressbar"></td></tr>
    <tr>
        <td>
            <div class="box box-primary" style="height:400px;overflow-y: scroll;overflow-x: hidden">
                <table class="table">
                    <tr>
                        <td style="width:4%"><b>Row</b></td>
                        <td style="width: 60%"><b>Y-axis</b></td>
                        <td style="width: 12%"><b>Male</b></td>
                        <td style="width: 12%"><b>Female</b></td>
                        <td style="width: 12%"><b>Total</b></td>
                    </tr>
                    <tbody id='dashboard_data'>

                    </tbody>
                </table>
            </div>
        </td>
    </tr>
</table>
<script src="css/jquery.js"></script>
<script src="js/Chart.min.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
     $('#start_date').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en',
    startDate: 'now'
    });
    $('#start_date').datetimepicker({value: '', step: 1});
    $('#end_date').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en',
    startDate: 'now'
    });
    $('#end_date').datetimepicker({value: '', step: 1});

    $(".foripd").attr('disabled','disabled');
    $(".foropd").removeAttr('disabled');
function reportChange(obj){
    if(obj.value === "OPD"){
        $(".foripd").attr('disabled','disabled');
        $(".foropd").removeAttr('disabled');
        
    }else if(obj.value === "IPD"){
        $(".foropd").attr('disabled','disabled');
        $(".foripd").removeAttr('disabled');
    }else{
        $(".foripd").attr('disabled','disabled');
        $(".foropd").removeAttr('disabled');
    }
    changeReport();
}

function changeReport(){
    var report = $("#reporttype").val();
    if(report == 'OPD'){
        $("#y_axis").html("<option></option><option>Medication</option><option>Diagnosis</option><option>Clinic</option>");
    }else{
        $("#y_axis").html("<option>Ward</option>");
    }
}
changeReport();

  function filter_report(){
      var start_date = $("#start_date").val();
      var end_date = $("#end_date").val();
      var y_axis = $("#y_axis").val();
      var clinics = $("#clinics").val();
      var visittype = $("#visittype").val();
      var lowerage = $("#lowerage").val();
      var higherage = $("#higherage").val();
      var ipdstatus = $("#ipdstatus").val();
      var wards = $("#wards").val();
      var agetype =$("#agetype").val();
      var region = $("#region").val();
     
      var District = $("#District").val(); 
      if(lowerage=='' || higherage==''){
            alert("Please Enter Age range");
            $("#higherage").css("border", "2px solid red");
            $("#lowerage").css("border", "2px solid red");
           exit();
        }else{
            $("#higherage").css("border", "2px solid green");
            $("#lowerage").css("border", "2px solid green");
        }

        if(start_date=='' || end_date==''){
            alert("Please Enter date range");
            $("#end_date").css("border", "2px solid red");
            $("#start_date").css("border", "2px solid red");
           exit();
        }else{
            $("#end_date").css("border", "2px solid green");
            $("#start_date").css("border", "2px solid green");
        }

      document.getElementById('progressbar').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
      
      $.ajax({
        type:'POST',
        url:'ajax_medical_dashboard.php',
        data:{start_date:start_date,region:region,District:District,end_date:end_date,y_axis:y_axis,agetype:agetype,lowerage:lowerage,higherage:higherage,clinics:clinics,
            visittype:visittype,wards:wards,ipdstatus:ipdstatus
        },
        success:function(data){
            var myObj = JSON.parse(data);
            graph_chart(myObj.x_axis,myObj.y_axis);
            $("#dashboard_data").html(myObj.table_data);
            document.getElementById('progressbar').innerHTML = "";
            console.log(myObj.query);
        }
    });
  }
  
  function medical_report_pdf(){
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        var y_axis = $("#y_axis").val();
        var clinics = $("#clinics").val();
        var visittype = $("#visittype").val();
        var lowerage = $("#lowerage").val();
        var higherage = $("#higherage").val();
        var ipdstatus = $("#ipdstatus").val();
        var wards = $("#wards").val();
        var report = $("#reporttype").val();
        var agetype =$("#agetype").val();
        var region = $("#region").val();
        var District = $("#District").val();
        if(lowerage=='' || higherage==''){
            alert("Please Enter Age range");
            $("higherage").css("border", "2px solid red");
            $("lowerage").css("border", "2px solid red");
            exit();
        }
        if(start_date=='' || end_date==''){
            alert("Please Enter date range");
            $("#end_date").css("border", "2px solid red");
            $("#start_date").css("border", "2px solid red");
           exit();
        }else{
            $("#end_date").css("border", "2px solid green");
            $("#start_date").css("border", "2px solid green");
        }
        window.open("medical_report_pdf.php?start_date="+start_date+"&end_date="+end_date+"&agetype="+agetype+"&y_axis="+y_axis+"&lowerage="+lowerage+"&higherage="+higherage+"&clinics="+clinics+"&visittype="+visittype+"&wards="+wards+"&ipdstatus="+ipdstatus+"&report="+report+"&District="+District+"&region="+region,"_blank");
    }
  function medical_report_excel(){
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        var y_axis = $("#y_axis").val();
        var clinics = $("#clinics").val();
        var visittype = $("#visittype").val();
        var lowerage = $("#lowerage").val();
        var higherage = $("#higherage").val();
        var ipdstatus = $("#ipdstatus").val();
        var wards = $("#wards").val();
        var report = $("#reporttype").val();
        var agetype =$("#agetype").val();
        var region = $("#region").val();
        var District = $("#District").val();
        if(lowerage=='' || higherage==''){
            alert("Please Enter Age range");
            $("higherage").css("border", "2px solid red");
            $("lowerage").css("border", "2px solid red");
            exit();
        }
        if(start_date=='' || end_date==''){
            alert("Please Enter date range");
            $("#end_date").css("border", "2px solid red");
            $("#start_date").css("border", "2px solid red");
           exit();
        }else{
            $("#end_date").css("border", "2px solid green");
            $("#start_date").css("border", "2px solid green");
        }
        window.open("medical_report_excel.php?start_date="+start_date+"&end_date="+end_date+"&agetype="+agetype+"&y_axis="+y_axis+"&lowerage="+lowerage+"&higherage="+higherage+"&clinics="+clinics+"&visittype="+visittype+"&wards="+wards+"&ipdstatus="+ipdstatus+"&report="+report+"&District="+District+"&region="+region,"_blank");
    }
</script>
<script>
    function graph_chart(x_axis,y_axis){
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: x_axis,
                datasets: [{
                    label: '# Total Patient: ',
                    data: y_axis,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    }
    graph_chart([],[]);
</script>
<script>
function getDistricts() {
        var Region_Name = document.getElementById("region").value;
        console.log(Region_Name);
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
        mm.open('GET', 'GetDistricts.php?Region_Name=' + Region_Name+'&From=GetDistricts', true);
        mm.send();
        $('#District').val("");
      
    }



    // function Wards() {
    //     var data = mm.responseText;
    //     document.getElementById('select-ward').innerHTML = data;
    // }
    function AJAXP() {
        var data = mm.responseText;
        document.getElementById('District').innerHTML = data;
    }
</script>
<?php
include("./includes/footer.php");
?>