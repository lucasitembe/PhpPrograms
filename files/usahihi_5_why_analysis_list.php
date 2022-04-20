<?php
include("./includes/header.php");
include("./includes/connection.php");
?>
<!-- <a href='./completed_engineering_works.php' class='art-button-green'>
        COMPLETED 5 WHY ANALYSIS
    </a> -->
<a href='./engineering_works.php?engineering_works=engineering_WorkThisPage' class='art-button-green'>
        BACK
    </a>

		<br>
        <?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        
        $age = $Today - $original_Date; 
    }
?>

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>

<script language="javascript" type="text/javascript">
    function searchPatient(assigned_engineer){
		document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='search_list_engineering_Iframe2.php?assigned_engineer="+assigned_engineer+"'></iframe>";
    }
</script>

<script language="javascript" type="text/javascript">
	var assigned_engineer = document.getElementById("Search_Patient").value;
	
	if (assigned_engineer != '' && assigned_engineer != null) {//All set
		document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='search_list_engineering_Iframe2.php?assigned_engineer="+assigned_engineer+"'></iframe>";
	}else(assigned_engineer == '' || assigned_engineer == null)){
	    document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='search_list_engineering_Iframe2.php?assigned="+assigned+"'></iframe>";	
	}
    }
</script>
<br/><br/>
<center>
    <table width=100%>
        <tr>
            <td width=20%>
                <input type='text' name='Search_Patient' id='Search_Patient' style='text-align: center;' oninput='searchPatient(this.value)'  placeholder="~~~~~~~~~~~~~  Search By Staff / Engineer's Name  ~~~~~~~~~~~~~~~~~~~" autocomplete="off">
            </td>
        </td>
        </tr>
        
    </table>
</center>
<br/>
<fieldset>  
            <legend align=center><b>5 WHY ANALYSIS LIST</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
		<iframe width='100%' height=400px src='search_5_why_list_Iframe.php?assigned_engineer="+assigned_engineer+"'></iframe>
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<script type="text/javascript" src="js/afya_card.js"></script>
<?php
    include("./includes/footer.php");
?>
