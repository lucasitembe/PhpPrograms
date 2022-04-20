<?php
	include("./includes/header.php");
	include("./includes/connection.php");
	$requisit_officer=$_SESSION['userinfo']['Employee_Name'];
	
    if(!isset($_SESSION['userinfo'])){ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");}
    if(isset($_SESSION['userinfo']))
	{
		if(isset($_SESSION['userinfo']['Storage_And_Supply_Work']))
		{
			if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){ header("Location: ./index.php?InvalidPrivilege=yes");} 
		}else
			{
				header("Location: ./index.php?InvalidPrivilege=yes");
			}
    }else
		{ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes"); }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
            { 
            echo "<a onclick='goBack()' class='art-button-green'>BACK</a>";
            }
    }
               




if(!isset($_GET['addlaboratorytest'])){
    ?>
<br/><br/><br/><br/><br/>
<center>               
<table style="width:90%;margin_top:20px;" class="hiv_table">
    <tr>
        <td>
            <fieldset>   
        <center>
        <form name="" action="laboratory_setup_test.php?addlaboratorytest=addlaboratorytest" method="POST">
            <table width=100%>
    <tr>
        <th>Parameter Name</th><th>Unit Of Measure</th><th>Higher Value</th><th>Operator</th><th>Lower Value</th>
    </tr>
                <tr>
                    <td style="width:500px">
                                <select name='Specimen_ID' id='Specimen_ID'  required='required'  style="width:100%" >
                                <option selected='selected'></option>
                                  <?php
                                    $data = mysqli_query($conn,"SELECT * FROM tbl_laboratory_specimen");
                                    while($row = mysqli_fetch_array($data)){
                                      echo "<option  value='".$row['Specimen_ID']."'>".$row['Specimen_Name']."</option>";
                                    }
                                  ?> 
                                </select>
                    </td>

                    <td style="width:900px"> 
                        <input name="Unit_Of_Measure" type="text" >
                    </td> 
                    <td style="width:900px"> 
                        <input name="Higher_Value" type="text" >
                    </td> 
                    <td style="width:900px"> 
                        <input name="Operator" type="text" >
                    </td>
                    <td style="width:900px"> 
                        <input name="Lower_Value" type="text" >
                    </td>
                    <td style='text-align: center;'>
                        <input type='submit' name='submit' id='submit' value='ADD' class='art-button-green'>
                        <input type='hidden' name='' value='true'/> 
                    </td>
                </tr>
                <tr>
                    <td colspan="7" style="width:100%"><hr></td>
                </tr>
                 <tr>
                    <td colspan="7" style="width:100%">
                    <div style="width:100%;height:350px;background-color:white;overflow:scroll;">
    <?php
        $select_sample =mysqli_query($conn,"select * from tbl_laboratory_test_specimen where Item_ID='".$_GET['Item_ID']."'");
        ?>
<center><table style="width:99%">
    <tr>
         <th>Parameter Name</th><th>Unit Of Measure</th><th>Higher Value</th><th>Operator</th><th>Lower Value</th>
    </tr>

        <?php
        while($disp = mysqli_fetch_array($select_sample)){

 echo "<tr>";
        echo "<td>".$disp['Lab_Test_Name']."</td><td>".$disp['Item_ID']."</td><td>".$disp['Specimen_Type_ID']."</td><td>".$disp['CPOE_Name']."</td><td>".$disp['Genaral_Information']."</td>";
echo "</tr>";


        }
            ?>
        </table>
</center>
                    </div>
                    </td>
                </tr>
            </table>   
        </center>
</fieldset>
        </td>
    </tr>
</table>      
        </center>
<?php
    }else
     if(isset($_GET['addlaboratorytest'])) {

                $item_id = $_POST['Item_ID'];
                $lab_test_name = $_POST['lab_test_name'];
                $Specimen_ID = $_POST['Specimen_ID'];
                $cpoe_name = $_POST['cpoe_name'];
                $general_information = $_POST['general_information'];

$sql = mysqli_query($conn,"INSERT INTO tbl_laboratory_test_specimen ( Lab_Test_Name,Item_ID,CPOE_Name,Specimen_ID) 
                        VALUES ( '$lab_test_name','$item_id', '$cpoe_name', '$Specimen_ID');");

if($sql){

                echo "<script>
                        document.location = 'laboratory_setup_test.php?Item_ID={$item_id}';
                        </script>";
        // header('Location: laboratory_setup_test.php?Item_ID ={$item_id}');
}else{
        echo "Fail To Add Item Specimen";   
}

    }             
                

    include("./includes/footer.php");
?>
