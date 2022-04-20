
<script>
function SelectViewer(imgSrc){
	parent.document.getElementById('imgViewerImg').src = imgSrc;
	parent.document.getElementById('imgViewer').style.visibility = 'visible';
}
</script>
<?php
   include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['RI'])){
        $Registration_ID = $_GET['RI'];   
    }else{
        $Registration_ID = '';
    }
	
    if(isset($_GET['II'])){
        $Item_ID = $_GET['II'];   
    }else{
        $Item_ID = '';
    }
	$data='';
 ?>

 <?php
	if(isset($_POST['submit'])){       
        if(isset($_SESSION['userinfo'])){ 
           if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
           }else{
            $Employee_ID = 0;
           }
		}
	}

	//fetching data from database 
	
	
//	$photo="SELECT * FROM tbl_radiology_image WHERE Registration_ID='$Registration_ID' AND Item_ID = '$Item_ID'";
//	  $result=mysqli_query($conn,$photo) or die(mysqli_error($conn));
//		 if(mysqli_num_rows($result) > 0){
//			 $list=0;
//			 while ($row = mysqli_fetch_array($result)){
//				 $list++;
//				 extract($row);
//                                 
//                                $file=getimagesize($Radiology_Image);
//                                
//
//				 $data.= '<h3 style="text-align: center;display:inline">';
//                                 
//                                 if($file !==false){
//                                      $data .= "<a href='" . $Radiology_Image . "' class='fancyboxRadimg' ><img height='20' alt=''  src='" . $Radiology_Image . "'  alt=''/></a>";
//                                 }else{
//                                     $data .= "<a href='" . $Radiology_Image . "'  ><img height='20' alt=''  src='Reports-icon.png'  alt=''/></a>";
//                                 }
//                                 
//                                
//				  $data.= '</h3>';
//			 }
//		 }else{
//			 $data.= "<center><b style='text-align: center;font-size: 14px;font-weight: bold;color:red'>No Radiology Images For This Patient.</b></center>";
//		 }
?>