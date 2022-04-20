<?php
include("./includes/connection.php");
                         $sql_select = mysqli_query($conn,"SELECT cancer_drug_ID,cancer_id, it.Product_Name,dr.item_id FROM tbl_items_cancer_drug dr,tbl_items it WHERE  it.Item_ID=dr.item_id AND dr.Status='pending'");
                           $count =0;
                          while($row=mysqli_fetch_assoc($sql_select)){
                              $count++;
                              $item_id =$row['item_id'];
                              $item_name = $row['Product_Name'];
                              $cancer_drug_ID = $row['cancer_drug_ID'];
                              $cancer_id =$row['cancer_id'];
                              
                             echo "  <tr><td> 
                    <center>$count</center>   
                        </td>
                        <td>
                            <input type='text' name='ditem_id[]' autocomplete='off' style='width:100%;display:none;height:30px;' readonly value='$item_id'/>
                           <input type='text' name='drug[]' autocomplete='off' style='width:100%;display:inline;height:30px;' readonly value='$item_name'/>
                        </td>
                        <td>
                            <input type='text' name='dvolume[]'  autocomplete='off' style='width:100%;display:inline;height:30px;'/>
                        </td>
                        <td>
                             <input type='text' name='ddose[]'  autocomplete='off' style='width:100%;display:inline;height:30px;'/>
                        </td>                        
                          <td>
                           <input type='text' name='droute[]' autocomplete='off' style='width:100%;display:inline;height:30px;'/>
                        </td>
                          <td>
                           <input type='text' name='dadmin[]' autocomplete='off' style='width:100%;display:inline;height:30px;'/>
                        </td>
                          <td>
                           <span><input type='text' name='dfrequence[]' autocomplete='off' style='width:80%;display:inline;height:30px;'/>
                           <input type='button' value='X' class='btn btn-danger' name='remove_items' onclick='remove_item_drug($cancer_drug_ID,$cancer_id)'></span>
                        </td>
                        
                        </tr>"; 
                              
                          }
                          
                       ?>