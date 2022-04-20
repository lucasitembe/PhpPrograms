            <?php
            include("./includes/connection.php");
            
            
            
             
                         $sql_select = mysqli_query($conn,"SELECT item_cancer_ID,cancer_id,it.Product_Name,nt.item_ID FROM tbl_items_cancer nt,tbl_items it WHERE  it.Item_ID=nt.item_ID AND nt.Status='pending'");
                           $count =0;
                          while($row=mysqli_fetch_assoc($sql_select)){
                              $count++;
                              $item_name = $row['Product_Name'];
                              $item_ID  = $row['item_ID'];
                              $item_cancer_ID = $row['item_cancer_ID'];
                              $cancer_id =$row['cancer_id'];
                              
                             echo "  <tr><td>
                    <center>$count</center>  
                        </td>
                        <td>
                      
                           <input type='text' name='item_ID[]'  autocomplete='off' style='width:100%;display:none;height:30px;' readonly value='$item_ID'/>
                           <input type='text' name='item[]'  autocomplete='off' style='width:100%;display:inline;height:30px;' readonly value='$item_name'/>
                        </td>
                        <td>
                             <input type='text' name='dose[]' autocomplete='off' style='width:100%;display:inline;height:30px;'/>
                        </td>
                        <td>
                            <input type='text' name='route[]' autocomplete='off' style='width:100%;display:inline;height:30px;'/>
                        </td>
                          <td>
                           <input type='text' name='admin[]' autocomplete='off' style='width:100%;display:inline;height:30px;'/>
                        </td>
                          <td>
                           <input type='text' name='frequence[]' autocomplete='off' style='width:100%;display:inline;height:30px;'/>
                        </td>
                          <td>
                           <input type='text'  name='medication[]' autocomplete='off' style='width:80%;display:inline;height:30px;'/>
                           <input type='button' value='X' class='btn btn-danger' name='remove_item' onclick='remove_item($item_cancer_ID,$cancer_id)'>
                        </td></tr>"; 
                              
                          }

                         
                       ?> 