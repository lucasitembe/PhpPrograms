<?php
/*written by gkcchief on 01.03.2019 */
include("./includes/constants.php");
function is_connected()
{
  $connected = fopen(ehms_mr_local_url.":80/","r");
  if($connected)
  {
     return true;
  } else {
   return false;
  }

}
if(is_connected()){
    echo "connection_available";
}else{
    echo "connection_fails";
}
