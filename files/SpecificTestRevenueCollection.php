<?php
include("./includes/functions.php");
include("./includes/header.php");
        $DateGiven = date('Y-m-d');
?>
<?php
//get sub department id
$Sub_Department_ID='';
if(isset($_SESSION['Laboratory'])){
    $Sub_Department_Name = $_SESSION['Laboratory'];
    $select_sub_department = mysqli_query($conn,"select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name'");
    while($row = mysqli_fetch_array($select_sub_department)){
        $Sub_Department_ID = $row['Sub_Department_ID'];
    }
}else{
    $Sub_Department_ID = '';
}
?>
<a href="Laboratory_Reports.php?LaboratoryReportThisPage=ThisPage" class="art-button-green">BACK</a>
          <fieldset style='margin-top:10px;'>
                <legend align=right><b>SPECIFIC TEST REVENUE COLLECTION</b></legend>
                  

<!--Specif revenue Collection-->
<div id="specifictestrevenue">
    <table  class="hiv_table" style="width:100%;margin-top:3px;">
        <tr> 
            <td style="text-align:right;width:10%"><b>Date From<b></td>
            <td width="18%"><input type='text' name='Date_From' id='specific_date_From'  autocomplete="off"></td>
            <td style="text-align:right;width:10%"><b>Date To<b></td>
            <td width="18%"><input type='text' name='Date_To' id='specific_date_To'  autocomplete="off"></td>
            <td style="text-align:right;width:10%"><b>Sponsor<b></td>
                <td width="10px"><select id="sponsor">
                        <option value="All">All</option>
                            <?php
                            include("./includes/connection.php");
                                $getSponsor=mysqli_query($conn,"SELECT * FROM tbl_sponsor");
                                while ($result=  mysqli_fetch_assoc($getSponsor)){
                                    echo '<option value="'.$result['Sponsor_ID'].'">'.$result['Guarantor_Name'].'</option>';
                                }
                            ?>
                        </select>
                </td>
                
             <td style="text-align:right;width:10%"><b>Test Name:<b></td>    
                 <td width="10px">
                     <select id="test">
                        <option value="All">All</option>
                            <?php
                            include("./includes/connection.php");
                                $getSponsor=mysqli_query($conn,"SELECT * FROM tbl_items WHERE Consultation_Type='Laboratory'");
                                while ($result=  mysqli_fetch_assoc($getSponsor)){
                                    echo '<option value="'.$result['Item_ID'].'">'.$result['Product_Name'].'</option>';
                                }
                            ?>
                     </select>
                 </td>
            <td width="30px"><input type="submit" id="viewSelected" name="submit" value="View Revenue" class="art-button-green" />  <button class="art-button-green" id="ViewAllrevenuehere" style="display: none">View All</button></td>
        </tr> 

     </table>
  </div>

                <center>
                <hr width="100%">
                </center>

                <center>
                    <table  class="hiv_table" style="width:100%">
                        <tr>
                            <td>
                                <div style="width:100%; height:420px;overflow-x: hidden;background-color: rgb(250,250,250);overflow-y: auto"  id="Search_Iframe_item">

                                </div>
                            </td>
                        </tr>
                    </table>
                </center>
                </fieldset>
                <br/>
                                                            
        <?php
            include("./includes/footer.php");
        ?>                                                           

<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>            
<script src="css/jquery.datetimepicker.js"></script>
   <script>
    $('#specific_date_From').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    startDate:  'now'
    });
    $('#specific_date_From').datetimepicker({value:'',step:30});
    $('#specific_date_To').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    startDate:'now'
    });
    $('#specific_date_To').datetimepicker({value:'',step:30});
    
    </script>

    
    <script>
        $('#viewSelected').click(function(){
           var from_Date=$('#specific_date_From').val();
           var to_Date=$('#specific_date_To').val();
           var sponsor=$('#sponsor').val();
           var testName=$('#test').val();
           if(from_Date=='' || to_Date==''){
               alert('Select date to get the results');
               return false;
           }
            $.ajax({
            type:'POST', 
            url:'requests/TestrevenueCollection.php',
            data:'action=specifictestrevenue&from_Date='+from_Date+'&to_Date='+to_Date+'&sponsor='+sponsor+'&testName='+testName,
            cache:false,
            success:function(html){
              $('#Search_Iframe_item').html(html);
              }
            }); 

        });
    </script>
    <!--End datetimepicker-->
