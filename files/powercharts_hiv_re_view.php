



<?php
    include("./includes/connection.php");
   if(isset($_POST['rch2'])){
	
	$rx= $_POST['rch2'];
        $h= $_POST['h'];
	
        
	
$checkifyupo = "SELECT * FROM tbl_hiv_first_visit WHERE pr_r= '$h'";

$row=mysqli_fetch_array($qresult=mysqli_query($conn,$checkifyupo)); 
	
	$last=$row['hiv_id'];
        
	
	$checkifyupogo = "SELECT * FROM tbl_hiv_visits WHERE first_v_id= '$last' AND muda_huu like '%$rx%'";
$qresulte=mysqli_query($conn,$checkifyupogo);
    
  $fetchdete=mysqli_fetch_array($qresulte);
	  
   }
	
	 ?>
	  
	  

   
   
					
						 
  			
		




        <div class="tabcontents" >
		<?php 
if(isset($_POST['h'])){
	
	$pn= $_POST['h'];	
	$select_Patient_Details = mysqli_query($conn,"
				select pr.Patient_Name,pr.Date_Of_Birth,pr.Member_Number,pr.Gender from
				    tbl_patient_registration pr
					where pr.registration_id ='$pn'") or die(mysqli_error($conn));
							
    //display all items
        while($row2 = mysqli_fetch_array($select_Patient_Details)){
		
	    $Today = Date("Y-m-d");
	    $Date_Of_Birth = $row2['Date_Of_Birth'];
	    $date1 = new DateTime($Today);
	    $date2 = new DateTime($Date_Of_Birth);
	    $diff = $date1 -> diff($date2);
	    $age = $diff->y;
			
            $fname= explode(' ',$row2['Patient_Name'])[0];
			
            
			
			
			$mname='';
			
	    if(sizeof(explode(' ',$row2['Patient_Name']))>= 3){
			
			
			
			
			
			$mname=explode(' ',$row2['Patient_Name'])[sizeof(explode(' ',$row2['Patient_Name'])) - 2];
			
		$lname=explode(' ',$row2['Patient_Name'])[sizeof(explode(' ',$row2['Patient_Name'])) - 1];
		
	    
		
		}
		
		else{
		    
		$lname=explode(' ',$row2['Patient_Name'])[1];
	    }
  	
} }

?>
			
            
			   
	  <table width="100%" class="hiv_table" border="0" > 
                         <tr>
                            
			     
			 <td colspan="2"><input readonly value="<?php echo strtoupper( $fname." ".$mname." ".$lname); ?>" type="text"><input type="hidden" id="prid" value="<?php echo $pn; ?>"></td>
                             
                             
                        </tr>
                         <tr>
                             <td width="100%" colspan="4"><hr></td>
                        </tr>
                        <tr>
                            <!--<td width="30%" class="powercharts_td_left">Husband/Wife/Partner Name</td>
                            <td><input name="" type="text" id="partinername"  ></td>
                            <td class="powercharts_td_left">Mother's Name (if a child under 18 tears)</td>
                            <td><input name="" type="text" id="mother"></td> -->
                        </tr>
                        <tr>
                            <!--<td class="powercharts_td_left">How Long Have They Been Taking ARV?</td> 
                            <td><input name="" type="text" id="arv" placeholder="Years" style="width:35px;"><input name="" type="text" id="month" placeholder="Months" style="width:45px;" ><input style="width:30px;" name="" type="text" id="day" placeholder="days"></td>
                            --><td class="powercharts_td_left">Which ARV Medication are They Taking?</td>
                            <td><input type="text" value="<?php echo $fetchdete['arv_type_medication']; ?>" >
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Current HIV Status</td>
                            <td width="12%">
                                <input type="text"  value="<?php echo $fetchdete['curr_hiv_status']; ?>" >
                            </td>
                            <td class="powercharts_td_left" style="text-align:right">Date Of Previous HIV Test</td>
                            <td><input name="" type="text" value="<?php echo $fetchdete['dateofprev_test']; ?>" id="date2"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Previous HIV Test</td>
                            <td><input name="" type="text" value="<?php echo $fetchdete['did_hetake_prev_test_']; ?>" id="date2"></td>
                            <td class="powercharts_td_left"  style="text-align:right"> Result of Previous HIV Tests</td>
                            <td><input name="" type="text" value="<?php echo $fetchdete['result_ofprev_test']; ?>" id="date2"></td>
                        </tr>                   
                        <tr>
                            <td class="powercharts_td_left">Patient Has Received Pre-Testing Counseling?</td>
                            <td>
                                <input name="" type="text" value="<?php echo $fetchdete['did_pre_test_councel']; ?>" id="date2">
                            </td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Patient Has Received Post -Result Counseling?</td>
                            <td>
                                <input name="" type="text" value="<?php echo $fetchdete['did_post_result_councel']; ?>" id="date2">
                                </td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Recommended Date For Status Review</td>
                            <td><input name="" type="text" value="<?php echo $fetchdete['recommended_date_status_review']; ?>" id="date2"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Is The Patient Taking ARV Therapy?</td>
                            <td>
                                <input name="" type="text" value="<?php echo $fetchdete['arv_therapy']; ?>" id="date2">
                            </td>
                            <td class="powercharts_td_left">Patient Has Signed HIV Declaration</td>
                            <td >
                                <input name="" type="text" value="<?php echo $fetchdete['hiv_declaration']; ?>" id="date2">
                            </td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left" colspan="3">Has Your Husband/Partner/Wife/Mother Been Tested For HIV?</td>
                            <td>
                                <input name="" type="text" value="<?php echo $fetchdete['partiner_or_mother_tested']; ?>" id="date2">
                            </td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left" colspan="3">If The Patient Is HIV Positive With Children,Have They Received Information On Feeding?</td>
                            <td colspan="2">
                               <input name="" type="text" value="<?php echo $fetchdete['feeding_info']; ?>" id="date2">
                                </td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Other Comments</td>
                            <td rowspan="5" colspan="3"><textarea rows="5" id="comment"><?php echo $fetchdete['comments']; ?></textarea>
                        <tr>
                            <td class="powercharts_td_left"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left"></td>
                        </tr>
                         <tr>
                             <td colspan="4" rowspan="2" class="powercharts_footer">
                                "Please ensure that the patient is fully counselled and always aware of the treaments available for HIV positive results before sending them for testing"<br/>
                                "All patients being sent for HIV testing must sign an 'HIV DECLARATION' form,please give to reception for scanning to their file record"<br/>
                                   "If you are screening/testing both partners, you must fill this HIV History for both patients please"</td></td>
                        </tr>
                    </table>
                
						
						
						

						
        
