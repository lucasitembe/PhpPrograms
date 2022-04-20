<?php
    include("./includes/connection.php");
    $optical_sponsor_setup_ID=$_POST['optical_sponsor_setup_ID'];
    $select_sponsor = mysqli_query($conn,"SELECT `optical_sponsor_setup_ID`, sp2.Guarantor_Name,sp1.Sponsor_ID FROM optical_sponsor_setup as sp1,tbl_sponsor as sp2 where sp1.Sponsor_ID=sp2.Sponsor_ID and sp1.optical_sponsor_setup_ID='$optical_sponsor_setup_ID'") or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_sponsor)){
        $Guarantor_Name= $row['Guarantor_Name'];
        $Sponsor_ID= $row['Sponsor_ID'];
    }
   
?>
<html>
    <head></head>
    <body>
        <center>               
        <table width=80%>
            <tr>
                <td>
            <fieldset>  
                <legend align=center><b>EDIT SPONSOR</b></legend>
                <form name='myForm' id='myForm'>
                    <table width=100%>
                        <tr>
                            <td width=25%><b>Sponsor</b></td>
                            <td style="width: 50%">
                                <select style='text-align: center;padding:4px; width:100%;' name="Sponsor_ID" id="Sponsor_ID" onchange="update_sponsor(this.value)">
                                    <option value="<?php echo $Sponsor_ID;?>"><?php echo $Guarantor_Name;?></option>
                                        <?php 
                                            //Sponsor list
                                            $query_sub_cat = mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name,payment_method FROM tbl_sponsor") or die(mysqli_error($conn));
                                            while ($row = mysqli_fetch_array($query_sub_cat)) {
                                                echo '<option value="' . $row['Sponsor_ID'] . '">' . $row['Guarantor_Name'] . '</option>';
                                            }
                                        ?> 
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan=2 style='text-align: right;'>
                            <!-- <input type='button' name='submit1' id='submit1' value=' UPDATE' class='art-button-green' onclick="update_sponsor()"> -->
                            </td>
                            <input type="hidden" id="optical_sponsor_setup_ID" class="optical_sponsor_setup_ID" value="<?php echo $optical_sponsor_setup_ID;?>">
                        </tr>
                    </table>
                </form>
            </fieldset>
                </td>
            </tr>
        </table>
    </body>
    </center>
</html>
            
<script>
       function update_sponsor(Sponsor_ID){
          //var Sponsor_ID=$("#Sponsor_ID").val();
          var optical_sponsor_setup_ID=$("#optical_sponsor_setup_ID").val();
        //   alert(Sponsor_ID)
          if(Sponsor_ID !=''){
            $.ajax({
            type:'POST',
            url:'update_optical_sponsor.php',
            data : {Sponsor_ID:Sponsor_ID,optical_sponsor_setup_ID:optical_sponsor_setup_ID
            },
                success : function(response){  
                    // $('#show').html(response);
                    alert(response);
                    location.reload(true);

                }
            })
          }
          else{
              alert("Please fill Sponsor name");
          }
         
    }
</script>