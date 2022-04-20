<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     
?>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='setupandconfiguration.php?BackToSetupAndConfiguration=BackTosetupAndConfigurationThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>


<?php
    //select systemconfiguration based on branch
    if(isset($_SESSION['userinfo']['Branch_ID'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }
    $select_system_configuration = mysqli_query($conn,"select * from tbl_system_configuration where Branch_ID = '$Branch_ID'");
    while($row = mysqli_fetch_array($select_system_configuration)){
        $Centralized_Collection = $row['Centralized_Collection'];
        $Departmental_Collection = $row['Departmental_Collection'];
    }
    
    if(isset($_POST['save_changes'])){
       $terminal_id= $_POST['terminal_id'];
       $terminal_name= $_POST['terminal_name'];
       $terminal_auto_inc_id= $_POST['terminal_auto_inc_id'];
       
       $sql_update_terminal="UPDATE tbl_epay_offline_terminals_config SET terminal_id='$terminal_id',terminal_name='$terminal_name'";
       $sql_update_terminal.="WHERE id='$terminal_auto_inc_id'";
       $sql_update_terminal_result=mysqli_query($conn,$sql_update_terminal) or die(mysqli_error($conn));
       if($sql_update_terminal_result){
           ?>
               <script>alert("Changes Saved Successfully")</script>
               <?php
       }else{
            ?>
               <script>alert("Fail to save changes!..try again")</script>
               <?php
       }
    }
    
    
?>
<br/><br/><br/><br/><br/><br/>
<fieldset>  <br/>
            <legend align=right><b>ePAYMENT CONFIGURATION</b></legend>
        <center>
        <table width = 60%>
        <thead>
            <tr>
                <th>OFFLINE TRANSACTIONS CONFIG</th>
                <th>INVOICE IDs GENERATION</th>
            </tr>
        </thead>
            <tr>
             <td style='text-align: center; height: 40px;'>
            <input type="button" name="add_terminal" id="add_terminal" value="Offline Terminal Setup" style="width: 100%; height: 100%;">
             </td>
                <td style='text-align: center; height: 40px;'>
                    <input type="button" name="Code_Generator" id="Code_Generator" value="Generate CRDB invoice IDs" style='width: 100%; height: 100%' onclick="Generate_Invoices();">
                </td>
            </tr>
        </table>
        </center><br/><br/><br/>
</fieldset>

<script type="text/javascript">
    function Generate_Invoices(){
        var r = confirm("You are sure you want to generate invoice numbers?. \nClick OK to continue or Cancel to stop process");
        if (r == true) {
            if(window.XMLHttpRequest){
                myObjectGenerator = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectGenerator = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGenerator.overrideMimeType('text/xml');
            }
            myObjectGenerator.onreadystatechange = function (){
                data = myObjectGenerator.responseText;
                if (myObjectGenerator.readyState == 4) {
                    var feedback = data;
                    if(feedback == 'yes'){
                        alert(data);
                    }else{
                        alert("Process Fail");
                    }
                    /*alert(data);*/
                    //document.getElementById('Requisition_Number').value = data;
                }
            }; //specify name of function that will handle server response........
            
            myObjectGenerator.open('GET','number_generator.php',true);
            myObjectGenerator.send();
        }
    }

    $('#add_terminal').click(function(){
        var uri = '../epay/offline_terminal_setup.php';
        $.ajax({
            type: 'POST',
            url: uri,
            data:{},
            success: function(data){
                showDialog('Offline Terminals',data,'60%','450');
            },
            error: function(){

            }
        });
        
    });

    function showDialog(dTitile,content,dWidth,dHeight)
    {
        $("#myDalog").dialog({
            title: dTitile,
            width: dWidth,
            height: dHeight,

        }).html(content);
    }
</script>
<div id="myDalog" style="display:none;">defualt text</div>
<?php
    include("./includes/footer.php");
?>