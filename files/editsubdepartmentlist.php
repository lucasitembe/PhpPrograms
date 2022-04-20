
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
    <a href='departmentpage.php?Department=DepartmentThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>



<script language="javascript" type="text/javascript">
    function searchSubDepartment(Sub_Department_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=320px src='edit_Sub_Department_Frame.php?Sub_Department_Name="+Sub_Department_Name+"'></iframe>";
    }
</script>
<br/><br/>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    #sss:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<fieldset>
    <center>
    <table width = "80%">
        <tr>
            <td style="text-align: right;" width="15%">Department Name</td>
            <td width="25%">
                <select name="Department_ID" id="Department_ID" onchange="Search_Sub_Department()">
                    <option selected="selected" value="0">All Department</option>
                <?php
                    $select = mysqli_query($conn,"select Department_ID, Department_Name from tbl_department where Department_Status = 'active'") or die(mysqli_error($conn));
                    $no = mysqli_num_rows($select);
                    if($no > 0){
                        while ($data = mysqli_fetch_array($select)) {
                ?>
                            <option value="<?php echo $data['Department_ID']; ?>"><?php echo ucwords(strtolower($data['Department_Name'])); ?></option>
                <?php
                        }
                    }
                ?>
                </select>
            </td>
            <td>
                <input type='text' name='Sub_Department_Name' id='Sub_Department_Name' oninput='Search_Sub_Department()' onkeypress='Search_Sub_Department()' placeholder='~~~~ ~~~ ~~ ~Search Sub Department Name~ ~~ ~~~ ~~~~' style="text-align: center;" autocomplete="off">
            </td>
        </tr>
    </table>
    </center>
</fieldset>

<fieldset style="overflow-y: scroll; height: 400px; background-color: white;" id="Sub_Department_Area">  
    <legend align="left"><b>SUB DEPARTMENTS LIST</b></legend>
    <table width = "100%">
<?php
    $Title = '<tr><td colspan="4"><hr></td></tr>
                <tr>
                    <td width="5%"><b>SN</b></td>
                    <td><b>SUB DEPARTMENT NAME</b></td>
                    <td width="30%"><b>DEPARTMENT NAME</b></td>
                    <td width="15%" style="text-align: center;"><b>SUB DEPARTMENT STATUS</b></td>
                </tr>
                <tr><td colspan="4"><hr></td></tr>';
    echo $Title;

    $select = mysqli_query($conn,"select * from tbl_department dep, tbl_sub_department sdep
                            where dep.department_id = sdep.department_id order by dep.department_id") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        $temp = 0;
        while ($row = mysqli_fetch_array($select)) {
            echo "<tr id='sss'>
                    <td>".++$temp."</td>
                    <td><a href='editsubdepartment.php?Sub_Department_ID=".$row['Sub_Department_ID']."&EditSubDepartment=EditSubDepartmentThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Sub_Department_Name']))."</a></td>
                    <td><a href='editsubdepartment.php?Sub_Department_ID=".$row['Sub_Department_ID']."&EditSubDepartment=EditSubDepartmentThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Department_Name']))."</a></td>
                    <td style='text-align: center;'><a href='editsubdepartment.php?Sub_Department_ID=".$row['Sub_Department_ID']."&EditSubDepartment=EditSubDepartmentThisForm' target='_parent' style='text-decoration: none;'>".$row['Sub_Department_Status']."</a></td>";
            if($temp%20 == 0){
                echo $Title;
            }
        }
    }    
?>
    </table>
    <!-- <center>
        <table width=100% border=1>
            <tr>
                <td id='Search_Iframe'>
                    <iframe width='100%' height=320px src='edit_Sub_Department_Pre_Frame.php?Sub_Department_Name="+Sub_Department_Name+"'></iframe>
                </td>
            </tr>
        </table>
    </center> -->
</fieldset>

<script type="text/javascript">
    function Search_Sub_Department(){
        var Sub_Department_Name = document.getElementById("Sub_Department_Name").value;
        var Department_ID = document.getElementById("Department_ID").value;
        if(window.XMLHttpRequest) {
            myObjectSearch = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSearch = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearch.overrideMimeType('text/xml');
        }
        
        myObjectSearch.onreadystatechange = function (){
            dataSearch = myObjectSearch.responseText;
            if (myObjectSearch.readyState == 4) {
                document.getElementById('Sub_Department_Area').innerHTML = dataSearch;
            }
        }; //specify name of function that will handle server response........
        myObjectSearch.open('GET','Search_Sub_Department.php?Sub_Department_Name='+Sub_Department_Name+'&Department_ID='+Department_ID,true);
        myObjectSearch.send();
    }
</script>

<script>
    $(document).ready(function () {
        $('select').select2();
    })
</script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
<?php
    include("./includes/footer.php");
?>