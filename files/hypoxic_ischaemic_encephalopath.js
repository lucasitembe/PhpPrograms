var app = angular.module('ThompsonModule',[]);
app.controller('ThompsonCtl',function($scope,$http,$timeout){

    $scope.divForm = false;
    $scope.tableDiv = true;
    $scope.lastYears = true;
    $scope.isRefferal = false;
    $scope.success = "";
    $scope.error = "";
    $scope.basics = {};
    $scope.basics1 = {};

    // signs and score
    $scope.Tone ={'Normal':0,'Hyper':1,'Hypo':2,'Flaccid':3};
    $scope.Loc = {'Normal':0,'Hyper alert state':1,'Lethargic':2,'Comatose':3};
    $scope.Fits = {'Normal':0,'Infrequent <3/day':1,'Frequent >2/day':2,'Empty':3};
    $scope.Posture = {'Normal':0,'Fisting, cycling':1,'Strong distal flexion':2,'Decerebrate':3};
    $scope.Moro = {'Normal':0,'Absent':1,'Empty2':2,'Empty3':3};
    $scope.Grasp = {'Normal':0,'Poor':1,'Absent':2,'Empty':3};
    $scope.Suck = {'Normal':0,'Poor':1,'Absent + bites':2,'Empty':3};
    $scope.Respiratory = {'Normal':0,'Hyperventilation':1,'Brief Apnoea':2,'Ventilation':3};
    $scope.Fontanelle = {'Normal':0,'Full, non-tense':1,'Tense':2,'Empty':3};
    $scope.days = [1,2,3,4,5,6,7];

    $scope.tones1 = {};
    $scope.tones2 = {};
    $scope.tones3 = {};
    $scope.tones4 = {};
    $scope.tones5 = {};
    $scope.tones6 = {};
    $scope.tones7 = {};

    $scope.tones11 = {};
    $scope.tones21 = {};
    $scope.tones31 = {};
    $scope.tones41 = {};
    $scope.tones51 = {};
    $scope.tones61 = {};
    $scope.tones71 = {};


    $scope.locs1 = {};
    $scope.locs2 = {};
    $scope.locs3 = {};
    $scope.locs4 = {};
    $scope.locs5 = {};
    $scope.locs6 = {};
    $scope.locs7 = {};

    $scope.locs11 = {};
    $scope.locs21 = {};
    $scope.locs31 = {};
    $scope.locs41 = {};
    $scope.locs51 = {};
    $scope.locs61 = {};
    $scope.locs71 = {};


    $scope.fits1 = {};
    $scope.fits2 = {};
    $scope.fits3 = {};
    $scope.fits4 = {};
    $scope.fits5 = {};
    $scope.fits6 = {};
    $scope.fits7 = {};

    $scope.fits11 = {};
    $scope.fits21 = {};
    $scope.fits31 = {};
    $scope.fits41 = {};
    $scope.fits51 = {};
    $scope.fits61 = {};
    $scope.fits71 = {};


    $scope.postures1 = {};
    $scope.postures2 = {};
    $scope.postures3 = {};
    $scope.postures4 = {};
    $scope.postures5 = {};
    $scope.postures6 = {};
    $scope.postures7 = {};

    $scope.postures11 = {};
    $scope.postures21 = {};
    $scope.postures31 = {};
    $scope.postures41 = {};
    $scope.postures51 = {};
    $scope.postures61 = {};
    $scope.postures71 = {};


    $scope.moros1 = {};
    $scope.moros2 = {};
    $scope.moros3 = {};
    $scope.moros4 = {};
    $scope.moros5 = {};
    $scope.moros6 = {};
    $scope.moros7 = {};

    $scope.moros11 = {};
    $scope.moros21 = {};
    $scope.moros31 = {};
    $scope.moros41 = {};
    $scope.moros51 = {};
    $scope.moros61 = {};
    $scope.moros71 = {};


    $scope.grasps1 = {};
    $scope.grasps2 = {};
    $scope.grasps3 = {};
    $scope.grasps4 = {};
    $scope.grasps5 = {};
    $scope.grasps6 = {};
    $scope.grasps7 = {};

    $scope.grasps11 = {};
    $scope.grasps21 = {};
    $scope.grasps31 = {};
    $scope.grasps41 = {};
    $scope.grasps51 = {};
    $scope.grasps61 = {};
    $scope.grasps71 = {};

    $scope.sucks1 = {};
    $scope.sucks2 = {};
    $scope.sucks3 = {};
    $scope.sucks4 = {};
    $scope.sucks5 = {};
    $scope.sucks6 = {};
    $scope.sucks7 = {};

    $scope.sucks11 = {};
    $scope.sucks21 = {};
    $scope.sucks31 = {};
    $scope.sucks41 = {};
    $scope.sucks51 = {};
    $scope.sucks61 = {};
    $scope.sucks71 = {};


    $scope.respiratories1 = {};
    $scope.respiratories2 = {};
    $scope.respiratories3 = {};
    $scope.respiratories4 = {};
    $scope.respiratories5 = {};
    $scope.respiratories6 = {};
    $scope.respiratories7 = {};

    $scope.respiratories11 = {};
    $scope.respiratories21 = {};
    $scope.respiratories31 = {};
    $scope.respiratories41 = {};
    $scope.respiratories51 = {};
    $scope.respiratories61 = {};
    $scope.respiratories71 = {};


    $scope.fontanelle1 = {};
    $scope.fontanelle2 = {};
    $scope.fontanelle3 = {};
    $scope.fontanelle4 = {};
    $scope.fontanelle5 = {};
    $scope.fontanelle6 = {};
    $scope.fontanelle7 = {};

    $scope.fontanelle11 = {};
    $scope.fontanelle21 = {};
    $scope.fontanelle31 = {};
    $scope.fontanelle41 = {};
    $scope.fontanelle51 = {};
    $scope.fontanelle61 = {};
    $scope.fontanelle71 = {};




      $scope.d1Total = 0;
      $scope.d2Total = 0;
      $scope.d3Total = 0;
      $scope.d4Total = 0;
      $scope.d5Total = 0;
      $scope.d6Total = 0;
      $scope.d7Total = 0;

      $scope.d1Total1 = 0;
      $scope.d2Total1 = 0;
      $scope.d3Total1 = 0;
      $scope.d4Total1 = 0;
      $scope.d5Total1 = 0;
      $scope.d6Total1 = 0;
      $scope.d7Total1 = 0;


      //get all functions Which called by year
      $scope.getAll = function()
      {
        var y = document.getElementById('year').value;
        $scope.getBasicInfoByYear(y);
        $scope.getTone1ByYear(y);
        $scope.getTone2ByYear(y);
        $scope.getTone3ByYear(y);
        $scope.getTone4ByYear(y);
        $scope.getTone5ByYear(y);
        $scope.getTone6ByYear(y);
        $scope.getTone7ByYear(y);

        $scope.getLOC1ByYear(y);
        $scope.getLOC2ByYear(y);
        $scope.getLOC3ByYear(y);
        $scope.getLOC4ByYear(y);
        $scope.getLOC5ByYear(y);
        $scope.getLOC6ByYear(y);
        $scope.getLOC7ByYear(y);

        $scope.getFits1ByYear(y);
        $scope.getFits2ByYear(y);
        $scope.getFits3ByYear(y);
        $scope.getFits4ByYear(y);
        $scope.getFits5ByYear(y);
        $scope.getFits6ByYear(y);
        $scope.getFits7ByYear(y);

        $scope.getPosture1ByYear(y);
        $scope.getPosture2ByYear(y);
        $scope.getPosture3ByYear(y);
        $scope.getPosture4ByYear(y);
        $scope.getPosture5ByYear(y);
        $scope.getPosture6ByYear(y);
        $scope.getPosture7ByYear(y);

        $scope.getMoro1ByYear(y);
        $scope.getMoro2ByYear(y);
        $scope.getMoro3ByYear(y);
        $scope.getMoro4ByYear(y);
        $scope.getMoro5ByYear(y);
        $scope.getMoro6ByYear(y);
        $scope.getMoro7ByYear(y);

        $scope.getGrasp1ByYear(y);
        $scope.getGrasp2ByYear(y);
        $scope.getGrasp3ByYear(y);
        $scope.getGrasp4ByYear(y);
        $scope.getGrasp5ByYear(y);
        $scope.getGrasp6ByYear(y);
        $scope.getGrasp7ByYear(y);

        $scope.getSuck1ByYear(y);
        $scope.getSuck2ByYear(y);
        $scope.getSuck3ByYear(y);
        $scope.getSuck4ByYear(y);
        $scope.getSuck5ByYear(y);
        $scope.getSuck6ByYear(y);
        $scope.getSuck7ByYear(y);

        $scope.getRespiratory1ByYear(y);
        $scope.getRespiratory2ByYear(y);
        $scope.getRespiratory3ByYear(y);
        $scope.getRespiratory4ByYear(y);
        $scope.getRespiratory5ByYear(y);
        $scope.getRespiratory6ByYear(y);
        $scope.getRespiratory7ByYear(y);

        $scope.getFontanelle1ByYear(y);
        $scope.getFontanelle2ByYear(y);
        $scope.getFontanelle3ByYear(y);
        $scope.getFontanelle4ByYear(y);
        $scope.getFontanelle5ByYear(y);
        $scope.getFontanelle6ByYear(y);
        $scope.getFontanelle7ByYear(y);

        $scope.sumDay1ByYear(y);
        $scope.sumDay2ByYear(y);
        $scope.sumDay3ByYear(y);
        $scope.sumDay4ByYear(y);
        $scope.sumDay5ByYear(y);
        $scope.sumDay6ByYear(y);
        $scope.sumDay7ByYear(y);

      }


    // initializer
    $scope.initilizer = function()
    {
      $scope.getBasicInfo();
      $scope.getTone1();
      $scope.getTone2();
      $scope.getTone3();
      $scope.getTone4();
      $scope.getTone5();
      $scope.getTone6();
      $scope.getTone7();

      $scope.getLOC1();
      $scope.getLOC2();
      $scope.getLOC3();
      $scope.getLOC4();
      $scope.getLOC5();
      $scope.getLOC6();
      $scope.getLOC7();

      $scope.getFits1();
      $scope.getFits2();
      $scope.getFits3();
      $scope.getFits4();
      $scope.getFits5();
      $scope.getFits6();
      $scope.getFits7();

      $scope.getPosture1();
      $scope.getPosture2();
      $scope.getPosture3();
      $scope.getPosture4();
      $scope.getPosture5();
      $scope.getPosture6();
      $scope.getPosture7();

      $scope.getMoro1();
      $scope.getMoro2();
      $scope.getMoro3();
      $scope.getMoro4();
      $scope.getMoro5();
      $scope.getMoro6();
      $scope.getMoro7();

      $scope.getGrasp1();
      $scope.getGrasp2();
      $scope.getGrasp3();
      $scope.getGrasp4();
      $scope.getGrasp5();
      $scope.getGrasp6();
      $scope.getGrasp7();

      $scope.getSuck1();
      $scope.getSuck2();
      $scope.getSuck3();
      $scope.getSuck4();
      $scope.getSuck5();
      $scope.getSuck6();
      $scope.getSuck7();

      $scope.getRespiratory1();
      $scope.getRespiratory2();
      $scope.getRespiratory3();
      $scope.getRespiratory4();
      $scope.getRespiratory5();
      $scope.getRespiratory6();
      $scope.getRespiratory7();

      $scope.getFontanelle1();
      $scope.getFontanelle2();
      $scope.getFontanelle3();
      $scope.getFontanelle4();
      $scope.getFontanelle5();
      $scope.getFontanelle6();
      $scope.getFontanelle7();

      $scope.sumDay1();
      $scope.sumDay2();
      $scope.sumDay3();
      $scope.sumDay4();
      $scope.sumDay5();
      $scope.sumDay6();
      $scope.sumDay7();

    }



 //sum day1
 $scope.sumDay1 = function()
 {
   var Registration_ID = document.querySelector('#Registration_ID').value;
   var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=sum_d1&Registration_ID="+Registration_ID+"";

   $http.get(url).then(function(res){
     $scope.d1Total = res.data;
     console.log($scope.d1Total);
   });
 }


 //sum day1 by year
 $scope.sumDay1ByYear = function(y)
 {
   var Registration_ID = document.querySelector('#Registration_ID').value;
   var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=sum_d11&Registration_ID="+Registration_ID+"&year="+y+"";

   $http.get(url).then(function(res){
     $scope.d1Total1 = res.data;
     console.log($scope.d1Total1);
   });
 }






 //sum day2
 $scope.sumDay2 = function()
 {
   var Registration_ID = document.querySelector('#Registration_ID').value;
   var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=sum_d2&Registration_ID="+Registration_ID+"";

   $http.get(url).then(function(res){
     $scope.d2Total = res.data;
   });

 }


 //sum day2 by year
 $scope.sumDay2ByYear = function(y)
 {
   var Registration_ID = document.querySelector('#Registration_ID').value;
   var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=sum_d21&Registration_ID="+Registration_ID+"&year="+y+"";

   $http.get(url).then(function(res){
     $scope.d2Total1 = res.data;
   });

 }






 //sum day3
 $scope.sumDay3 = function()
 {
   var Registration_ID = document.querySelector('#Registration_ID').value;
   var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=sum_d3&Registration_ID="+Registration_ID+"";

   $http.get(url).then(function(res){
     $scope.d3Total = res.data;
   });
 }



 //sum day3 by year
 $scope.sumDay3ByYear = function(y)
 {
   var Registration_ID = document.querySelector('#Registration_ID').value;
   var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=sum_d31&Registration_ID="+Registration_ID+"&year="+y+"";

   $http.get(url).then(function(res){
     $scope.d3Total1 = res.data;
   });
 }



 //sum day4
 $scope.sumDay4 = function()
 {
   var Registration_ID = document.querySelector('#Registration_ID').value;
   var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=sum_d4&Registration_ID="+Registration_ID+"";

   $http.get(url).then(function(res){
     $scope.d4Total = res.data;
   });

 }


 //sum day4 by year
 $scope.sumDay4ByYear = function(y)
 {
   var Registration_ID = document.querySelector('#Registration_ID').value;
   var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=sum_d41&Registration_ID="+Registration_ID+"&year="+y+"";

   $http.get(url).then(function(res){
     $scope.d4Total1 = res.data;
   });

 }



 //sum day5
 $scope.sumDay5 = function()
 {
   var Registration_ID = document.querySelector('#Registration_ID').value;
   var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=sum_d5&Registration_ID="+Registration_ID+"";

   $http.get(url).then(function(res){
     $scope.d5Total = res.data;
   });

 }



 //sum day5 by year
 $scope.sumDay5ByYear = function(y)
 {
   var Registration_ID = document.querySelector('#Registration_ID').value;
   var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=sum_d51&Registration_ID="+Registration_ID+"&year="+y+"";

   $http.get(url).then(function(res){
     $scope.d5Total1 = res.data;
   });

 }



 //sum day6
 $scope.sumDay6 = function()
 {
   var Registration_ID = document.querySelector('#Registration_ID').value;
   var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=sum_d6&Registration_ID="+Registration_ID+"";

   $http.get(url).then(function(res){
     $scope.d6Total = res.data;
   });

 }


 //sum day6 by year
 $scope.sumDay6ByYear = function(y)
 {
   var Registration_ID = document.querySelector('#Registration_ID').value;
   var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=sum_d61&Registration_ID="+Registration_ID+"&year="+y+"";

   $http.get(url).then(function(res){
     $scope.d6Total1 = res.data;
   });

 }



 //sum day7
 $scope.sumDay7 = function()
 {
   var Registration_ID = document.querySelector('#Registration_ID').value;
   var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=sum_d7&Registration_ID="+Registration_ID+"";

   $http.get(url).then(function(res){
     $scope.d7Total = res.data;
   });

 }



 //sum day7 by year
 $scope.sumDay7ByYear = function(y)
 {
   var Registration_ID = document.querySelector('#Registration_ID').value;
   var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=sum_d71&Registration_ID="+Registration_ID+"&year="+y+"";

   $http.get(url).then(function(res){
     $scope.d7Total1 = res.data;
   });

 }




    // ************************** Tones ******************************************************************************************************************

    // get tone
    $scope.getTone1 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_tone1&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.tones1 = res.data;
        console.log($scope.tones1);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get tone by year
    $scope.getTone1ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_tone11&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.tones11 = res.data;
        console.log($scope.tones11);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get tone2
    $scope.getTone2 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_tone2&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.tones2 = res.data;
        console.log($scope.tones2);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get tone2 by year
    $scope.getTone2ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_tone21&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.tones21 = res.data;
        console.log($scope.tones21);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get tone3
    $scope.getTone3 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_tone3&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.tones3 = res.data;
        console.log($scope.tones3);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get tone3 by year
    $scope.getTone3ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_tone31&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.tones31 = res.data;
        console.log($scope.tones31);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get tone4
    $scope.getTone4 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_tone4&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.tones4 = res.data;
        console.log($scope.tones4);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get tone4 by year
    $scope.getTone4ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_tone41&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.tones41 = res.data;
        console.log($scope.tones41);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get tone5
    $scope.getTone5 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_tone5&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.tones5 = res.data;
        console.log($scope.tones5);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }



    // get tone5
    $scope.getTone5ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_tone51&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.tones51 = res.data;
        console.log($scope.tones51);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get tone6
    $scope.getTone6 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_tone6&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.tones6 = res.data;
        console.log($scope.tones6);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get tone6 by year
    $scope.getTone6ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_tone61&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.tones61 = res.data;
        console.log($scope.tones61);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }



    // get tone7
    $scope.getTone7 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_tone7&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.tones7 = res.data;
        console.log($scope.tones7);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get tone7 by year
    $scope.getTone7ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_tone71&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.tones71 = res.data;
        console.log($scope.tones71);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }
    // ************************************ end tones ***********************************************************************************************************




// ************************************ LOC ***********************************************************************************************************
    // get LOC1
    $scope.getLOC1 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_loc1&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.locs1 = res.data;
        console.log($scope.locs1);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get LOC1 by year
    $scope.getLOC1ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_loc11&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.locs11 = res.data;
        console.log($scope.locs11);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }



    // get LOC2
    $scope.getLOC2 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_loc2&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.locs2 = res.data;
        console.log($scope.locs2);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get LOC2 by year
    $scope.getLOC2ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_loc21&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.locs21 = res.data;
        console.log($scope.locs21);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get LOC3
    $scope.getLOC3 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_loc3&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.locs3 = res.data;
        console.log($scope.locs3);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }



    // get LOC3 by year
    $scope.getLOC3ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_loc31&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.locs31 = res.data;
        console.log($scope.locs31);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get LOC4
    $scope.getLOC4 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_loc4&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.locs4 = res.data;
        console.log($scope.locs4);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get LOC4 by year
    $scope.getLOC4ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_loc41&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.locs41 = res.data;
        console.log($scope.locs41);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get LOC5
    $scope.getLOC5 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_loc5&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.locs5 = res.data;
        console.log($scope.locs5);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }



    // get LOC5 by year
    $scope.getLOC5ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_loc51&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.locs51 = res.data;
        console.log($scope.locs51);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get LOC6
    $scope.getLOC6 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_loc6&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.locs6 = res.data;
        console.log($scope.locs6);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get LOC6 by year
    $scope.getLOC6ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_loc61&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.locs61 = res.data;
        console.log($scope.locs61);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get LOC7
    $scope.getLOC7 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_loc7&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.locs7 = res.data;
        console.log($scope.locs7);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get LOC7 by year
    $scope.getLOC7ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_loc71&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.locs71 = res.data;
        console.log($scope.locs71);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // ************************************  end LOC ***********************************************************************************************************





    // *************************** Fits ************************************************************************************************************************

    // get Fits
    $scope.getFits1 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fits1&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.fits1 = res.data;
        console.log($scope.fits1);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Fits by year
    $scope.getFits1ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fits11&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.fits11 = res.data;
        console.log($scope.fits11);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Fits2
    $scope.getFits2 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fits2&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.fits2 = res.data;
        console.log($scope.fits2);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }



    // get Fits2 by year
    $scope.getFits2ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fits21&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.fits21 = res.data;
        console.log($scope.fits21);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Fits3
    $scope.getFits3 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fits3&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.fits3 = res.data;
        console.log($scope.fits3);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Fits3 by year
    $scope.getFits3ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fits31&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.fits31 = res.data;
        console.log($scope.fits31);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Fits4
    $scope.getFits4 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fits4&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.fits4 = res.data;
        console.log($scope.fits4);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Fits4 by year
    $scope.getFits4ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fits41&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.fits41 = res.data;
        console.log($scope.fits41);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Fits5
    $scope.getFits5 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fits5&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.fits5 = res.data;
        console.log($scope.fits5);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Fits5 by year
    $scope.getFits5ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fits51&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.fits51 = res.data;
        console.log($scope.fits51);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Fits6
    $scope.getFits6 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fits6&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.fits6 = res.data;
        console.log($scope.fits6);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Fits6 by year
    $scope.getFits6ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fits61&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.fits61 = res.data;
        console.log($scope.fits61);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }

    // get Fits7
    $scope.getFits7 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fits7&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.fits7 = res.data;
        console.log($scope.fits7);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Fits7 by year
    $scope.getFits7ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fits71&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.fits71 = res.data;
        console.log($scope.fits71);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }

    // *************************** end Fits ************************************************************************************************************************




 // *************************** Posture ************************************************************************************************************************

    // get Posture1
    $scope.getPosture1 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_posture1&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.postures1 = res.data;
        console.log($scope.postures1);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }



    // get Posture1 by year
    $scope.getPosture1ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_posture11&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.postures11 = res.data;
        console.log($scope.postures11);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }



    // get Posture2
    $scope.getPosture2 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_posture2&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.postures2 = res.data;
        console.log($scope.postures2);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }

    // get Posture2 by year
    $scope.getPosture2ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_posture21&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.postures21 = res.data;
        console.log($scope.postures21);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }

    // get Posture3
    $scope.getPosture3 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_posture3&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.postures3 = res.data;
        console.log($scope.postures3);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Posture3 by year
    $scope.getPosture3ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_posture31&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.postures31 = res.data;
        console.log($scope.postures31);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Posture4
    $scope.getPosture4 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_posture4&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.postures4 = res.data;
        console.log($scope.postures4);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Posture4 by year
    $scope.getPosture4ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_posture41&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.postures41 = res.data;
        console.log($scope.postures41);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Posture5
    $scope.getPosture5 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_posture5&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.postures5 = res.data;
        console.log($scope.postures5);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Posture5 by year
    $scope.getPosture5ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_posture51&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.postures51 = res.data;
        console.log($scope.postures51);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Posture6
    $scope.getPosture6 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_posture6&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.postures6 = res.data;
        console.log($scope.postures6);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Posture6 by year
    $scope.getPosture6ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_posture61&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.postures61 = res.data;
        console.log($scope.postures61);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Posture7
    $scope.getPosture7 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_posture7&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.postures7 = res.data;
        console.log($scope.postures7);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Posture7 by year
    $scope.getPosture7ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_posture71&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.postures71 = res.data;
        console.log($scope.postures71);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }




    // *************************** end Posture ************************************************************************************************************************





// *************************** Moro ************************************************************************************************************************

    // get Moro1
    $scope.getMoro1 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_moro1&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.moros1 = res.data;
        console.log($scope.moros1);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }



    // get Moro1 by year
    $scope.getMoro1ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_moro11&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.moros11 = res.data;
        console.log($scope.moros11);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Moro2
    $scope.getMoro2 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_moro2&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.moros2 = res.data;
        console.log($scope.moros2);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Moro2 by year
    $scope.getMoro2ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_moro21&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.moros21 = res.data;
        console.log($scope.moros21);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }



    // get Moro3
    $scope.getMoro3 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_moro3&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.moros3 = res.data;
        console.log($scope.moros3);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Moro3 by year
    $scope.getMoro3ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_moro31&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.moros31 = res.data;
        console.log($scope.moros31);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }



    // get Moro4
    $scope.getMoro4 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_moro4&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.moros4 = res.data;
        console.log($scope.moros4);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Moro4 by year
    $scope.getMoro4ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_moro41&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.moros41 = res.data;
        console.log($scope.moros41);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Moro5
    $scope.getMoro5 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_moro5&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.moros5 = res.data;
        console.log($scope.moros5);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }



    // get Moro5 by year
    $scope.getMoro5ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_moro51&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.moros51 = res.data;
        console.log($scope.moros51);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }



    // get Moro6
    $scope.getMoro6 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_moro6&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.moros6 = res.data;
        console.log($scope.moros6);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Moro6 by year
    $scope.getMoro6ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_moro61&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.moros61 = res.data;
        console.log($scope.moros61);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }



    // get Moro7
    $scope.getMoro7 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_moro7&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.moros7 = res.data;
        console.log($scope.moros7);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }

    // get Moro7 by year
    $scope.getMoro7ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_moro71&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.moros71 = res.data;
        console.log($scope.moros71);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }



    // *************************** end Moro ************************************************************************************************************************




// *************************** Grasp ************************************************************************************************************************
    // get Grasp1
    $scope.getGrasp1 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_grasp1&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.grasps1 = res.data;
        console.log($scope.grasps1);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }

    // get Grasp1 by year
    $scope.getGrasp1ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_grasp11&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.grasps11 = res.data;
        console.log($scope.grasps11);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Grasp2
    $scope.getGrasp2 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_grasp2&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.grasps2 = res.data;
        console.log($scope.grasps2);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }



    // get Grasp2 by year
    $scope.getGrasp2ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_grasp21&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.grasps21 = res.data;
        console.log($scope.grasps21);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }

    // get Grasp3
    $scope.getGrasp3 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_grasp3&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.grasps3 = res.data;
        console.log($scope.grasps3);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Grasp3 by year
    $scope.getGrasp3ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_grasp31&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.grasps31 = res.data;
        console.log($scope.grasps31);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Grasp4
    $scope.getGrasp4 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_grasp4&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.grasps4 = res.data;
        console.log($scope.grasps4);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }



    // get Grasp4 by year
    $scope.getGrasp4ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_grasp41&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.grasps41 = res.data;
        console.log($scope.grasps41);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Grasp5
    $scope.getGrasp5 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_grasp5&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.grasps5 = res.data;
        console.log($scope.grasps5);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Grasp5 by year
    $scope.getGrasp5ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_grasp51&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.grasps51 = res.data;
        console.log($scope.grasps51);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Grasp6
    $scope.getGrasp6 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_grasp6&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.grasps6 = res.data;
        console.log($scope.grasps6);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Grasp6 by year
    $scope.getGrasp6ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_grasp61&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.grasps61 = res.data;
        console.log($scope.grasps61);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Grasp7
    $scope.getGrasp7 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_grasp7&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.grasps7 = res.data;
        console.log($scope.grasps7);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Grasp7 by year
    $scope.getGrasp7ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_grasp71&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.grasps71 = res.data;
        console.log($scope.grasps71);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }





    // *************************** end Grasp ************************************************************************************************************************





// *************************** Suck ************************************************************************************************************************
    // get Suck1
    $scope.getSuck1 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_suck1&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.sucks1 = res.data;
        console.log($scope.sucks1);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }




// get Suck1 by year
$scope.getSuck1ByYear = function(y)
{
  var Registration_ID = document.querySelector('#Registration_ID').value;
  var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_suck11&Registration_ID="+Registration_ID+"&year="+y+"";
  $http.get(url).then(function(res){
    $scope.sucks11 = res.data;
    console.log($scope.sucks11);
  },function(err){
    $scope.error =  err.status;
    console.log($scope.error);
  });

}

    // get Suck2
    $scope.getSuck2 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_suck2&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.sucks2 = res.data;
        console.log($scope.sucks2);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Suck2 by year
    $scope.getSuck2ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_suck21&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.sucks21 = res.data;
        console.log($scope.sucks21);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Suck3
    $scope.getSuck3 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_suck3&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.sucks3 = res.data;
        console.log($scope.sucks3);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }



    // get Suck3 by year
    $scope.getSuck3ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_suck31&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.sucks31 = res.data;
        console.log($scope.sucks31);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }



    // get Suck4
    $scope.getSuck4 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_suck4&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.sucks4 = res.data;
        console.log($scope.sucks4);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Suck4 by year
    $scope.getSuck4ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_suck41&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.sucks41 = res.data;
        console.log($scope.sucks41);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }



    // get Suck5
    $scope.getSuck5 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_suck5&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.sucks5 = res.data;
        console.log($scope.sucks5);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Suck5 by year
    $scope.getSuck5ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_suck51&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.sucks51 = res.data;
        console.log($scope.sucks51);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Suck6
    $scope.getSuck6 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_suck6&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.sucks6 = res.data;
        console.log($scope.sucks6);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Suck6 by year
    $scope.getSuck6ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_suck61&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.sucks61 = res.data;
        console.log($scope.sucks61);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Suck7
    $scope.getSuck7 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_suck7&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.sucks7 = res.data;
        console.log($scope.sucks7);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Suck7 by year
    $scope.getSuck7ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_suck71&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.sucks71 = res.data;
        console.log($scope.sucks71);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }




// *************************** end Suck ************************************************************************************************************************




// *************************** Respiratory ************************************************************************************************************************
    // get Respiratory1
    $scope.getRespiratory1 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_respiratory1&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.respiratories1 = res.data;
        console.log($scope.respiratories1);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }




// get Respiratory1 by year
$scope.getRespiratory1ByYear = function(y)
{
  var Registration_ID = document.querySelector('#Registration_ID').value;
  var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_respiratory11&Registration_ID="+Registration_ID+"&year="+y+"";
  $http.get(url).then(function(res){
    $scope.respiratories11 = res.data;
    console.log($scope.respiratories11);
  },function(err){
    $scope.error =  err.status;
    console.log($scope.error);
  });

}


    // get Respiratory2
    $scope.getRespiratory2 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_respiratory2&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.respiratories2 = res.data;
        console.log($scope.respiratories2);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }



    // get Respiratory2 by year
    $scope.getRespiratory2ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_respiratory21&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.respiratories21 = res.data;
        console.log($scope.respiratories21);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Respiratory3
    $scope.getRespiratory3 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_respiratory3&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.respiratories3 = res.data;
        console.log($scope.respiratories3);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }



    // get Respiratory3 by year
    $scope.getRespiratory3ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_respiratory31&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.respiratories31 = res.data;
        console.log($scope.respiratories31);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Respiratory4
    $scope.getRespiratory4 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_respiratory4&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.respiratories4 = res.data;
        console.log($scope.respiratories4);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Respiratory4 by year
    $scope.getRespiratory4ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_respiratory41&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.respiratories41 = res.data;
        console.log($scope.respiratories41);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Respiratory5
    $scope.getRespiratory5 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_respiratory5&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.respiratories5 = res.data;
        console.log($scope.respiratories5);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Respiratory5 by year
    $scope.getRespiratory5ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_respiratory51&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.respiratories51 = res.data;
        console.log($scope.respiratories51);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Respiratory6
    $scope.getRespiratory6 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_respiratory6&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.respiratories6 = res.data;
        console.log($scope.respiratories6);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Respiratory6 by year
    $scope.getRespiratory6ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_respiratory61&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.respiratories61 = res.data;
        console.log($scope.respiratories61);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Respiratory7
    $scope.getRespiratory7 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_respiratory7&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.respiratories7 = res.data;
        console.log($scope.respiratories7);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Respiratory7 by year
    $scope.getRespiratory7ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_respiratory71&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.respiratories71 = res.data;
        console.log($scope.respiratories71);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }

    // *************************** end Respiratory ************************************************************************************************************************



// *************************** Fontanelle ************************************************************************************************************************
    // get Fontanelle1
    $scope.getFontanelle1 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fontanelle1&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.fontanelle1 = res.data;
        console.log($scope.fontanelle1);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }




// get Fontanelle1 by year
$scope.getFontanelle1ByYear = function(y)
{
  var Registration_ID = document.querySelector('#Registration_ID').value;
  var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fontanelle11&Registration_ID="+Registration_ID+"&year="+y+"";
  $http.get(url).then(function(res){
    $scope.fontanelle11 = res.data;
    console.log($scope.fontanelle11);
  },function(err){
    $scope.error =  err.status;
    console.log($scope.error);
  });

}

    // get Fontanelle2
    $scope.getFontanelle2 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fontanelle2&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.fontanelle2 = res.data;
        console.log($scope.fontanelle2);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Fontanelle2 by year
    $scope.getFontanelle2ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fontanelle21&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.fontanelle21 = res.data;
        console.log($scope.fontanelle21);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Fontanelle3
    $scope.getFontanelle3 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fontanelle3&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.fontanelle3 = res.data;
        console.log($scope.fontanelle3);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Fontanelle3 by year
    $scope.getFontanelle3ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fontanelle31&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.fontanelle31 = res.data;
        console.log($scope.fontanelle31);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }



    // get Fontanelle4
    $scope.getFontanelle4 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fontanelle4&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.fontanelle4 = res.data;
        console.log($scope.fontanelle4);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }



    // get Fontanelle4 by year
    $scope.getFontanelle4ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fontanelle41&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.fontanelle41 = res.data;
        console.log($scope.fontanelle41);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Fontanelle5
    $scope.getFontanelle5 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fontanelle5&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.fontanelle5 = res.data;
        console.log($scope.fontanelle5);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Fontanelle5 by year
    $scope.getFontanelle5ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fontanelle51&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.fontanelle51 = res.data;
        console.log($scope.fontanelle51);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Fontanelle6
    $scope.getFontanelle6 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fontanelle6&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.fontanelle6 = res.data;
        console.log($scope.fontanelle6);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Fontanelle6 by year
    $scope.getFontanelle6ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fontanelle61&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.fontanelle61 = res.data;
        console.log($scope.fontanelle61);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }

    // get Fontanelle7
    $scope.getFontanelle7 = function()
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fontanelle7&Registration_ID="+Registration_ID+"";
      $http.get(url).then(function(res){
        $scope.fontanelle7 = res.data;
        console.log($scope.fontanelle7);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }


    // get Fontanelle7 by year
    $scope.getFontanelle7ByYear = function(y)
    {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=get_fontanelle71&Registration_ID="+Registration_ID+"&year="+y+"";
      $http.get(url).then(function(res){
        $scope.fontanelle71 = res.data;
        console.log($scope.fontanelle71);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

    }

    // *************************** end Fontanelle ************************************************************************************************************************



    //retrieve basic info
    $scope.getBasicInfo = function()
    {
        var Registration_ID = document.querySelector('#Registration_ID').value;
        var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=basic_info&Registration_ID="+Registration_ID+"";

        $http.get(url).then(function(res){
          $scope.basics = res.data;
          console.log($scope.basics);
        },function(err){
          $scope.error =  err.status;
          console.log($scope.error);
        });

    }


    //retrieve basic info by year
    $scope.getBasicInfoByYear = function(y)
    {
        var Registration_ID = document.querySelector('#Registration_ID').value;
        var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php?action=basic_info11&Registration_ID="+Registration_ID+"&year="+y+"";

        $http.get(url).then(function(res){
          $scope.basics1 = res.data;
          console.log($scope.basics1);
        },function(err){
          $scope.error =  err.status;
          console.log($scope.error);
        });

    }


    //save thomson
    $scope.saveThomposon = function()
    {

      var url = "http://172.19.3.3/ehms/files/neonatal_record/save_hypoxic_ischaemic_encephalopath.php";

      if (confirm('Are sure want to save this?')) {
        $http.post(url,{
          "Admision_ID":document.querySelector('#Admision_ID').value,
          "consultation_id":document.querySelector('#consultation_id').value,
          "Employee_ID":document.querySelector('#Employee_ID').value,
          "Registration_ID":document.querySelector('#Registration_ID').value,
          "baby_name":$scope.baby_name,
          "birth_weight":$scope.birth_weight,
          "sex":$scope.sex,
          "apgar_score1min":$scope.apgar_score1min,
          "apgar_score5min":$scope.apgar_score5min,
          "referral":$scope.referral,
          "referral_from":$scope.referral_from,
          "history_or_dx":$scope.history_or_dx,
          "birth_date":$scope.birth_date,
          "selectTone":$scope.selectTone,
          "selectLOC":$scope.selectLOC,
          "selectFits":$scope.selectFits,
          "selectPosture":$scope.selectPosture,
          "selectMoro":$scope.selectMoro,
          "selectGrasp":$scope.selectGrasp,
          "selectSuck":$scope.selectSuck,
          "selectRespiratory":$scope.selectRespiratory,
          "selectFontanelle":$scope.selectFontanelle,
          "selectDay":$scope.selectDay,
          "remarks":$scope.remarks,
          "action":"save_thompson"
        }).then(function(res){
          $scope.success = res.data;
          $scope.clearInputs();
          $scope.initilizer();

          //success msg timeout
          $timeout(function(){
            $scope.success = "";
          },5000);

          console.log($scope.success);
        },function(res){
          $scope.error = res.status;
          console.log($scope.error);
        });
      } //End confirm

    }



    // check referral no
    $scope.checkReferralNo = function()
    {

      var refNo = document.querySelector('#refNo').value;

     if (refNo == 'no') {
        $scope.isRefferal = false;
      }
      // alert(refYes);
    }

    // check referral yes
    $scope.checkReferral = function()
    {
      var refYes = document.querySelector('#refYes').value;


      if (refYes == 'yes') {
          $scope.isRefferal = true;
      }

      // alert(refYes);
    }


    $scope.isDate = true;
    // enable date
    $scope.enableDate = function()
    {
      //var refYes = document.querySelector('#dateYes').value;

          $scope.isDate = true;


      // alert(refYes);
    }

    // enable date
    $scope.disableDate = function(x)
    {
      //var refYes = document.querySelector('#dateYes').value;
          if(x == 1)
            $scope.isDate = false;
          else if(x !=1)
            $scope.isDate = true;
          else
            $scope.isDate = true;


      // alert(refYes);
    }



  //show table
    $scope.showLastYearTable = function()
    {
      $scope.lastYears = false;
      $scope.divForm = true;
      $scope.tableDiv = true;

    }


  //show table
    $scope.showTable = function()
    {
      $scope.lastYears = true;
      $scope.divForm = true;
      $scope.tableDiv = false;

    }


    // show form
    $scope.showForm = function()
      {
        $scope.lastYears = true;
        $scope.divForm = false;
        $scope.tableDiv = true;
      }




  //clear inputs
  $scope.clearInputs = function()
  {
    $scope.baby_name = "";
    $scope.birth_weight = "";
    $scope.sex = "";
    $scope.apgar_score = "";
    $scope.referral = "";
    $scope.referral_from = "";
    $scope.history_or_dx = "";
    $scope.birth_date = "";
    $scope.selectTone = "";
    $scope.selectLOC = "";
    $scope.selectFits = "";
    $scope.selectPosture = "";
    $scope.selectMoro = "";
    $scope.selectGrasp = "";
    $scope.selectSuck = "";
    $scope.selectRespiratory = "";
    $scope.selectFontanelle = "";
    $scope.selectDay = "";
    $scope.remarks = "";
    $scope.apgar_score1min = "";
    $scope.apgar_score5min ="";
  }


});
