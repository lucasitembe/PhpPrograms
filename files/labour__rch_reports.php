
<?php
include("./includes/header.php");
include("./includes/connection.php");
$requisit_officer = $_SESSION['userinfo']['Employee_Name'];

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
/*
  if(isset($_SESSION['userinfo']))
  {
  if(isset($_SESSION['userinfo']['Rch_Works']))
  {
  if($_SESSION['userinfo']['Rch_Works'] != 'yes'){ header("Location: ./index.php?InvalidPrivilege=yes");}
  }else
  {
  header("Location: ./index.php?InvalidPrivilege=yes");
  }
  }else
  { @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes"); }

 */

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Rch_Works'] == 'yes') {
        echo "<a href='searchvisitorsWazazipatientlistforrch.php?section=Rch&RchWorks=RchWorksThisPage' class='art-button-green'>BACK</a>";
    }
}
?>


<!-- link menu -->

<!--<a href="searchvisitorsWatotopatientlistforrch.php?section=Rch&RchWorks=RchWorksThisPage" class="art-button-green" >BACK</a>-->





<fieldset style="margin-top:5px;"> 
    <legend style="background-color:#006400;color:white;padding:2px;" align="right"><b>TAARIFA YA MWEZI KUTOKA WODI YA WAZAZI</b></legend> 
    <div class="powercharts_body" style="height: 400px;overflow-x: hidden;overflow-y: auto">
        <!--
            </div>
        </fieldset>
                        
        -->

        <script type="text/javascript" src="min.js"></script>
        <script src="ui2\jquery-ui.js"></script>
        <link rel="stylesheet" type="text/css" href="ui2\jquery-ui.css">				
        <script type="text/javascript">

            //Saving to database
            $(function () {
                $("#ol").hide();
                //modal box

                $("#dialog-1").dialog({
                    autoOpen: false,
                });



                $("#hudhurio1").show();

                $("#hudhurio2").hide();

                $("#hudhurio3").hide();

                $("#hudhurio4").hide();

                $("#hudhurio5").hide();

                $("#modal-rch").hide();
                $("#modal-rchload").hide();

                $("#filt").click(function () {

                    var sel = $('#sel option:selected').val();



                    if ($('#date').val() == "") {
                        var err2 = 1;

                        $('#date').css({"border-color": "red"});
                    } else {
                        var date = $('#date').val();
                        var err2 = 0;
                        $('#date').css({"border-color": "white"});
                    }

                    if ($('#date2').val() == "") {
                        var err3 = 1;
                        $('#date2').css({"border-color": "red"});
                    } else {
                        var date1 = $('#date2').val();
                        var err3 = 0;
                        $('#date2').css({"border-color": "white"});
                    }


                    if (err2 == 1 || err3 == 1) {



                        alert('Please fill all red areas!');

                        //code
                    } else {
                      
                        $("#modal-rchload").show();

                        $.ajax({
                            type: "POST",
                            url: 'labour_rch_reports_view.php',
                            data: "action=save&d=" + date + "&d2=" + date1 + "&sel=" + sel,
                            success: function (data) {
                                $("#ol").show();
                                $("#ol").html(data);
                                $("#modal-rchload").hide();
                            }
                        });


                    }



                });

            });
        </script>




        <div class="tabcontents" >

            <?php
            if (isset($_GET['ctcno'])) {

                $pn = $_GET['ctcno'];
                $select_Patient_Details = mysqli_query($conn,"
				select pr.Patient_Name,pr.Date_Of_Birth,pr.Member_Number,pr.Gender from
				    tbl_patient_registration pr
					where pr.registration_id ='$pn'") or die(mysqli_error($conn));

                //display all items
                while ($row2 = mysqli_fetch_array($select_Patient_Details)) {

                    $Today = Date("Y-m-d");
                    $Date_Of_Birth = $row2['Date_Of_Birth'];
                    $date1 = new DateTime($Today);
                    $date2 = new DateTime($Date_Of_Birth);
                    $diff = $date1->diff($date2);
                    $age = $diff->y;

                    $fname = explode(' ', $row2['Patient_Name'])[0];




                    $mname = '';

                    if (sizeof(explode(' ', $row2['Patient_Name'])) >= 3) {





                        $mname = explode(' ', $row2['Patient_Name'])[sizeof(explode(' ', $row2['Patient_Name'])) - 2];

                        $lname = explode(' ', $row2['Patient_Name'])[sizeof(explode(' ', $row2['Patient_Name'])) - 1];
                    } else {

                        $lname = explode(' ', $row2['Patient_Name'])[1];
                    }
                }
            }
            ?>

            <table width="100%" class="hiv_table" border="0"  > 
                <tr>
                    <td colspan="2">From<input id="date" type="text" style="width: 150px;" >&nbsp;To&nbsp;<input id="date2" type="text" style="width: 150px;" >&nbsp;<input id="filt" style="height:25px; " type="submit" value="Filter" class="art-button-green"> 

                        <input id="print_report" style="height:25px; " type="button" value="Print preview" class="art-button-green">
                    </td>   
                </tr>
            </table>


            <!-- HTML --> 
            <div id="dialog-1" title="Close CTC File" style="" >
                Choose file:
                <select id="sel">
                    <option value="none">Select from List</option>
                    <option value="new">New Visit</option>
                    <option value="re">Re-visit</option>
                    <option value="re">Re-visit</option>

                </select>
                <input type="submit" value="CloseFile">


            </div>

            <div id="ol" style="margin-top:20px;background-color: rgb(255,255,255)">		   

            </div>


            <!--Div ya pili------------------------------------------------------------->


            <div id="modal-rch" style="height:auto; width:auto;background-color:none">
                <b>Loarc.....</b>

            </div>

            <div id="modal-rchload" style="height:auto; width:auto;background-color:none">
                <b>Loading.....</b>

            </div>





    <?php
     include("./includes/footer.php");
    ?>
    <script>
        $('#print_report').click(function (){
            var umrichini=$('#umrichini').val();
            var umrizaidi=$('#umrizaidi').val();
            if(umrichini=='' || umrizaidi==''){
                alert('Jaza waliotarajiwa kujifungua katika eneo la huduma');
                return false;
            }
            var from_date = $('#date').val();
            var to_date = $('#date2').val();
            window.open('PrintLabour.php?from_date=' + from_date + '&to_date=' + to_date + '&umrichini='+umrichini+'&umrizaidi='+umrizaidi+'');
        });
    </script>