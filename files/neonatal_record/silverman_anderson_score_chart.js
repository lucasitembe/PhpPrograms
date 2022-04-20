

var app = angular.module('silvermanModule',[]);

app.controller('silvermanCtl', function($scope,$http,$timeout){

  $scope.divForm = false;
  $scope.tableDiv = true;
  $scope.lastYears = true;
  $scope.isRefferal = false;
  $scope.success = "";
  $scope.error = "";
  $scope.basics = {};
  $scope.basics1 = {};

  $scope.Upper_chest = {"Synchronised":0,"Lags on inspiration":1,"See-Saw respiration":2};
  $scope.Lower_chest = {"None":0,"Just visible":1,"Marked":2};
  $scope.Xiphoid_retraction = {"None":0,"Just visible":1,"Marked":2};
  $scope.Nasal_flaring = {"None":0,"Minimal":1,"Marked":2};
  $scope.Expiratory_grant = {"None":0,"Audible with stethoscope":1,"Audible with naked ear":2};
  $scope.days = [1,2,3,4,5,6];


// UPPER CHEST INDRAWING
  $scope.Upper_chest1 = {};
  $scope.Upper_chest2 = {};
  $scope.Upper_chest3 = {};
  $scope.Upper_chest4 = {};
  $scope.Upper_chest5 = {};
  $scope.Upper_chest6 = {};
  $scope.Upper_chest7 = {};

  $scope.Upper_chest11 = {};
  $scope.Upper_chest21 = {};
  $scope.Upper_chest31 = {};
  $scope.Upper_chest41 = {};
  $scope.Upper_chest51 = {};
  $scope.Upper_chest61 = {};
  $scope.Upper_chest71 = {};



  // LOWER CHEST INDRAWING
    $scope.Lower_chest1 = {};
    $scope.Lower_chest2 = {};
    $scope.Lower_chest3 = {};
    $scope.Lower_chest4 = {};
    $scope.Lower_chest5 = {};
    $scope.Lower_chest6 = {};
    $scope.Lower_chest7 = {};

    $scope.Lower_chest11 = {};
    $scope.Lower_chest21 = {};
    $scope.Lower_chest31 = {};
    $scope.Lower_chest41 = {};
    $scope.Lower_chest51 = {};
    $scope.Lower_chest61 = {};
    $scope.Lower_chest71 = {};

    //get all functions Which called by year
    $scope.getAll = function()
    {
      var y = document.querySelector('#year').value;

        $scope.getBasicInfoByYear(y);

        $scope.sumDay1ByYear(y);
        $scope.sumDay2ByYear(y);
        $scope.sumDay3ByYear(y);
        $scope.sumDay4ByYear(y);
        $scope.sumDay5ByYear(y);
        $scope.sumDay6ByYear(y);

        $scope.getUpper_chest1ByYear(y);
        $scope.getUpper_chest2ByYear(y);
        $scope.getUpper_chest3ByYear(y);
        $scope.getUpper_chest4ByYear(y);
        $scope.getUpper_chest5ByYear(y);
        $scope.getUpper_chest6ByYear(y);

        $scope.getLower_chest1ByYear(y);
        $scope.getLower_chest2ByYear(y);
        $scope.getLower_chest3ByYear(y);
        $scope.getLower_chest4ByYear(y);
        $scope.getLower_chest5ByYear(y);
        $scope.getLower_chest6ByYear(y);



   $scope.getXiphoid_retraction1ByYear(y);
   $scope.getXiphoid_retraction2ByYear(y);
   $scope.getXiphoid_retraction3ByYear(y);
   $scope.getXiphoid_retraction4ByYear(y);
   $scope.getXiphoid_retraction5ByYear(y);
   $scope.getXiphoid_retraction6ByYear(y);


   $scope.getNasal_flaring1ByYear(y);
   $scope.getNasal_flaring2ByYear(y);
   $scope.getNasal_flaring3ByYear(y);
   $scope.getNasal_flaring4ByYear(y);
   $scope.getNasal_flaring5ByYear(y);
   $scope.getNasal_flaring6ByYear(y);


   $scope.getExpiratory_grants1ByYear(y);
   $scope.getExpiratory_grants2ByYear(y);
   $scope.getExpiratory_grants3ByYear(y);
   $scope.getExpiratory_grants4ByYear(y);
   $scope.getExpiratory_grants5ByYear(y);
   $scope.getExpiratory_grants6ByYear(y);

        //$scope.getOthers(y);


  }





    // initializer
    $scope.initilizer = function()
    {
      $scope.getBasicInfo();

      $scope.sumDay1();
      $scope.sumDay2();
      $scope.sumDay3();
      $scope.sumDay4();
      $scope.sumDay5();
      $scope.sumDay6();

      $scope.getUpper_chest1();
      $scope.getUpper_chest2();
      $scope.getUpper_chest3();
      $scope.getUpper_chest4();
      $scope.getUpper_chest5();
      $scope.getUpper_chest6();


      $scope.getLower_chest1();
      $scope.getLower_chest2();
      $scope.getLower_chest3();
      $scope.getLower_chest4();
      $scope.getLower_chest5();
      $scope.getLower_chest6();

      $scope.getXiphoid_retraction1();
      $scope.getXiphoid_retraction2();
      $scope.getXiphoid_retraction3();
      $scope.getXiphoid_retraction4();
      $scope.getXiphoid_retraction5();
      $scope.getXiphoid_retraction6();

      $scope.getNasal_flaring1();
      $scope.getNasal_flaring2();
      $scope.getNasal_flaring3();
      $scope.getNasal_flaring4();
      $scope.getNasal_flaring5();
      $scope.getNasal_flaring6();

      $scope.getExpiratory_grants1();
      $scope.getExpiratory_grants2();
      $scope.getExpiratory_grants3();
      $scope.getExpiratory_grants4();
      $scope.getExpiratory_grants5();
      $scope.getExpiratory_grants6();

    }




    // XIPHOID RETRACTION
      $scope.Xiphoid_retraction1 = {};
      $scope.Xiphoid_retraction2 = {};
      $scope.Xiphoid_retraction3 = {};
      $scope.Xiphoid_retraction4 = {};
      $scope.Xiphoid_retraction5 = {};
      $scope.Xiphoid_retraction6 = {};
      $scope.Xiphoid_retraction7 = {};


      $scope.Xiphoid_retraction11 = {};
      $scope.Xiphoid_retraction21 = {};
      $scope.Xiphoid_retraction31 = {};
      $scope.Xiphoid_retraction41 = {};
      $scope.Xiphoid_retraction51 = {};
      $scope.Xiphoid_retraction61 = {};
      $scope.Xiphoid_retraction71 = {};



  // Upper_chest1 chest
  $scope.getUpper_chest1 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_upper1&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Upper_chest1 = res.data;
      console.log($scope.Upper_chest1);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Upper_chest1 chest by year
  $scope.getUpper_chest1ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_upper11&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Upper_chest11 = res.data;
      console.log($scope.Upper_chest11);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }



  // Upper_chest2 chest
  $scope.getUpper_chest2= function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_upper2&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Upper_chest2 = res.data;
      console.log($scope.Upper_chest2);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Upper_chest2 chest by year
  $scope.getUpper_chest2ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_upper21&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Upper_chest21 = res.data;
      console.log($scope.Upper_chest21);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }



  // Upper_chest3 chest
  $scope.getUpper_chest3 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_upper3&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Upper_chest3 = res.data;
      console.log($scope.Upper_chest3);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Upper_chest3 chest by year
  $scope.getUpper_chest3ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_upper31&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Upper_chest31 = res.data;
      console.log($scope.Upper_chest31);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }




  // Upper_chest4 chest
  $scope.getUpper_chest4 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_upper4&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Upper_chest4 = res.data;
      console.log($scope.Upper_chest4);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Upper_chest4 chest by year
  $scope.getUpper_chest4ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_upper41&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Upper_chest41 = res.data;
      console.log($scope.Upper_chest41);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }



  // Upper_chest5 chest
  $scope.getUpper_chest5 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_upper5&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Upper_chest5 = res.data;
      console.log($scope.Upper_chest5);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Upper_chest5 chest by year
  $scope.getUpper_chest5ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_upper51&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Upper_chest51 = res.data;
      console.log($scope.Upper_chest51);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }



  // Upper_chest6 chest
  $scope.getUpper_chest6 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_upper6&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Upper_chest6 = res.data;
      console.log($scope.Upper_chest6);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Upper_chest6 chest by year
  $scope.getUpper_chest6ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_upper61&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Upper_chest61 = res.data;
      console.log($scope.Upper_chest61);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }








  // Lower_chest1
  $scope.getLower_chest1 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_lower1&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Lower_chest1 = res.data;
      console.log($scope.Lower_chest1);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Lower_chest1 by year
  $scope.getLower_chest1ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_lower11&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Lower_chest11 = res.data;
      console.log($scope.Lower_chest11);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }



  // Lower_chest2
  $scope.getLower_chest2 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_lower2&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Lower_chest2 = res.data;
      console.log($scope.Lower_chest2);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Lower_chest2 by year
  $scope.getLower_chest2ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_lower21&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Lower_chest21 = res.data;
      console.log($scope.Lower_chest21);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }



  // Lower_chest3
  $scope.getLower_chest3 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_lower3&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Lower_chest3 = res.data;
      console.log($scope.Lower_chest3);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Lower_chest3 by year
  $scope.getLower_chest3ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_lower31&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Lower_chest31 = res.data;
      console.log($scope.Lower_chest31);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Lower_chest4
  $scope.getLower_chest4 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_lower4&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Lower_chest4 = res.data;
      console.log($scope.Lower_chest4);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Lower_chest4 by year
  $scope.getLower_chest4ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_lower41&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Lower_chest41 = res.data;
      console.log($scope.Lower_chest41);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }




  // Lower_chest5
  $scope.getLower_chest5 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_lower5&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Lower_chest5 = res.data;
      console.log($scope.Lower_chest5);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Lower_chest5 by year
  $scope.getLower_chest5ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_lower51&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Lower_chest51 = res.data;
      console.log($scope.Lower_chest51);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }



  // Lower_chest6
  $scope.getLower_chest6 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_lower6&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Lower_chest6 = res.data;
      console.log($scope.Lower_chest6);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Lower_chest6 by year
  $scope.getLower_chest6ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_lower61&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Lower_chest61 = res.data;
      console.log($scope.Lower_chest61);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }






  // Xiphoid_retraction1
  $scope.getXiphoid_retraction1 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_xiphoid1&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Xiphoid_retraction1 = res.data;
      console.log($scope.Xiphoid_retraction1);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Xiphoid_retraction1  by year
  $scope.getXiphoid_retraction1ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_xiphoid11&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Xiphoid_retraction11 = res.data;
      console.log($scope.Xiphoid_retraction11);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }



  // Xiphoid_retraction2
  $scope.getXiphoid_retraction2 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_xiphoid2&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Xiphoid_retraction2 = res.data;
      console.log($scope.Xiphoid_retraction2);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Xiphoid_retraction2  by year
  $scope.getXiphoid_retraction2ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_xiphoid21&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Xiphoid_retraction21 = res.data;
      console.log($scope.Xiphoid_retraction21);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }



  // Xiphoid_retraction3
  $scope.getXiphoid_retraction3 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_xiphoid3&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Xiphoid_retraction3 = res.data;
      console.log($scope.Xiphoid_retraction3);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Xiphoid_retraction3  by year
  $scope.getXiphoid_retraction3ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_xiphoid31&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Xiphoid_retraction31 = res.data;
      console.log($scope.Xiphoid_retraction31);
    },function(err){
      $scope.error =  err.statusText;
      console.log($scope.error);
    });

  }




  // Xiphoid_retraction4
  $scope.getXiphoid_retraction4 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_xiphoid4&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Xiphoid_retraction4 = res.data;
      console.log($scope.Xiphoid_retraction4);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Xiphoid_retraction4  by year
  $scope.getXiphoid_retraction4ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_xiphoid41&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Xiphoid_retraction41 = res.data;
      console.log($scope.Xiphoid_retraction41);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }




  // Xiphoid_retraction5
  $scope.getXiphoid_retraction5 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_xiphoid5&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Xiphoid_retraction5 = res.data;
      console.log($scope.Xiphoid_retraction5);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Xiphoid_retraction5  by year
  $scope.getXiphoid_retraction5ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_xiphoid51&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Xiphoid_retraction51 = res.data;
      console.log($scope.Xiphoid_retraction51);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }



  // Xiphoid_retraction6
  $scope.getXiphoid_retraction6 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_xiphoid6&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Xiphoid_retraction6 = res.data;
      console.log($scope.Xiphoid_retraction6);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Xiphoid_retraction6  by year
  $scope.getXiphoid_retraction6ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_xiphoid61&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Xiphoid_retraction61 = res.data;
      console.log($scope.Xiphoid_retraction61);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }







// NASAL FLARING
  $scope.Nasal_flaring1 = {};
  $scope.Nasal_flaring2 = {};
  $scope.Nasal_flaring3 = {};
  $scope.Nasal_flaring4 = {};
  $scope.Nasal_flaring5 = {};
  $scope.Nasal_flaring6 = {};
  $scope.Nasal_flaring7 = {};


  $scope.Nasal_flaring11 = {};
  $scope.Nasal_flaring21 = {};
  $scope.Nasal_flaring31 = {};
  $scope.Nasal_flaring41 = {};
  $scope.Nasal_flaring51 = {};
  $scope.Nasal_flaring61 = {};
  $scope.Nasal_flaring71 = {};

  // Nasal_flaring1 chest
  $scope.getNasal_flaring1 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_flaring1&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Nasal_flaring1 = res.data;
      console.log($scope.Nasal_flaring1);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Nasal_flaring1  by year
  $scope.getNasal_flaring1ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_flaring11&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Nasal_flaring11 = res.data;
      console.log($scope.Nasal_flaring11);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }



  // Nasal_flaring2
  $scope.getNasal_flaring2 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_flaring2&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Nasal_flaring2 = res.data;
      console.log($scope.Nasal_flaring2);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Nasal_flaring2  by year
  $scope.getNasal_flaring2ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_flaring21&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Nasal_flaring21 = res.data;
      console.log($scope.Nasal_flaring21);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }




  // Nasal_flaring3
  $scope.getNasal_flaring3 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_flaring3&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Nasal_flaring3 = res.data;
      console.log($scope.Nasal_flaring3);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Nasal_flaring3  by year
  $scope.getNasal_flaring3ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_flaring31&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Nasal_flaring31 = res.data;
      console.log($scope.Nasal_flaring31);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }



  // Nasal_flaring4
  $scope.getNasal_flaring4 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_flaring4&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Nasal_flaring4 = res.data;
      console.log($scope.Nasal_flaring4);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Nasal_flaring4  by year
  $scope.getNasal_flaring4ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_flaring41&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Nasal_flaring41 = res.data;
      console.log($scope.Nasal_flaring41);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }



  // Nasal_flaring5
  $scope.getNasal_flaring5 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_flaring5&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Nasal_flaring5 = res.data;
      console.log($scope.Nasal_flaring5);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Nasal_flaring5  by year
  $scope.getNasal_flaring5ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_flaring51&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Nasal_flaring51 = res.data;
      console.log($scope.Nasal_flaring51);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }



  // Nasal_flaring6
  $scope.getNasal_flaring6 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_flaring6&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Nasal_flaring6 = res.data;
      console.log($scope.Nasal_flaring6);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Nasal_flaring6  by year
  $scope.getNasal_flaring6ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_flaring61&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Nasal_flaring61 = res.data;
      console.log($scope.Nasal_flaring61);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }






// EXPIRATORY GRANT
  $scope.Expiratory_grants1 = {};
  $scope.Expiratory_grants2 = {};
  $scope.Expiratory_grants3 = {};
  $scope.Expiratory_grants4 = {};
  $scope.Expiratory_grants5 = {};
  $scope.Expiratory_grants6 = {};
  $scope.Expiratory_grants7 = {};


  $scope.Expiratory_grants11 = {};
  $scope.Expiratory_grants21 = {};
  $scope.Expiratory_grants31 = {};
  $scope.Expiratory_grants41 = {};
  $scope.Expiratory_grants51 = {};
  $scope.Expiratory_grants61 = {};
  $scope.Expiratory_grants71 = {};

  // Expiratory_grants1
  $scope.getExpiratory_grants1 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_expiratory1&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Expiratory_grants1 = res.data;
      console.log($scope.Expiratory_grants1);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Expiratory_grants1  by year
  $scope.getExpiratory_grants1ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_expiratory11&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Expiratory_grants11 = res.data;
      console.log($scope.Expiratory_grants11);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }



  // Expiratory_grants2
  $scope.getExpiratory_grants2 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_expiratory2&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Expiratory_grants2 = res.data;
      console.log($scope.Expiratory_grants2);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Expiratory_grants2  by year
  $scope.getExpiratory_grants2ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_expiratory21&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Expiratory_grants21 = res.data;
      console.log($scope.Expiratory_grants21);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }



  // Expiratory_grants3
  $scope.getExpiratory_grants3 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_expiratory3&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Expiratory_grants3 = res.data;
      console.log($scope.Expiratory_grants3);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Expiratory_grants3  by year
  $scope.getExpiratory_grants3ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_expiratory31&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Expiratory_grants31 = res.data;
      console.log($scope.Expiratory_grants31);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }




  // Expiratory_grants4
  $scope.getExpiratory_grants4 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_expiratory4&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Expiratory_grants4 = res.data;
      console.log($scope.Expiratory_grants4);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Expiratory_grants4  by year
  $scope.getExpiratory_grants4ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_expiratory41&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Expiratory_grants41 = res.data;
      console.log($scope.Expiratory_grants41);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }



  // Expiratory_grants5
  $scope.getExpiratory_grants5 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_expiratory5&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Expiratory_grants5 = res.data;
      console.log($scope.Expiratory_grants5);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Expiratory_grants5  by year
  $scope.getExpiratory_grants5ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_expiratory51&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Expiratory_grants51 = res.data;
      console.log($scope.Expiratory_grants51);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }



  // Expiratory_grants6
  $scope.getExpiratory_grants6 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_expiratory6&Registration_ID="+Registration_ID+"";
    $http.get(url).then(function(res){
      $scope.Expiratory_grants6 = res.data;
      console.log($scope.Expiratory_grants6);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }


  // Expiratory_grants6  by year
  $scope.getExpiratory_grants6ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=get_expiratory61&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.Expiratory_grants61 = res.data;
      console.log($scope.Expiratory_grants61);
    },function(err){
      $scope.error =  err.status;
      console.log($scope.error);
    });

  }







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


  //retrieve basic info
  $scope.getBasicInfo = function()
  {
      var Registration_ID = document.querySelector('#Registration_ID').value;
      var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=basic_info&Registration_ID="+Registration_ID+"";

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
      var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=basic_info11&Registration_ID="+Registration_ID+"&year="+y+"";

      $http.get(url).then(function(res){
        $scope.basics1 = res.data;
        console.log($scope.basics1);
      },function(err){
        $scope.error =  err.status;
        console.log($scope.error);
      });

  }




  // save silverman data
  $scope.saveSilverman =  function()
  {
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php";

    if (confirm('Are you sure want to save this?')) {

        $http.post(url,{
          "Admision_ID":document.querySelector('#Admision_ID').value,
          "consultation_id":document.querySelector('#consultation_id').value,
          "Registration_ID":document.querySelector('#Registration_ID').value,
          "Employee_ID":document.querySelector('#Employee_ID').value,
          "Upper_chest":$scope.selectedUpper_chest,
          "Lower_chest":$scope.selectedLower_chest,
          "Xiphoid_retraction":$scope.selectedXiphoid_retraction,
          "Nasal_flaring":$scope.selectedNasal_flaring,
          "Expiratory_grant":$scope.selectedExpiratory_grant,
          "patient_name":$scope.patient_name,
          "admission_date":$scope.admission_date,
          "Gestation_age_at_birth":$scope.Gestation_age_at_birth,
          "sex":$scope.sex,
          "day":$scope.selectedDay,
          "action":"save_silverman"
        }).then(function(res){

          $scope.success = res.data;
          $scope.initilizer();
          //clear the inputs
          $scope.clearInputs();

          //success msg 5sec timeout
          $timeout(function(){
            $scope.success = "";
          },5000);

        },function(res){
          $scope.error = res.data;
        });

    } //end confirm

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
    $scope.selectedUpper_chest = "";
    $scope.selectedLower_chest = "";
    $scope.selectedXiphoid_retraction = "";
    $scope.selectedNasal_flaring = "";
    $scope.selectedExpiratory_grant = "";
    $scope.patient_name = "";
    $scope.admission_date = "";
    $scope.Gestation_age_at_birth = "";
    $scope.sex = "";
    $scope.selectedDay ="";

  }



  //sum day1
  $scope.sumDay1 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=sum_d1&Registration_ID="+Registration_ID+"";

    $http.get(url).then(function(res){
      $scope.d1Total = res.data;
      console.log($scope.d1Total);
    });
  }


  //sum day1 by year
  $scope.sumDay1ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=sum_d11&Registration_ID="+Registration_ID+"&year="+y+"";

    $http.get(url).then(function(res){
      $scope.d1Total1 = res.data;
      console.log($scope.d1Total1);
    });
  }



  //sum Day2
  $scope.sumDay2 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=sum_d2&Registration_ID="+Registration_ID+"";

    $http.get(url).then(function(res){
      $scope.d2Total = res.data;
      console.log($scope.d2Total);
    });
  }


  //sum Day2 by year
  $scope.sumDay2ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=sum_d21&Registration_ID="+Registration_ID+"&year="+y+"";

    $http.get(url).then(function(res){
      $scope.d2Total1 = res.data;
      console.log($scope.d2Total1);
    });
  }


  //sum Day3
  $scope.sumDay3 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=sum_d3&Registration_ID="+Registration_ID+"";

    $http.get(url).then(function(res){
      $scope.d3Total = res.data;
      console.log($scope.d3Total);
    });
  }


  //sum Day3 by year
  $scope.sumDay3ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=sum_d31&Registration_ID="+Registration_ID+"&year="+y+"";

    $http.get(url).then(function(res){
      $scope.d3Total1 = res.data;
      console.log($scope.d3Total1);
    });
  }



  //sum Day4
  $scope.sumDay4 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=sum_d4&Registration_ID="+Registration_ID+"";

    $http.get(url).then(function(res){
      $scope.d4Total = res.data;
      console.log($scope.d4Total);
    });
  }


  //sum Day4 by year
  $scope.sumDay4ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=sum_d41&Registration_ID="+Registration_ID+"&year="+y+"";

    $http.get(url).then(function(res){
      $scope.d4Total1 = res.data;
      console.log($scope.d4Total1);
    });
  }



  //sum Day5
  $scope.sumDay5 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=sum_d5&Registration_ID="+Registration_ID+"";

    $http.get(url).then(function(res){
      $scope.d5Total = res.data;
      console.log($scope.d5Total);
    });
  }


  //sum Day5 by year
  $scope.sumDay5ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=sum_d51&Registration_ID="+Registration_ID+"&year="+y+"";

    $http.get(url).then(function(res){
      $scope.d5Total1 = res.data;
      console.log($scope.d5Total1);
    });
  }



  //sum Day6
  $scope.sumDay6 = function()
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=sum_d6&Registration_ID="+Registration_ID+"";

    $http.get(url).then(function(res){
      $scope.d6Total = res.data;
      console.log($scope.d6Total);
    });
  }


  //sum Day6 by year
  $scope.sumDay6ByYear = function(y)
  {
    var Registration_ID = document.querySelector('#Registration_ID').value;
    var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_silverman_anderson_score_chart.php?action=sum_d61&Registration_ID="+Registration_ID+"&year="+y+"";

    $http.get(url).then(function(res){
      $scope.d6Total1 = res.data;
      console.log($scope.d6Total1);
    });
  }


});
