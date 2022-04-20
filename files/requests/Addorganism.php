 
<?php 

	require_once('../includes/connection.php');
//        
	isset($_GET['Organism']) ? $Organism = mysqli_real_escape_string($conn,$_GET['Organism']) : $Organism != '';
	
//	$cached_data = '';
          if(!empty($Organism)){
		$insert_cache = "INSERT INTO  tbl_organism(organism_name) VALUES('$Organism')";
		$insert_cache_qry = mysqli_query($conn,$insert_cache) or die(mysqli_error($conn));
                
          }
                
                 
                $cached_data.= "<select class='seleboxorg3' id='new_organism_1' style='width:600px; padding-top:4px; padding-bottom:4px;'>";
                 
                         $query_sub_specimen = mysqli_query($conn,"SELECT organism_name FROM tbl_organism") or die(mysqli_error($conn));
                          $cached_data.= '<option value="All">~~~~~Select organism~~~~~</option>';
                         while ($row = mysqli_fetch_array($query_sub_specimen)) {
                           $cached_data.= '<option value="' . $row['organism_idPrimary'] . '">' . $row['organism_name'] . '</option>';
                         }
                       
                $cached_data.= "</select><button type='button' style='color:white !important; height:27px !important; margin-left:168px !important;' class='art-button-green' onclick='addorganism();'>Add Organism</button>";
             
		
		echo $cached_data; 
               
?>
 <link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
<script>
    $(document).ready(function (e){
//        $("#saveCulture7").hide();
        $(".select").select2();
//        $("select").css('color','black');
    });
</script>

