<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
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
    
    <a href='diseaseconfiguration.php?OtherConfigurations=OtherConfigurationsThisForm' class='art-button-green'>BACK</a>
<?php  } } ?>
<script language="javascript" type="text/javascript">
    function searchClinic(Clinic_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=440px src='edit_Clinic_Frame.php?Clinic_Name="+Clinic_Name+"'></iframe>";
    }
</script>
<br/><br/>

<fieldset>  
            <legend align=center><b>GROUP LIST</b></legend>

            <center>
    <table width=40%>
        <tr>
            <td>
                <input style="width:100%; text-align:center" type='text' name='Search_Clinic' class='Search_group_name' onkeyup='search_group_name()'  placeholder='Enter Group Name'>
            </td>
        </tr>
        
    </table>
</center>

        <center>
            <div id="search_group" style='overflow:scroll;margin:1px ;height:60vh' class="search_group">
        <?php
        $no=1;
    echo "<table  width=40% style='margin-left:10px'>";
                     $sql="SELECT *from tbl_disease_group";
                     //$sql="SELECT *from tbl_disease_group WHERE disease_group_for='icd_10'";
                     $check=mysqli_query($conn,$sql);
                     echo "
                        <thead>
                            <th style='text-align:left;'>SN</th>
                            <th>GROUP NAME</th>
                            <th style='text-align:left;'>REMARK</th>
                        </thead>
                        <tbody id='search_result'>
                     ";
                     
                    while($row=mysqli_fetch_array($check)){
                        $disease_group_id=$row['disease_group_id'];
                        $disease_group_name=$row['disease_group_name'];
                        echo "
                            <tr>
                                <td  width=10% >
                                    <input type='text' value='".$no++."' readonly>
                                </td>
                                <td>
                                    <input type='text' value='$disease_group_name' readonly>
                                </td>
                               
                                <td  width=6%>
                                    <select id='remark".$row['disease_group_id']."' onchange='update_remark(".$row['disease_group_id'].")'>
                                        <option>".$row['remarks']."</option>
                                        <option value='Yes'>Yes</option>
                                        <option value='No'>No</option>
                                    </select>
                                </td>
                               
                            </tr>
                        ";
                    }
                      
                      ?>
                
        </tbody>
    </table>
            </div>
</center>

<script type="text/javascript">

    function update_remark(disease_group_id){
        var remark = $("#remark"+disease_group_id).val();
        
        $.ajax({
            type: 'POST',
            url: 'update_clinic_remark.php',
            data: {
                remark:remark,
                disease_group_id:disease_group_id
            },
            success: function(response) {
                
            }
        });
    }
    function search_group_name(){
        var search_group_name = $(".Search_group_name").val();
        
        $.ajax({
            type: 'POST',
            url: 'search_group.php',
            data: {search_group_name:search_group_name},
            success: function(response) {
               $("#search_result").html(response);                
            }
        });
    }
</script>
<script src="css/jquery.js"></script>
<script src="script.js"></script>

</fieldset><br/>
<?php
    include("./includes/footer.php");
?>