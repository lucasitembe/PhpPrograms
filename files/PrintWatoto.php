<?php
include("includes/connection.php");
$date1 = mysqli_real_escape_string($conn,$_GET['from_date']);
$date2 = mysqli_real_escape_string($conn,$_GET['to_date']);
 $birthcertme = 0;
    $birthcertke = 0;
    $twoAme = 0;
    $twoAke = 0;
    $twoBme = 0;
    $twoBke = 0;
    $twoCme = 0;
    $twoCke = 0;
    $twoDme = 0;
    $twoDke = 0;
    $twoEme1 = 0;
    $twoEke1 = 0;
    $twoEme2 = 0;
    $twoEke2 = 0;
    $twoEme3 = 0;
    $twoEke3 = 0;
    $twoFme1 = 0;
    $twoFke1 = 0;
    $twoFme2 = 0;
    $twoFke2 = 0;
    $twoFme3 = 0;
    $twoFke3 = 0;
    $twoGme1 = 0;
    $twoGke1 = 0;
    $twoGme2 = 0;
    $twoGke2 = 0;
    $twoGme3 = 0;
    $twoGke3 = 0;
    $twoHme1 = 0;
    $twoHke1 = 0;
    $twoHme2 = 0;
    $twoHke2 = 0;
    $twoHme3 = 0;
    $twoHke3 = 0;
    $twoOme1 = 0;
    $twoOke1 = 0;
    $twoOme2 = 0;
    $twoOke2 = 0;
    $twoOme3 = 0;
    $twoOke3 = 0;
    $twoPme1 = 0;
    $twoPke1 = 0;
    $twoPme2 = 0;
    $twoPke2 = 0;
    $twoPme3 = 0;
    $twoPke3 = 0;
    $twoQme1 = 0;
    $twoQke1 = 0;
    $twoQme2 = 0;
    $twoQke2 = 0;
    $twoQme3 = 0;
    $twoQke3 = 0;
    $twoRme1 = 0;
    $twoRke1 = 0;
    $twoRme2 = 0;
    $twoRke2 = 0;
    $twoRme3 = 0;
    $twoRke3 = 0;
    $twoSme1 = 0;
    $twoSke1 = 0;
    $twoSme2 = 0;
    $twoSke2 = 0;
    $twoSme3 = 0;
    $twoSke3 = 0;
    $twoTme1 = 0;
    $twoTke1 = 0;
    $twoTme2 = 0;
    $twoTke2 = 0;
    $twoTme3 = 0;
    $twoTke3 = 0;
    $twoUme1 = 0;
    $twoUke1 = 0;
    $twoUme2 = 0;
    $twoUke2 = 0;
    $twoUme3 = 0;
    $twoUke3 = 0;
    $twoVme1 = 0;
    $twoVke1 = 0;
    $twoVme2 = 0;
    $twoVke2 = 0;
    $twoVme3 = 0;
    $twoVke3 = 0;
    $threeAme = 0;
    $threeAke = 0;
    $threeBme = 0;
    $threeBke = 0;
    $threeCme = 0;
    $threeCke = 0;

    $fourAme = 0;
    $fourAke = 0;
    $fourBme1 = 0;
    $fourBke1 = 0;
    $fourBme2 = 0;
    $fourBke2 = 0;
    $fourBme3 = 0;
    $fourBke3 = 0;

    $fourCme1 = 0;
    $fourCke1 = 0;
    $fourCme2 = 0;
    $fourCke2 = 0;
    $fourCme3 = 0;
    $fourCke3 = 0;

    $fourDme1 = 0;
    $fourDke1 = 0;
    $fourDme2 = 0;
    $fourDke2 = 0;
    $fourDme3 = 0;
    $fourDke3 = 0;

    $sixAme = 0;
    $sixAke = 0;
    $sixBme = 0;
    $sixBke = 0;
    $sixCme = 0;
    $sixCke = 0;
    $sevenAme = 0;
    $sevenAke = 0;
    $eightAme = 0;
    $eightAke = 0;
    $eightBme = 0;
    $eightBke = 0;
    $nineAme = 0;
    $nineAke = 0;
    $nineBme = 0;
    $nineBke = 0;
    $nineCme = 0;
    $nineCke = 0;

    $firstphase = mysqli_query($conn,"SELECT * FROM tbl_watoto WHERE Tarehe BETWEEN '$date1' AND '$date2'");
    $Today = Date("Y-m-d");
    while ($result = mysqli_fetch_assoc($firstphase)) {
        $Date_Of_Birth = $result['Birth_date'];
        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y;

//        $moth= $diff->m;
//        $days= $diff->d;
//        
//        echo $age.'&'.$moth.'&'.$days.'<br />';
        if ($result['Birth_reg_No'] != '' && $result['Jinsi'] == 'ME') {
            $birthcertme++;
        } else if ($result['Birth_reg_No'] != '' && $result['Jinsi'] == 'KE') {
            $birthcertke++;
        }



        if (($result['BCG'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'ME') {
            $twoAme++;
        } else if (($result['BCG'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'KE') {
            $twoAke++;
        }


        if (($result['BCG'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'ME') {
            $twoBme++;
        } else if (($result['BCG'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'KE') {
            $twoBke++;
        }

        if (($result['BCG'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'ME') {
            $twoCme++;
        } else if (($result['BCG'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'KE') {
            $twoCke++;
        }


        if (($result['BCG'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'ME') {
            $twoDme++;
        } else if (($result['BCG'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'KE') {
            $twoDke++;
        }

        if (($result['Polio_1'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'ME') {
            $twoEme1++;
        } else if (($result['Polio_1'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'KE') {
            $twoEke1++;
        }

        if (($result['Polio_2'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'ME') {
            $twoEme2++;
        } else if (($result['Polio_2'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'KE') {
            $twoEke2++;
        }

        if (($result['Polio_3'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'ME') {
            $twoEme3++;
        } else if (($result['Polio_3'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'KE') {
            $twoEke3++;
        }

        if (($result['Polio_1'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'ME') {
            $twoFme1++;
        } else if (($result['Polio_1'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'KE') {
            $twoFke1++;
        }

        if (($result['Polio_2'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'ME') {
            $twoFme2++;
        } else if (($result['Polio_2'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'KE') {
            $twoFke2++;
        }

        if (($result['Polio_3'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'ME') {
            $twoFme3++;
        } else if (($result['Polio_3'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'KE') {
            $twoFke3++;
        }


        if (($result['Polio_1'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'ME') {
            $twoGme1++;
        } else if (($result['Polio_1'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'KE') {
            $twoGke1++;
        }

        if (($result['Polio_2'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'ME') {
            $twoGme2++;
        } else if (($result['Polio_2'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'KE') {
            $twoGke2++;
        }

        if (($result['Polio_3'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'ME') {
            $twoGme3++;
        } else if (($result['Polio_3'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'KE') {
            $twoGke3++;
        }


        if (($result['Polio_1'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'ME') {
            $twoHme1++;
        } else if (($result['Polio_1'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'KE') {
            $twoHke1++;
        }

        if (($result['Polio_2'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'ME') {
            $twoHme2++;
        } else if (($result['Polio_2'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'KE') {
            $twoHke2++;
        }

        if (($result['Polio_3'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'ME') {
            $twoHme3++;
        } else if (($result['Polio_3'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'KE') {
            $twoHke3++;
        }


        if (($result['PENTA_1'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'ME') {
            $twoOme1++;
        } else if (($result['PENTA_1'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'KE') {
            $twoOke1++;
        }

        if (($result['PENTA_2'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'ME') {
            $twoOme2++;
        } else if (($result['PENTA_2'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'KE') {
            $twoOke2++;
        }

        if (($result['PENTA_3'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'ME') {
            $twoOme3++;
        } else if (($result['PENTA_3'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'KE') {
            $twoOke3++;
        }


        if (($result['PENTA_1'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'ME') {
            $twoPme1++;
        } else if (($result['PENTA_1'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'KE') {
            $twoPke1++;
        }

        if (($result['PENTA_2'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'ME') {
            $twoPme2++;
        } else if (($result['PENTA_2'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'KE') {
            $twoPke2++;
        }

        if (($result['PENTA_3'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'ME') {
            $twoPme3++;
        } else if (($result['PENTA_3'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'KE') {
            $twoPke3++;
        }


        if (($result['PENTA_1'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'ME') {
            $twoQme1++;
        } else if (($result['PENTA_1'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'KE') {
            $twoQke1++;
        }


        if (($result['PENTA_2'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'ME') {
            $twoQme2++;
        } else if (($result['PENTA_2'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'KE') {
            $twoQke2++;
        }

        if (($result['PENTA_3'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'ME') {
            $twoQme3++;
        } else if (($result['PENTA_3'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'KE') {
            $twoQke3++;
        }


        if (($result['PENTA_1'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'ME') {
            $twoRme1++;
        } else if (($result['PENTA_1'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'KE') {
            $twoRke1++;
        }

        if (($result['PENTA_2'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'ME') {
            $twoRme2++;
        } else if (($result['PENTA_2'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'KE') {
            $twoRke2++;
        }

        if (($result['PENTA_3'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'ME') {
            $twoRme3++;
        } else if (($result['PENTA_3'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'KE') {
            $twoRke3++;
        }


        if (($result['PCV_1'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'ME') {
            $twoSme1++;
        } else if (($result['PCV_1'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'KE') {
            $twoSke1++;
        }

        if (($result['PCV_2'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'ME') {
            $twoSme2++;
        } else if (($result['PCV_2'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'KE') {
            $twoSke2++;
        }


        if (($result['PCV_3'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'ME') {
            $twoSme3++;
        } else if (($result['PCV_3'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'KE') {
            $twoSke3++;
        }




        if (($result['PCV_1'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'ME') {
            $twoTme1++;
        } else if (($result['PCV_1'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'KE') {
            $twoTke1++;
        }

        if (($result['PCV_2'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'ME') {
            $twoTme2++;
        } else if (($result['PCV_2'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'KE') {
            $twoTke2++;
        }

        if (($result['PCV_3'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'ME') {
            $twoTme3++;
        } else if (($result['PCV_3'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'KE') {
            $twoTke3++;
        }


        if (($result['PCV_1'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'ME') {
            $twoUme1++;
        } else if (($result['PCV_1'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'KE') {
            $twoUke1++;
        }

        if (($result['PCV_2'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'ME') {
            $twoUme2++;
        } else if (($result['PCV_2'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'KE') {
            $twoUke2++;
        }


        if (($result['PCV_3'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'ME') {
            $twoUme3++;
        } else if (($result['PCV_3'] != '0000-00-00' && $age < 1) && $result['Jinsi'] == 'KE') {
            $twoUke3++;
        }



        if (($result['PCV_1'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'ME') {
            $twoVme1++;
        } else if (($result['PCV_1'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'KE') {
            $twoVke1++;
        }

        if (($result['PCV_2'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'ME') {
            $twoVme2++;
        } else if (($result['PCV_2'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'KE') {
            $twoVke2++;
        }

        if (($result['PCV_3'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'ME') {
            $twoVme3++;
        } else if (($result['PCV_3'] != '0000-00-00' && $age >= 1) && $result['Jinsi'] == 'KE') {
            $twoVke3++;
        }


        if ($result['Ana_TT2'] == 'N' && $result['Jinsi'] == 'ME') {
            $threeAme++;
        } else if ($result['Ana_TT2'] == 'N' && $result['Jinsi'] == 'KE') {
            $threeAke++;
        }

        if ($result['Ana_TT2'] == 'H' && $result['Jinsi'] == 'ME') {
            $threeBme++;
        } else if ($result['Ana_TT2'] == 'H' && $result['Jinsi'] == 'KE') {
            $threeBke++;
        }

        if ($result['Ana_TT2'] == 'U' && $result['Jinsi'] == 'ME') {
            $threeCme++;
        } else if ($result['Ana_TT2'] == 'U' && $result['Jinsi'] == 'KE') {
            $threeCke++;
        }




        if (($result['Uz_um_9'] != '' || $result['Uz_ur_9'] != '' || $result['Ur_um_9'] != '' || $result['Uz_um_18'] != '' || $result['Uz_ur_18'] != '' || $result['Ur_um_18'] != '' || $result['Uz_um_36'] != '' || $result['Uz_ur_36'] != '' || $result['Ur_um_36'] != '' || $result['Uz_um_48'] != '' || $result['Uz_ur_48'] != '' || $result['Ur_um_48'] != '') && ($age >= 1 && $age <= 5) && $result['Jinsi'] == 'ME') {
            $fourAme++;
        } else if (($result['Uz_um_9'] != '' || $result['Uz_ur_9'] != '' || $result['Ur_um_9'] != '' || $result['Uz_um_18'] != '' || $result['Uz_ur_18'] != '' || $result['Ur_um_18'] != '' || $result['Uz_um_36'] != '' || $result['Uz_ur_36'] != '' || $result['Ur_um_36'] != '' || $result['Uz_um_48'] != '' || $result['Uz_ur_48'] != '' || $result['Ur_um_48'] != '') && ($age >= 1 && $age <= 5) && $result['Jinsi'] == 'KE') {
            $fourAke++;
        }

        
         if (($result['Uz_um_9'] == '1' || $result['Uz_um_18'] == '1' || $result['Uz_um_36'] == '1' || $result['Uz_um_48'] == '1') && ($age >= 1 && $age <= 5) && $result['Jinsi'] == 'ME') {
            $fourBme1++;
        } else if (($result['Uz_um_9'] == '1' || $result['Uz_um_18'] == '1' || $result['Uz_um_36'] == '1' || $result['Uz_um_48'] == '1') && ($age >= 1 && $age <= 5) && $result['Jinsi'] == 'KE') {
            $fourBke1++;
        }

        
         if (($result['Uz_um_9'] == '2' || $result['Uz_um_18'] == '2' || $result['Uz_um_36'] == '2' || $result['Uz_um_48'] == '2') && ($age >= 1 && $age <= 5) && $result['Jinsi'] == 'ME') {
            $fourBme2++;
        } else if (($result['Uz_um_9'] == '2' || $result['Uz_um_18'] == '2' || $result['Uz_um_36'] == '2' || $result['Uz_um_48'] == '2') && ($age >= 1 && $age <= 5) && $result['Jinsi'] == 'KE') {
            $fourBke2++;
        }

        
         if (($result['Uz_um_9'] == '3' || $result['Uz_um_18'] == '3' || $result['Uz_um_36'] == '3' || $result['Uz_um_48'] == '3') && ($age >= 1 && $age <= 5) && $result['Jinsi'] == 'ME') {
            $fourBme3++;
        } else if (($result['Uz_um_9'] == '3' || $result['Uz_um_18'] == '3' || $result['Uz_um_36'] == '3' || $result['Uz_um_48'] == '3') && ($age >= 1 && $age <= 5) && $result['Jinsi'] == 'KE') {
            $fourBke3++;
        }

        
        
         if (($result['Uz_ur_9'] == '1' || $result['Uz_ur_18'] == '1' || $result['Uz_ur_36'] == '1' || $result['Uz_ur_48'] == '1') && ($age >= 1 && $age <= 5) && $result['Jinsi'] == 'ME') {
            $fourCme1++;
        } else if (($result['Uz_ur_9'] == '1' || $result['Uz_ur_18'] == '1' || $result['Uz_ur_36'] == '1' || $result['Uz_ur_48'] == '1') && ($age >= 1 && $age <= 5) && $result['Jinsi'] == 'KE') {
            $fourCke1++;
        }
        
        
         if (($result['Uz_ur_9'] == '2' || $result['Uz_ur_18'] == '2' || $result['Uz_ur_36'] == '2' || $result['Uz_ur_48'] == '2') && ($age >= 1 && $age <= 5) && $result['Jinsi'] == 'ME') {
            $fourCme2++;
        } else if (($result['Uz_ur_9'] == '2' || $result['Uz_ur_18'] == '2' || $result['Uz_ur_36'] == '2' || $result['Uz_ur_48'] == '2') && ($age >= 1 && $age <= 5) && $result['Jinsi'] == 'KE') {
            $fourCke2++;
        }
        
         if (($result['Uz_ur_9'] == '3' || $result['Uz_ur_18'] == '3' || $result['Uz_ur_36'] == '3' || $result['Uz_ur_48'] == '3') && ($age >= 1 && $age <= 5) && $result['Jinsi'] == 'ME') {
            $fourCme3++;
        } else if (($result['Uz_ur_9'] == '3' || $result['Uz_ur_18'] == '3' || $result['Uz_ur_36'] == '3' || $result['Uz_ur_48'] == '3') && ($age >= 1 && $age <= 5) && $result['Jinsi'] == 'KE') {
            $fourCke3++;
        }
        
         if (($result['Ur_um_9'] == '1' || $result['Ur_um_18'] == '1' || $result['Ur_um_36'] == '1' || $result['Ur_um_48'] == '1') && ($age >= 1 && $age <= 5) && $result['Jinsi'] == 'ME') {
            $fourDme1++;
        } else if (($result['Ur_um_9'] == '1' || $result['Ur_um_18'] == '1' || $result['Ur_um_36'] == '1' || $result['Ur_um_48'] == '1') && ($age >= 1 && $age <= 5) && $result['Jinsi'] == 'KE') {
            $fourDke1++;
        }
        
         if (($result['Ur_um_9'] == '2' || $result['Ur_um_18'] == '2' || $result['Ur_um_36'] == '2' || $result['Ur_um_48'] == '2') && ($age >= 1 && $age <= 5) && $result['Jinsi'] == 'ME') {
            $fourDme2++;
        } else if (($result['Ur_um_9'] == '2' || $result['Ur_um_18'] == '2' || $result['Ur_um_36'] == '2' || $result['Ur_um_48'] == '2') && ($age >= 1 && $age <= 5) && $result['Jinsi'] == 'KE') {
            $fourDke2++;
        }
        
        
         if (($result['Ur_um_9'] == '3' || $result['Ur_um_18'] == '3' || $result['Ur_um_36'] == '3' || $result['Ur_um_48'] == '3') && ($age >= 1 && $age <= 5) && $result['Jinsi'] == 'ME') {
            $fourDme3++;
        } else if (($result['Ur_um_9'] == '3' || $result['Ur_um_18'] == '3' || $result['Ur_um_36'] == '3' || $result['Ur_um_48'] == '3') && ($age >= 1 && $age <= 5) && $result['Jinsi'] == 'KE') {
            $fourDke3++;
        }
        


        if ($result['VM_6'] == 'N' && $result['Jinsi'] == 'ME') {
            $sixAme++;
        } else if ($result['VM_6'] == 'N' && $result['Jinsi'] == 'KE') {
            $sixAke++;
        }


        if ($result['V_U_mwaka'] == 'N' && $result['Jinsi'] == 'ME') {
            $sixBme++;
        } else if ($result['V_U_mwaka'] == 'N' && $result['Jinsi'] == 'KE') {
            $sixBke++;
        }

        if ($result['V_mwaka_1_5'] == 'N' && $result['Jinsi'] == 'ME') {
            $sixCme++;
        } else if ($result['V_mwaka_1_5'] == 'N' && $result['Jinsi'] == 'KE') {
            $sixCke++;
        }


        if (($result['AM_12'] == 'N' || $result['AM_18'] == 'N' || $result['AM_24'] == 'N' || $result['AM_30'] == 'N') && ($age >= 1 && $age <= 5) && $result['Jinsi'] == 'ME') {
            $sevenAme++;
        } else if (($result['AM_12'] == 'N' || $result['AM_18'] == 'N' || $result['AM_24'] == 'N' || $result['AM_30'] == 'N') && ($age >= 1 && $age <= 5) && $result['Jinsi'] == 'KE') {
            $sevenAke++;
        }


        if ($result['Mama_maziwa'] == 'N' && $result['Jinsi'] == 'ME') {
            $eightAme++;
        } else if ($result['Mama_maziwa'] == 'N' && $result['Jinsi'] == 'KE') {
            $eightAke++;
        }


        if ($result['maziwa_mbadala'] == 'N' && $result['Jinsi'] == 'ME') {
            $eightBme++;
        } else if ($result['maziwa_mbadala'] == 'N' && $result['Jinsi'] == 'KE') {
            $eightBke++;
        }




        if ($result['VVU_Hali'] == '+ve' && $result['Jinsi'] == 'ME') {
            $nineAme++;
        } else if ($result['VVU_Hali'] == '+ve' && $result['Jinsi'] == 'KE') {
            $nineAke++;
        }

        if ($result['alikopelekwa'] == 'CTC' && $result['Jinsi'] == 'ME') {
            $nineBme++;
        } else if ($result['alikopelekwa'] == 'CTC' && $result['Jinsi'] == 'KE') {
            $nineBke++;
        }

        if ($result['Hati_punguzo'] == 'N' && $result['Jinsi'] == 'ME') {
            $nineCme++;
        } else if ($result['Hati_punguzo'] == 'N' && $result['Jinsi'] == 'KE') {
            $nineCke++;
        }
    }

    $Totalbirthcert = $birthcertme + $birthcertke;
    $TotaltwoA = $twoAme + $twoAke;
    $TotaltwoB = $twoBme + $twoBke;
    $TotaltwoC = $twoCme + $twoCke;
    $TotaltwoD = $twoDme + $twoDke;
    $TotaltwoE1 = $twoEme1 + $twoEke1;
    $TotaltwoE2 = $twoEme2 + $twoEke2;
    $TotaltwoE3 = $twoEme3 + $twoEke3;
    $TotaltwoF1 = $twoFme1 + $twoFke1;
    $TotaltwoF2 = $twoFme2 + $twoFke2;
    $TotaltwoF3 = $twoFme3 + $twoFke3;
    $TotaltwoG1 = $twoGme1 + $twoGke1;
    $TotaltwoG2 = $twoGme2 + $twoGke2;
    $TotaltwoG3 = $twoGme3 + $twoGke3;
    $TotaltwoH1 = $twoHme1 + $twoHke1;
    $TotaltwoH2 = $twoHme2 + $twoHke2;
    $TotaltwoH3 = $twoHme3 + $twoHke3;
    $TotaltwoO1 = $twoOme1 + $twoOke1;
    $TotaltwoO2 = $twoOme2 + $twoOke2;
    $TotaltwoO3 = $twoOme3 + $twoOke3;
    $TotaltwoP1 = $twoPme1 + $twoPke1;
    $TotaltwoP2 = $twoPme2 + $twoPke2;
    $TotaltwoP3 = $twoPme3 + $twoPke3;
    $TotaltwoQ1 = $twoQme1 + $twoQke1;
    $TotaltwoQ2 = $twoQme2 + $twoQke2;
    $TotaltwoQ3 = $twoQme3 + $twoQke3;
    $TotaltwoR1 = $twoRme1 + $twoRke1;
    $TotaltwoR2 = $twoRme2 + $twoRke2;
    $TotaltwoR3 = $twoRme3 + $twoRke3;
    $TotaltwoS1 = $twoSme1 + $twoSke1;
    $TotaltwoS2 = $twoSme2 + $twoSke2;
    $TotaltwoS3 = $twoSme3 + $twoSke3;
    $TotaltwoT1 = $twoTme1 + $twoTke1;
    $TotaltwoT2 = $twoTme2 + $twoTke2;
    $TotaltwoT3 = $twoTme3 + $twoTke3;
    $TotaltwoU1 = $twoUme1 + $twoUke1;
    $TotaltwoU2 = $twoUme2 + $twoUke2;
    $TotaltwoU3 = $twoUme3 + $twoUke3;
    $TotaltwoV1 = $twoVme1 + $twoVke1;
    $TotaltwoV2 = $twoVme2 + $twoVke2;
    $TotaltwoV3 = $twoVme3 + $twoVke3;
    $TotalthreeA = $threeAme + $threeAke;
    $TotalthreeB = $threeBme + $threeBke;
    $TotalthreeC = $threeCme + $threeCke;
    $TotalfourA = $fourAme + $fourAke;
    $TotalfourB1 = $fourBme1 + $fourBke1;
    $TotalfourB2 = $fourBme2 + $fourBke2;
    $TotalfourB3 = $fourBme3 + $fourBke3;
    $TotalfourC1 = $fourCme1 + $fourCke1;
    $TotalfourC2 = $fourCme2 + $fourCke2;
    $TotalfourC3 = $fourCme3 + $fourCke3;
    $TotalfourD1 = $fourDme1 + $fourDke1;
    $TotalfourD2 = $fourDme2 + $fourDke2;
    $TotalfourD3 = $fourDme3 + $fourDke3;
    
    
    $TotalsixA = $sixAme + $sixAke;
    $TotalsixB = $sixBme + $sixBke;
    $TotalsixC = $sixCme + $sixCke;
    $TotalsevenA = $sevenAme + $sevenAke;
    $TotaleightA = $eightAme + $eightAke;
    $TotaleightB = $eightBme + $eightBke;
    $TotalnineA = $nineAme + $nineAke;
    $TotalnineB = $nineBme + $nineBke;
    $TotalnineC = $nineCme + $nineCke;


    $disp= "<div id='all'>";
    $disp.= "<div id='hudhurio1'>";
    $disp.= "<table style='width:100%'>";
    $disp.= "<tr style='background-color:#006400;color:white;padding:2px;text-align:left'><th style='width:5%'>Namba</th><th style='width:40%'>Maelezo</th><th style='20%'></th><th style='25%'>Idadi</th></tr>";
    $disp.= "<tr style='background-color:#006400;color:white;padding:2px;text-align:left'><th style='width:5%'></th><th style='width:40%'></th><th style='width:20%'></th>  <th style='width:25%'><table style='width:100%'><tr><th>ME</th><th>KE</th><th>Jumla</th></tr></table></th></tr>";


    $disp.= "<tr><td style='text-align:center'>1</td><td>Idadi ya watoto walioandikishwa na kupewa vyeti vya kuzaliwa</td><td style='text-align:center'></td> <td style='text-align:center'><table style='width:100%'><tr><th>$birthcertme</th><th>$birthcertke</th><th>$Totalbirthcert</th></tr></table></td></tr>";

    $disp.= "<tr style='background-color:#006400;color:white;padding:2px;text-align:left'><th style='width:5%'>2</th><th style='width:40%;text-align:left'>Aina ya Chanjo kwa umri</th><th style='width:20%'></th>  <th style='width:25%'></th></tr>";
    $disp.= "<tr><td style='text-align:center'>2a</td><td>BCG Umri mwaka < 1 (Ndani ya eneo la huduma)</td><td style='text-align:center'></td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoAme</th><th>$twoAke</th><th>$TotaltwoA</th></tr></table></td></tr>
        
         <tr><td style='text-align:center'>2b</td><td>BCG Umri mwaka 1+ (Ndani ya eneo la huduma)</td><td style='text-align:center'></td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoBme</th><th>$twoBke</th><th>$TotaltwoB</th></tr></table></td></tr>

         <tr><td style='text-align:center'>2c</td><td>BCG Umri mwaka < 1 (Nje ya eneo la huduma)</td><td style='text-align:center'></td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoCme</th><th>$twoCke</th><th>$TotaltwoC</th></tr></table></td></tr>
        
         <tr><td style='text-align:center'>2d</td><td>BCG Umri mwaka 1+ (Nje ya eneo la huduma)</td><td style='text-align:center'></td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoDme</th><th>$twoDke</th><th>$TotaltwoD</th></tr></table></td></tr>
         <tr><td style='text-align:center'>2e</td><td>Polio Umri mwaka < 1 (Ndani ya eneo la huduma)</td><td style='text-align:left'>Dozi 1</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoEme1</th><th>$twoEke1</th><th>$TotaltwoE1</th></tr></table></td></tr>
         
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>Dozi 2</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoEme2</th><th>$twoEke2</th><th>$TotaltwoE2</th></tr></table></td></tr>
         
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>Dozi 3</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoEme3</th><th>$twoEke3</th><th>$TotaltwoE3</th></tr></table></td></tr>
             
         <tr><td style='text-align:center'>2f</td><td>Polio Umri mwaka 1+ (Ndani ya eneo la huduma)</td><td style='text-align:left'>Dozi 1</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoFme1</th><th>$twoFke1</th><th>$TotaltwoF1</th></tr></table></td></tr>
         
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>Dozi 2</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoFme2</th><th>$twoFke2</th><th>$TotaltwoF2</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>Dozi 3</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoFme3</th><th>$twoFke2</th><th>$TotaltwoF2</th></tr></table></td></tr>
        
         <tr><td style='text-align:center'>2g</td><td>Polio Umri mwaka < 1 (Nje ya eneo la huduma)</td><td style='text-align:left'>Dozi 1</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoGme1</th><th>$twoGke1</th><th>$TotaltwoG1</th></tr></table></td></tr>
         
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>Dozi 2</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoGme2</th><th>$twoGke2</th><th>$TotaltwoG2</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>Dozi 3</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoGme3</th><th>$twoGke3</th><th>$TotaltwoG3</th></tr></table></td></tr>
         
         <tr><td style='text-align:center'>2h</td><td>Polio Umri mwaka 1+ (Nje ya eneo la huduma)</td><td style='text-align:left'>Dozi 1</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoHme1</th><th>$twoHke1</th><th>$TotaltwoH1</th></tr></table></td></tr>
         
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>Dozi 2</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoHme2</th><th>$twoHke2</th><th>$TotaltwoH2</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>Dozi 3</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoHme3</th><th>$twoHke3</th><th>$TotaltwoH3</th></tr></table></td></tr>
        
         <tr><td style='text-align:center'>2i</td><td>Polio ya sindano miezi 18 (Ndani ya eneo la huduma)</td><td style='text-align:left'>Dozi 1</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoOme1</th><th>$twoOke1</th><th>$TotaltwoO1</th></tr></table></td></tr>
         
         <tr><td style='text-align:center'>2j</td><td>Polio ya sindano miezi 18 (Nje ya eneo la huduma)</td><td style='text-align:left'>Dozi 1</td> <td style='text-align:center'><table style='width:100%'><tr><th>0</th><th>0</th><th>0</th></tr></table></td></tr>
         <tr><td style='text-align:center'>2k</td><td>Rota umri wiki 6 hadi 15 (Ndani ya eneo la huduma)</td><td style='text-align:left'>Dozi 1</td> <td style='text-align:center'><table style='width:100%'><tr><th>0</th><th>0</th><th>0</th></tr></table></td></tr>
         <tr><td style='text-align:center'>2l</td><td>Rota umri wiki 6 hadi 15 (Nje ya eneo la huduma)</td><td style='text-align:left'>Dozi 1</td> <td style='text-align:center'><table style='width:100%'><tr><th>0</th><th>0</th><th>0</th></tr></table></td></tr>
         <tr><td style='text-align:center'>2m</td><td>Rota umri wiki 10 hadi 32 (Nje ya eneo la huduma)</td><td style='text-align:left'>Dozi 2</td> <td style='text-align:center'><table style='width:100%'><tr><th>0</th><th>0</th><th>0</th></tr></table></td></tr>
         <tr><td style='text-align:center'>2n</td><td>Rota umri wiki 10 hadi 32 (Ndani ya eneo la huduma)</td><td style='text-align:left'>Dozi 2</td> <td style='text-align:center'><table style='width:100%'><tr><th>0</th><th>0</th><th>0</th></tr></table></td></tr>

         
         <tr><td style='text-align:center'>2o</td><td>PENTA Umri mwaka <1 (Ndani ya eneo la huduma)</td><td style='text-align:left'>Dozi 1</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoOme1</th><th>$twoOke1</th><th>$TotaltwoO1</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>Dozi 2</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoOme2</th><th>$twoOke2</th><th>$TotaltwoO2</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>Dozi 3</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoOme3</th><th>$twoOke3</th><th>$TotaltwoO3</th></tr></table></td></tr>
         
         <tr><td style='text-align:center'>2p</td><td>PENTA Umri mwaka 1+ (Ndani ya eneo la huduma)</td><td style='text-align:left'>Dozi 1</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoPme1</th><th>$twoPke1</th><th>$TotaltwoP1</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>Dozi 2</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoPme2</th><th>$twoPke2</th><th>$TotaltwoP2</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>Dozi 3</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoPme3</th><th>$twoPke3</th><th>$TotaltwoP3</th></tr></table></td></tr>
         
         <tr><td style='text-align:center'>2q</td><td>PENTA Umri mwaka <1 (Nje ya eneo la huduma)</td><td style='text-align:left'>Dozi 1</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoQme1</th><th>$twoQke1</th><th>$TotaltwoQ1</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>Dozi 2</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoQme2</th><th>$twoQke2</th><th>$TotaltwoQ2</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>Dozi 3</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoQme3</th><th>$twoQke3</th><th>$TotaltwoQ3</th></tr></table></td></tr>
         
         
         <tr><td style='text-align:center'>2r</td><td>PENTA Umri mwaka 1+ (Nje ya eneo la huduma)</td><td style='text-align:left'>Dozi 1</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoRme1</th><th>$twoRke1</th><th>$TotaltwoR1</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>Dozi 2</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoRme2</th><th>$twoRke2</th><th>$TotaltwoR2</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>Dozi 3</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoRme3</th><th>$twoRke3</th><th>$TotaltwoR3</th></tr></table></td></tr>
         
         <tr><td style='text-align:center'>2s</td><td>Pneumococcal (PCV13) <1 (Ndani ya eneo la huduma)</td><td style='text-align:left'>Dozi 1</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoSme1</th><th>$twoSke1</th><th>$TotaltwoS1</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>Dozi 2</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoSme2</th><th>$twoSke2</th><th>$TotaltwoS2</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>Dozi 3</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoSme3</th><th>$twoSke3</th><th>$TotaltwoS3</th></tr></table></td></tr>

         
         <tr><td style='text-align:center'>2t</td><td>Pneumococcal (PCV13) 1+ (Ndani ya eneo la huduma)</td><td style='text-align:left'>Dozi 1</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoTme1</th><th>$twoTke1</th><th>$TotaltwoT1</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>Dozi 2</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoTme2</th><th>$twoTke2</th><th>$TotaltwoT2</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>Dozi 3</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoTme3</th><th>$twoTke3</th><th>$TotaltwoT3</th></tr></table></td></tr>
         
         <tr><td style='text-align:center'>2u</td><td>Pneumococcal (PCV13) <1 (Nje ya eneo la huduma)</td><td style='text-align:left'>Dozi 1</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoUme1</th><th>$twoUke1</th><th>$TotaltwoU1</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>Dozi 2</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoUme2</th><th>$twoUke2</th><th>$TotaltwoU2</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>Dozi 3</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoUme3</th><th>$twoUke3</th><th>$TotaltwoU3</th></tr></table></td></tr>
         
         <tr><td style='text-align:center'>2v</td><td>Pneumococcal (PCV13) 1+ (Nje ya eneo la huduma)</td><td style='text-align:left'>Dozi 1</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoVme1</th><th>$twoVke1</th><th>$TotaltwoV1</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>Dozi 2</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoVme2</th><th>$twoVke2</th><th>$TotaltwoV2</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>Dozi 3</td> <td style='text-align:center'><table style='width:100%'><tr><th>$twoVme3</th><th>$twoVke3</th><th>$TotaltwoV3</th></tr></table></td></tr>

         <tr><td style='text-align:center'>2w</td><td>Surua/Rubela umri miezi 9 (Ndani ya eneo la huduma)</td><td style='text-align:left'>Dozi 1</td> <td style='text-align:center'><table style='width:100%'><tr><th>0</th><th>0</th><th>0</th></tr></table></td></tr>
         <tr><td style='text-align:center'>2x</td><td>Surua/Rubela umri miezi 9 (Nje ya eneo la huduma)</td><td style='text-align:left'>Dozi 1</td> <td style='text-align:center'><table style='width:100%'><tr><th>0</th><th>0</th><th>0</th></tr></table></td></tr>
         <tr><td style='text-align:center'>2y</td><td>Surua/Rubela umri miezi 18 (Ndani ya eneo la huduma)</td><td style='text-align:left'>Dozi 2</td> <td style='text-align:center'><table style='width:100%'><tr><th>0</th><th>0</th><th>0</th></tr></table></td></tr>
         <tr><td style='text-align:center'>2z</td><td>Surua/Rubela umri miezi 18 (Ndani ya eneo la huduma)</td><td style='text-align:left'>Dozi 2</td> <td style='text-align:center'><table style='width:100%'><tr><th>0</th><th>0</th><th>0</th></tr></table></td></tr>
        ";
    $disp.= "<tr style='background-color:#006400;color:white;padding:2px;text-align:left'><th style='width:5%'>3</th><th style='width:40%;text-align:left'>Hali ya chanjo ya pepopunda kwa mama wakati wa kujifungua</th><th style='width:20%'></th>  <th style='width:25%'></th></tr>";
    $disp.= "<tr><td style='text-align:center'>3a</td><td>Waliokingwa</td><td style='text-align:center'></td> <td style='text-align:center'><table style='width:100%'><tr><th>$threeAme</th><th>$threeAke</th><th>$TotalthreeA</th></tr></table></td></tr>
              <tr><td style='text-align:center'>3b</td><td>Wasikuwa na Kinga</td><td style='text-align:center'></td> <td style='text-align:center'><table style='width:100%'><tr><th>$threeBme</th><th>$threeBke</th><th>$TotalthreeB</th></tr></table></td></tr>
              <tr><td style='text-align:center'>3c</td><td>Haijulikani</td><td style='text-align:center'></td> <td style='text-align:center'><table style='width:100%'><tr><th>$threeCme</th><th>$threeBke</th><th>$TotalthreeC</th></tr></table></td></tr>
       
        ";

    $disp.= "<tr style='background-color:#006400;color:white;padding:2px;text-align:left'><th style='width:5%'>4</th><th style='width:40%;text-align:left'>Mahudhurio na uwiano wa uzito,umri na urefu;umri mwaka 1 mpaka 5</th><th style='width:20%'></th>  <th style='width:25%'></th></tr>";
    $disp.= "
         <tr><td style='text-align:center'>4a</td><td>Jumla ya Mahudhurio ya Watoto</td><td style='text-align:center'></td> <td style='text-align:center'><table style='width:100%'><tr><th>$fourAme</th><th>$fourAke</th><th>$TotalfourA</th></tr></table></td></tr>
         <tr><td style='text-align:center'>4b</td><td>Uwiano wa uzito kwa umri</td><td style='text-align:left'>>80% au >-2SD</td> <td style='text-align:center'><table style='width:100%'><tr><th>$fourBme1</th><th>$fourBke1</th><th>$TotalfourB1</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>60-80% au -2 hadi -3SD</td> <td style='text-align:center'><table style='width:100%'><tr><th>$fourBme2</th><th>$fourBke2</th><th>$TotalfourB2</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'><60% au <-3SD</td> <td style='text-align:center'><table style='width:100%'><tr><th>$fourBme3</th><th>$fourBke3</th><th>$TotalfourB3</th></tr></table></td></tr>
         
         <tr><td style='text-align:center'>4c</td><td>Uwiano wa uzito kwa urefu</td><td style='text-align:left'>>-2SD</td> <td style='text-align:center'><table style='width:100%'><tr><th>$fourCme1</th><th>$fourCke1</th><th>$TotalfourC1</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>-2 hadi -3SD</td> <td style='text-align:center'><table style='width:100%'><tr><th>$fourCme2</th><th>$fourCke2</th><th>$TotalfourC2</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'><-3SD</td> <td style='text-align:center'><table style='width:100%'><tr><th>$fourCme3</th><th>$fourCke3</th><th>$TotalfourC3</th></tr></table></td></tr>
       
         <tr><td style='text-align:center'>4d</td><td>Uwiano wa urefu kwa umri</td><td style='text-align:left'>>-2SD</td> <td style='text-align:center'><table style='width:100%'><tr><th>$fourDme1</th><th>$fourDke1</th><th>$TotalfourD1</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>-2 hadi -3SD</td> <td style='text-align:center'><table style='width:100%'><tr><th>$fourDme2</th><th>$fourDke2</th><th>$TotalfourD2</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'><-3SD</td> <td style='text-align:center'><table style='width:100%'><tr><th>$fourDme3</th><th>$fourDke3</th><th>$TotalfourD3</th></tr></table></td></tr>
    
          ";




    $disp.= "<tr style='background-color:#006400;color:white;padding:2px;text-align:left'><th style='width:5%'>5</th><th style='width:40%;text-align:left'>Mahudhurio na uwiano wa uzito,umri na urefu;umri mwaka 1 mpaka 5</th><th style='width:20%'></th>  <th style='width:25%'></th></tr>";
    $disp.= "
         <tr><td style='text-align:center'>5a</td><td>Jumla ya Mahudhurio ya Watoto</td><td style='text-align:center'></td> <td style='text-align:center'><table style='width:100%'><tr><th>0</th><th>0</th><th>0</th></tr></table></td></tr>
         <tr><td style='text-align:center'>5b</td><td>Uwiano wa uzito kwa umri</td><td style='text-align:left'>>80% au >-2SD</td> <td style='text-align:center'><table style='width:100%'><tr><th>0</th><th>0</th><th>0</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>60-80% au -2 hadi -3SD</td> <td style='text-align:center'><table style='width:100%'><tr><th>0</th><th>0</th><th>0</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'><60% au <-3SD</td> <td style='text-align:center'><table style='width:100%'><tr><th>0</th><th>0</th><th>0</th></tr></table></td></tr>
       
         <tr><td style='text-align:center'>5c</td><td>Uwiano wa uzito kwa urefu</td><td style='text-align:left'>>-2SD</td> <td style='text-align:center'><table style='width:100%'><tr><th>0</th><th>0</th><th>0</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>-2 hadi -3SD</td> <td style='text-align:center'><table style='width:100%'><tr><th>0</th><th>0</th><th>0</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'><-3SD</td> <td style='text-align:center'><table style='width:100%'><tr><th>0</th><th>0</th><th>0</th></tr></table></td></tr>
       
         <tr><td style='text-align:center'>5d</td><td>Uwiano wa urefu kwa umri</td><td style='text-align:left'>>-2SD</td> <td style='text-align:center'><table style='width:100%'><tr><th>0</th><th>0</th><th>0</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'>-2 hadi -3SD</td> <td style='text-align:center'><table style='width:100%'><tr><th>0</th><th>0</th><th>0</th></tr></table></td></tr>
         <tr><td style='text-align:center'></td><td></td><td style='text-align:left'><-3SD</td> <td style='text-align:center'><table style='width:100%'><tr><th>0</th><th>0</th><th>0</th></tr></table></td></tr>
       

          ";


    $disp.= "<tr style='background-color:#006400;color:white;padding:2px;text-align:left'><th style='width:5%'>6</th><th style='width:40%;text-align:left'>Nyongeza ya Vitamini A</th><th style='width:20%'></th>  <th style='width:25%'></th></tr>";
    $disp.= "<tr><td style='text-align:center'>6a</td><td>Watoto umri wa miezi 6</td><td style='text-align:center'></td> <td style='text-align:center'><table style='width:100%'><tr><th>$sixAme</th><th>$sixAke</th><th>$TotalsixA</th></tr></table></td></tr>
              <tr><td style='text-align:center'>6b</td><td>Watoto chini ya umri wa mwaka 1</td><td style='text-align:center'></td> <td style='text-align:center'><table style='width:100%'><tr><th>$sixBme</th><th>$sixBke</th><th>$TotalsixB</th></tr></table></td></tr>
              <tr><td style='text-align:center'>6c</td><td>Watoto umri zaidi ya mwaka 1-5</td><td style='text-align:center'></td> <td style='text-align:center'><table style='width:100%'><tr><th>$sixCme</th><th>$sixCke</th><th>$TotalsixC</th></tr></table></td></tr>
       
        ";


    $disp.= "<tr style='background-color:#006400;color:white;padding:2px;text-align:left'><th style='width:5%'>7</th><th style='width:40%;text-align:left'>Waliopewa Mebendazole/Albendazole</th><th style='width:20%'></th>  <th style='width:25%'></th></tr>";
    $disp.= "<tr><td style='text-align:center'>7a</td><td>Watoto umri wa mwaka 1 hadi 5</td><td style='text-align:center'></td> <td style='text-align:center'><table style='width:100%'><tr><th>$sevenAme</th><th>$sevenAke</th><th>$TotalsevenA</th></tr></table></td></tr>
             
        ";

    $disp.= "<tr style='background-color:#006400;color:white;padding:2px;text-align:left'><th style='width:5%'>8</th><th style='width:40%;text-align:left'>Ulishaji wa Watoto Wachanga</th><th style='width:20%'></th>  <th style='width:25%'></th></tr>";
    $disp.= "<tr><td style='text-align:center'>8a</td><td>Watoto wachanga wanaonyonya maziwa ya mama pekee (EBF)</td><td style='text-align:center'></td> <td style='text-align:center'><table style='width:100%'><tr><th>$eightAme</th><th>$eightAke</th><th>$TotaleightA</th></tr></table></td></tr>
              <tr><td style='text-align:center'>8b</td><td>Watoto wachanga wanaopewa maziwa mbadala(RF)</td><td style='text-align:center'></td> <td style='text-align:center'><table style='width:100%'><tr><th>$eightBme</th><th>$eightBke</th><th>$TotaleightB</th></tr></table></td></tr>
               ";


    $disp.= "<tr style='background-color:#006400;color:white;padding:2px;text-align:left'><th style='width:5%'>9</th><th style='width:40%;text-align:left'>Taarifa za PMTCT/waliopewa hati punguzo</th><th style='width:20%'></th>  <th style='width:25%'></th></tr>";
    $disp.= "<tr><td style='text-align:center'>9a</td><td>Watoto waliozaliwa na mama mwenye maambukizi ya VVU/watoto wenye HEID no</td><td style='text-align:center'></td> <td style='text-align:center'><table style='width:100%'><tr><th>$nineAme</th><th>$nineAke</th><th>$TotalnineA</th></tr></table></td></tr>
              <tr><td style='text-align:center'>9b</td><td>Watoto waliohamishiwa Kliniki ya huduma na matibabu kwa wenye VVU (CTC)</td><td style='text-align:center'></td> <td style='text-align:center'><table style='width:100%'><tr><th>$nineBme</th><th>$nineBke</th><th>$TotalnineB</th></tr></table></td></tr>
              <tr><td style='text-align:center'>9c</td><td>Watoto waliopatiwa hati punguzo ya chandarua</td><td style='text-align:center'></td> <td style='text-align:center'><table style='width:100%'><tr><th>$nineCme</th><th>$nineCke</th><th>$TotalnineC</th></tr></table></td></tr>
       
        ";
    $disp.= "</table>";
    $disp.= "</div>";



include("MPDF/mpdf.php");

$mpdf = new mPDF('', 'Letter-L', 0, '', 12.7, 12.7, 14, 12.7, 8, 8);
$mpdf = new mPDF('c', 'A3-L');

$mpdf->WriteHTML($disp);
$mpdf->Output();
exit;
?>