<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Dialysis_Works'])){
	    if($_SESSION['userinfo']['Dialysis_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }else{
			
			@session_start();
			if(!isset($_SESSION['Dialysis_Supervisor'])){ 
				header("Location: ./deptsupervisorauthentication.php?SessionCategory=Dialysis&InvalidSupervisorAuthentication=yes");
			}
					
            }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>


<a href='./dialysisSessionReport.php?src=dialysis' class='art-button-green'>BACK</a>


<center>

    <fieldset style="background-color:white">
        <legend align="center" style="background-color:#006400;color:white;padding:5px;"><b>HAEMODIALYSIS SESSION REPORT SETUP</b></legend> 
       
<style>
    .text-align{
        text-align:right!important;
    }
    .text-align-td{
        text-align:center!important;
    }
</style>
        <center>
            <table width=60% border=1>
                <tr>
                    <td>
                        <div id="" style="min-height: 400px;">
                            <?php if(isset($_GET['update_sessions'])){ ?>
                                <h5 class="text-align-td" >UPDATE HAEMODIALYSIS REPORT SESSIONS</h5><br>
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <!-- <th rowspan="2">#</th> -->
                                            <th rowspan="2" width="25%">SESSION</th>
                                            <th colspan="3">FROM</th>
                                            <th colspan="3">TO</th>
                                            <th rowspan="2">ACTION</th>
                                        </tr>
                                        <tr>
                                            <th>HH</th>
                                            <th>MM</th>
                                            <th>SS</th>
                                            <th>HH</th>
                                            <th>MM</th>
                                            <th>SS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $sql="SELECT * FROM tbl_dialysis_session_time_setup";
                                        $mysql_select=mysqli_query($conn,$sql);
                                        while($row = mysqli_fetch_assoc($mysql_select)){ 
                                        $start_time = explode(':',$row['start_time']);
                                            $from_hh = $start_time['0']; $from_mm = $start_time['1']; $from_ss = $start_time['2'];
                                        $end_time= explode(':',$row['end_time']);
                                            $to_hh = $end_time['0']; $to_mm = $end_time['1']; $to_ss = $end_time['2'];
                                            $session_id = $row['session_time_setup_id'];
                                    ?>
                                        <tr>
                                            <th class="text-align"><?= strtoupper($row['session_description'])?></th>
                                            <td>
                                                <select name="from_hh" class="from_hh_<?=$session_id?>" class="form-control">
                                                    <option value="" selected disabled>Set</option>
                                                    <?php 
                                                        $hm=24;
                                                        for ($i=1; $i <= $hm; $i++) {
                                                            if($i<10){$tt='0'.$i;}else{$tt=$i;} ?>
                                                            <option <?php if($tt==$from_hh){echo 'selected';} ?> value="<?=$tt?>"><?=$tt?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="from_mm" class="from_mm_<?=$session_id?>" class="form-control">
                                                    <option value="" selected  disabled>Set</option>
                                                    <?php 
                                                        $hm=59;
                                                        for ($i=0; $i <= $hm; $i++) {
                                                            if($i<10){$tt='0'.$i;}else{$tt=$i;} ?>
                                                            <option <?php if($tt==$from_mm){echo 'selected';} ?> value="<?=$tt?>"><?=$tt?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="from_ss" class="from_ss_<?=$session_id?>" class="form-control">
                                                    <option value="" selected  disabled>Set</option>
                                                    <?php 
                                                        $hm=59;
                                                        for ($i=0; $i <= $hm; $i++) {
                                                            if($i<10){$tt='0'.$i;}else{$tt=$i;} ?>
                                                            <option <?php if($tt==$from_ss){echo 'selected';} ?> value="<?=$tt?>"><?=$tt?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="to_hh" class="to_hh_<?=$session_id?>" class="form-control">
                                                    <option value="" selected  disabled>Set</option>
                                                    <?php 
                                                        $hm=24;
                                                        for ($i=1; $i <= $hm; $i++) {
                                                            if($i<10){$tt='0'.$i;}else{$tt=$i;} ?>
                                                            <option <?php if($tt==$to_hh){echo 'selected';} ?> value="<?=$tt?>"><?=$tt?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="to_mm" class="to_mm_<?=$session_id?>" class="form-control">
                                                    <option value="" selected  disabled>Set</option>
                                                    <?php 
                                                        $hm=59;
                                                        for ($i=0; $i <= $hm; $i++) {
                                                            if($i<10){$tt='0'.$i;}else{$tt=$i;} ?>
                                                            <option <?php if($tt==$to_mm){echo 'selected';} ?> value="<?=$tt?>"><?=$tt?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="to_ss" class="to_ss_<?=$session_id?>" class="form-control">
                                                    <option value="" selected  disabled>Set</option>
                                                    <?php 
                                                        $hm=59;
                                                        for ($i=0; $i <= $hm; $i++) {
                                                            if($i<10){$tt='0'.$i;}else{$tt=$i;} ?>
                                                            <option <?php if($tt==$to_ss){echo 'selected';} ?> value="<?=$tt?>"><?=$tt?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <center><input type="submit" class="art-button-green" name="saveSession" value="SAVE" onclick="save_session('<?=$session_id?>')"></center>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="8">
                                                <center><a href="dialysisSessions.php" type="submit" class="art-button-green">PREVIEW SESSIONS</a></center>
                                            </td>
                                        </tr>
                                    </tfoot>

                                </table>
                                <h6>N.B</h6>
                                <ol>
                                    <li><b>HH</b>=> Hours</li>
                                    <li><b>MM</b>=> Minutes</li>
                                    <li><b>SS</b>=> Seconds</li>
                                </ol>
                            <?php }else{ ?>
                                <h5 class="text-align-td" >ADD NEW SESSIONS</h5><br>
                                <table style="width: 100%;" class="table">
                                    <tr>
                                        <td style="width: 5%; text-align:right;"><h4>Session Name</h4></td>
                                        <td style="width: 15%">
                                            <input type="text" name="session_name" id="session_name" placeholder="Enter Session Name" class="form-control"/>
                                        </td>
                                        <td style="width: 5%; text-align:right;"><h4>From</h4></td>
                                        <td style="width: 15%">
                                            <input type="text" name="session_from" id="session_from" class="form-control"/>
                                        </td>
                                        <td style="width: 5%; text-align:right;"><h4>To</h4></td>
                                        <td style="width: 15%">
                                            <input type="text" name="session_to" id="session_to" class="form-control"/>
                                        </td>
                                        <td style="width: 10%">
                                            <input type="button" name="save_btn" value="SAVE" class="art-button-green" onclick="save_new_session()"/>
                                        </td>
                                    </tr>
                                </table>
                                <h5 class="text-align-td" >LIST OF HAEMODIALYSIS REPORT SESSIONS</h5><br>

                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <!-- <th rowspan="2">#</th> -->
                                            <th rowspan="2" width="45%">SESSION NAME</th>
                                            <th colspan="2" style="text-align:center;">SESSION TIME (<i>24 Hrs Format</i>)</th>
                                        </tr>
                                        <tr>
                                            <th >FROM</th>
                                            <th >TO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $sql="SELECT * FROM tbl_dialysis_session_time_setup";
                                        $mysql_select=mysqli_query($conn,$sql);
                                        while($row = mysqli_fetch_assoc($mysql_select)){ 

                                    ?>
                                        <tr>
                                            <th class="text-align"><?= strtoupper($row['session_description'])?></th>
                                            <td class="text-align-td"><?=$row['start_time']?></td>
                                            <td class="text-align-td"><?=$row['end_time']?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5">
                                                <center><a href="dialysisSessions.php?update_sessions" type="submit" class="art-button-green">UPDATE/CHANGE SESSIONS</a></center>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            <?php } ?>
                        </div>
                    </td>
                </tr>
            </table>
        </center>
    </fieldset><br/>

 <script>
    function save_session(id) {
        var employee_id = '<?php echo $_SESSION['userinfo']['Employee_ID']?>';
       var from_hh= $('.from_hh_'+id).val();
       var from_mm= $('.from_mm_'+id).val();
       var from_ss= $('.from_ss_'+id).val();
       var to_hh= $('.to_hh_'+id).val();
       var to_mm= $('.to_mm_'+id).val();
       var to_ss= $('.to_ss_'+id).val();

       var start_time = from_hh+':'+from_mm+':'+from_ss;
       var end_time = to_hh+':'+to_mm+':'+to_ss;

       $.ajax({
            type: 'POST',
            url: 'dialysisSaveSessions.php',
            data: {updateSession:'True',session_id:id, employee_id:employee_id, start_time:start_time,end_time:end_time},
            cache: false,
            success: function (html) {
                alert(html);
            }
        });
       
    }

    function save_new_session() {
        var employee_id = '<?php echo $_SESSION['userinfo']['Employee_ID']?>';
        var session_name= $('#session_name').val();
        var session_from= $('#session_from').val();
        var session_to= $('#session_to').val();
        if(session_name == ''){
            $('#session_name').css("border","2px solid red");
            return false;
        }
        if(session_from == ''){
            $('#session_from').css("border","2px solid red");
            return false;
        }
        if(session_to == ''){
            $('#session_to').css("border","2px solid red");
            return false;
        }
            if(confirm("Are you Sure you want to Save")){
                $.ajax({   
                type: 'POST',
                url: 'dialysisSaveSessions.php',
                data: {new_session:'True',session_name:session_name, employee_id:employee_id, session_from:session_from,session_to:session_to},
                cache: false,
                success: function (html) {
                    alert(html);
                    $('#session_name').css("border","");
                    $('#session_from').css("border","");
                    $('#session_name').css("border","");
                    location.reload(true);
                }
            });  
        }
    }
        
 </script>


    <?php
    include("./includes/footer.php");
    ?>


<script src="css/jquery.datetimepicker.js"></script>
    <script>
    $('#session_from').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:    'now'
    });
    $('#session_from').datetimepicker({value:'',step:01});
    $('#session_to').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:'now'
    });
    $('#session_to').datetimepicker({value:'',step:01});
    </script>

    <link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
    <link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
    <script src="media/js/jquery.js" type="text/javascript"></script>
    <script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
    <script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
    <script src="css/jquery-ui.js"></script>
<!-- <br/> -->
<?php
    // include("./includes/footer.php");
?>

