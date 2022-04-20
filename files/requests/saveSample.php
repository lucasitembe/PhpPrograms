
<link rel="stylesheet" href="table.css" media="screen">
<script type="text/javascript">
    $('.submit').click(function(){
      var id=$(this).attr('id');
      var Edit_Sample_Name=$('#Edit_Sample_Name').val();
      var Edit_Sample_Container=$('#Edit_Sample_Container').val();
      if(Edit_Sample_Name=='' && Edit_Sample_Container==''){
           alert('Fill all the required details to continue');
           exit();
       }
       
       $.ajax({
        type:'POST', 
        url:'requests/saveSample.php',
        data:'action=SaveChanges&Sample_Container='+Edit_Sample_Container+'&Sample_Name='+Edit_Sample_Name+'&id='+id,
        cache:false,
        success:function(html){
          $('#Search_Iframe').html(html);
        }
     });
      
    });
    

    //Delete specimen
    $('.DeleteSpecimen').click(function(){
     var deleteme=$(this).attr('id');
     if(confirm('Are you sure you want to deactivate this?')){
         $.ajax({
        type:'POST', 
        url:'requests/saveSample.php',
        data:'action=SaveDeactivate&id='+deleteme,
        cache:false,
        success:function(html){
           $('#Search_Iframe').html(html);
        }
     }); 
      }else{
          exit();
      }
    });
    
    
    
    //Edit specimen
    $('.EditSpecimen').click(function(){
        var id=$(this).attr('id');
        var name=$(this).attr('name');
        $.ajax({
        type:'POST', 
        url:'requests/saveSample.php',
        data:'action=EditSample&id='+id,
        cache:false,
        success:function(html){
           $('#sampleDetails').html(html);
        }
     });
        
      $('#addNewSample').dialog({
      modal:true, 
      width:600,
      resizable:true,
      draggable:true,
    });

       $("#addNewSample").dialog('option', 'title', 'Edit  '+name);
    });
    
    
    
</script>
<?php
include("../includes/connection.php");
if(isset($_POST['action'])){
    if ($_POST['action']=='SaveSample'){
        $Sample_Container=$_POST['Sample_Container'];
        $Sample_Name=$_POST['Sample_Name'];
        $temp = 1;
        
        $checkAvailability=mysqli_query($conn,"SELECT * FROM tbl_laboratory_specimen WHERE Specimen_Name='$Sample_Name'");
        $rows=mysql_fetch_row($checkAvailability);
        $numRows=mysqli_num_rows($checkAvailability);
        if ($numRows>0){
          if($rows[3]=='Inactive') {
            $insertsample=mysqli_query($conn,"UPDATE tbl_laboratory_specimen SET Sample_Container='$Sample_Container',Status='Active' WHERE Specimen_Name='$Sample_Name'");  
            echo '<script>alert("Sample saved successfully")</script>';
          }elseif ($rows[3]=='Active') {
              $insertsample=''; 
              echo '<script>alert("This specimen already exists,choose a different specimen")</script>';
            } 
        }  else {
          $insertsample=mysqli_query($conn,"INSERT INTO tbl_laboratory_specimen (Specimen_Name,Sample_Container) VALUES ('$Sample_Name','$Sample_Container')");  
         
          
        }

        if($insertsample){
		
        echo '<center><table width =100% border=0>';
        echo '<tr id="thead"><td width = 5%><b>SN</b></td>
        <td><b>Sample Name</b></td>
         <td><b>Sample Container</b></td>
         <td width="20%">Action</td>';
        $message="";
        $select_lab_products = mysqli_query($conn,"SELECT * FROM tbl_laboratory_specimen WHERE Status='Active'");
        while($row = mysqli_fetch_array($select_lab_products)){
        echo "<tr><td>".$temp."</td>
        <td>".$row['Specimen_Name']."</td>
        <td>".$row['Sample_Container']."</td>
            
        <td style='width:150px'><button class='EditSpecimen' id=".$row['Specimen_ID'].">EDIT</button>
        <button class='DeleteSpecimen' id=".$row['Specimen_ID'].">DEACTIVATE</button></td>";

        $temp++;
        echo "</tr>";

        }   
          echo '<script>alert("Sample saved successfully")</script>';    
        } else {
            
        }
        
    }elseif ($_POST['action']=='EditSample') {
        $id=$_POST['id'];
        $getSample=mysqli_query($conn,"SELECT * FROM tbl_laboratory_specimen WHERE Specimen_ID='$id'");
        $results=mysql_fetch_row($getSample);
        $specimenID=$results[0];
        $sample=$results[1];
        $container=$results[2];
        echo '<fieldset>  
            <form method="post" name="myForm" id="myForm"  enctype="multipart/form-data">
            <table width=100%>
                <tr>
                    <td width=25% style="text-align:right;"><b>Sample Name</b></td>
                    <td width=75%>
                        <b><input type="text" name="Sample_Name" id="Edit_Sample_Name" value="'.$sample.'" required="required" placeholder="Enter Sample Name" value=""></b>
                    </td> 
                </tr>
                <tr>
                    <td width=25% style="text-align:right;"><b>Sample Container</b></td>
                    <td width=75%>
                         <input type="text" name="Sample_Container" id="Edit_Sample_Container" value="'.$container.'" required="required" placeholder="Enter Sample_Container"
                                value="">
                        
                    </td>
                </tr>
                <tr>
                    <td colspan=2 style="text-align: right;">
                        <input type="button" name="submit" id="'.$specimenID.'" value="   SAVE CHANGES   " class="art-button-green submit">
                        <input type="reset" name="clear" id="clear" value=" CLEAR" class="art-button-green">
                    </td>
            
                </tr>
            </table>
    </form>
</fieldset>';
        
        
    }elseif ($_POST['action']==='SaveChanges') {
      $id=$_POST['id'];
      $Sample_Container=$_POST['Sample_Container'];
      $Sample_Name=$_POST['Sample_Name'];
      $update="UPDATE tbl_laboratory_specimen SET Specimen_Name='$Sample_Name', Sample_Container='$Sample_Container' WHERE Specimen_ID='$id'";
      $updateQuery=mysqli_query($conn,$update);
      if($updateQuery){
        $temp = 1;
        echo '<center><table width =100% border=0>';
        echo '<tr id="thead"><td width = 5%><b>SN</b></td>
        <td><b>Sample Name</b></td>
         <td><b>Sample Container</b></td>
        <td width="20%">Action</td>';
        $select_lab_products = mysqli_query($conn,"SELECT * FROM tbl_laboratory_specimen WHERE Status='Active'");
                                             
        while($row = mysqli_fetch_array($select_lab_products)){
        echo "<tr><td>".$temp."</td>
        <td>".$row['Specimen_Name']."</td>
        <td>".$row['Sample_Container']."</td>
            
        <td style='width:150px'><button class='EditSpecimen' id=".$row['Specimen_ID'].">EDIT</button>
        <button class='DeleteSpecimen' id=".$row['Specimen_ID'].">DEACTIVATE</button></td>";
      
        $temp++;
        echo "</tr>";
      }
      echo '<script>alert("Changes saved successfully!")</script>';
         
      
      }  else {
        echo '<script>alert("An error has occured!")</script>'; 
      }
     
     }elseif ($_POST['action']==='SaveDeactivate'){
    $id=$_POST['id'];
    $update="UPDATE tbl_laboratory_specimen SET Status='Inactive' WHERE Specimen_ID='$id'";
    $Querying=mysqli_query($conn,$update);
    if($Querying) {
    $temp=1;
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead"><td width = 5%><b>SN</b></td>
    <td><b>Sample Name</b></td>
     <td><b>Sample Container</b></td>
     <td width="20%">Action</td>';
    
        $select_lab_products = mysqli_query($conn,"SELECT * FROM tbl_laboratory_specimen WHERE Status='Active'");
        while($row = mysqli_fetch_array($select_lab_products)){
        echo "<tr><td>".$temp."</td>
        <td>".$row['Specimen_Name']."</td>
        <td>".$row['Sample_Container']."</td>
            
        <td style='width:150px'><button class='EditSpecimen' id=".$row['Specimen_ID'].">EDIT</button>
        <button class='DeleteSpecimen' id=".$row['Specimen_ID'].">DEACTIVATE</button></td>";
        $temp++;
        echo "</tr>";
        
      }   
        echo '<script>alert("Specimen deactivated successfully")</script>';     
             
             
         }  
       
    }
    
}
?>

<script type="text/javascript">
    
    
</script>

