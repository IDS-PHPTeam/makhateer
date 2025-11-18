

<?php


include_once '../models/MokhtarResponse.php';
include_once '../config/config.php';

$Config = new config();

$entityBody = file_get_contents('php://input');

$dates = json_decode($entityBody, true);
$datesCount = sizeof($dates);
if ($datesCount == 1) {
  $date = DateTime::createFromFormat("Y-m-d", $dates[0]['ToDate']);
  $Year = $date->format("Y");


  // Declare and define two dates
  $date1 = strtotime($dates[0]['FromDate']);
  $date2 = strtotime($dates[0]['ToDate']);

  $diff = abs($date2 - $date1);

  $years = floor($diff / (365 * 60 * 60 * 24));

  if ($Year > 2016) {

    if ($years == 6) {

      $Total = 675000 * 6 * 3;
      echo json_encode(
        array('Total'=>$Total,'message' => 'Successfully Done','success' =>true)
      );
    }
  } else {


    if ($years == 6) {

      $Total = 675000 * 6 * 2;
      echo json_encode(
        array('Total'=>$Total,'message' => 'Successfully Done','success' =>true)
      );
    }
  }
} else if ($datesCount == 2) {
  $Has2010 = false;
  $HasCompletedLastElectoral = true;
  $Count = 0;
  $Total = 0;
  foreach ($dates as $date) {
    $Count++;
    // echo $date['FromDate'];
    $CurrentFromDate = DateTime::createFromFormat("Y-m-d", $date['FromDate']);
    $StartYear = $CurrentFromDate->format("Y");
    if ($StartYear == 2010) {

      $Has2010 = true;
    }
    if ($Count == 2) {
      $date11 = strtotime($date['FromDate']);
      $date22 = strtotime($date['ToDate']);

      $diff = abs($date22 - $date11);

      $Year = floor($diff / (365 * 60 * 60 * 24));

      if ($Year == 6) {
        if ($Has2010) {
          $Total = (675000 * 6 * 2) + (675000 * 6 * 3);
        } else {
          $Total = (675000 * 6 * 3) + (675000 * 6 * 3);
        }
      } else {
        if ($Has2010) {
          $Total = (25 / 100) * (675000 * 6 * 2);
        } else {
          $Total = (25 / 100) * (675000 * 6 * 3);
        }
      }
      echo json_encode(
        array('Total'=>$Total,'message' => 'Successfully Done','success' =>true)
      );
    }
  }
} else if ($datesCount == 3) {

  $Has2010 = false;
  $HasCompletedLastElectoral = true;
  $Count = 0;
  $Total = 0;
  foreach ($dates as $date) {
    $Count++;
    $CurrentFromDate = DateTime::createFromFormat("Y-m-d", $date['FromDate']);
    $StartYear = $CurrentFromDate->format("Y");
    if ($StartYear == 2010) {

      $Has2010 = true;
    }
    if ($Count == 3) {
      $date11 = strtotime($date['FromDate']);
      $date22 = strtotime($date['ToDate']);

      $diff = abs($date22 - $date11);

      $Year = floor($diff / (365 * 60 * 60 * 24));

      if ($Year == 6) {
        if ($Has2010) {
          $Total = (675000 * 6 * 2) + (675000 * 6 * 3) + (675000 * 6 * 3);
        } else {
          $Total = (675000 * 6 * 3) + (675000 * 6 * 3) + (675000 * 6 * 3);
        }
      } else {
        if ($Has2010) {
          $Total = (50 / 100) * ((675000 * 6 * 2) + (675000 * 6 * 3));
        } else {
          $Total = (50 / 100) * ((675000 * 6 * 3) + (675000 * 6 * 3));
        }
      }
      echo json_encode(
        array('Total'=>$Total,'message' => 'Successfully Done','success' =>true)
      );
    }
  }
} else if ($datesCount == 4) {
  $Count++;
  $CurrentFromDate = DateTime::createFromFormat("Y-m-d", $date['FromDate']);
  $StartYear = $CurrentFromDate->format("Y");
  if ($StartYear == 2010) {

    $Has2010 = true;
  }
  if ($Count == 4) {
    $date11 = strtotime($date['FromDate']);
    $date22 = strtotime($date['ToDate']);

    $diff = abs($date22 - $date11);

    $Year = floor($diff / (365 * 60 * 60 * 24));

    if ($Year == 6) {
      if ($Has2010) {
        $Total = (675000 * 6 * 2) + (675000 * 6 * 3) + (675000 * 6 * 3) + (675000 * 6 * 3);
      } else {
        $Total = (675000 * 6 * 3) + (675000 * 6 * 3) + (675000 * 6 * 3) + (675000 * 6 * 3);
      }
    } else {
      if ($Has2010) {
        $Total = (75 / 100) * ((675000 * 6 * 2) + (675000 * 6 * 3) + (675000 * 6 * 3));
      } else {
        $Total = (75 / 100) * ((675000 * 6 * 3) + (675000 * 6 * 3) + (675000 * 6 * 3));
      }
    }
    echo json_encode(
      array('Total'=>$Total,'message' => 'Successfully Done','success' =>true)
    );
  }
} else {
  $Count++;
  $CurrentFromDate = DateTime::createFromFormat("Y-m-d", $date['FromDate']);
  $StartYear = $CurrentFromDate->format("Y");
  if ($StartYear == 2010) {

    $Has2010 = true;
  }
  if ($Count ==  sizeof($dates)) {
    $date11 = strtotime($date['FromDate']);
    $date22 = strtotime($date['ToDate']);

    $diff = abs($date22 - $date11);

    $Year = floor($diff / (365 * 60 * 60 * 24));

    if ($Year == 6) {
      if ($Has2010) {
        $Total = (675000 * 6 * 2) + (675000 * 6 * 3) * sizeof($dates) - 1;
      } else {
        $Total = (675000 * 6 * 3) + (675000 * 6 * 3) * sizeof($dates) - 1;
      }
    } else {
      if ($Has2010) {
        $Total = (75 / 100) * ((675000 * 6 * 2) * sizeof($dates) - 1);
      } else {
        $Total = (75 / 100) * ((675000 * 6 * 3) * sizeof($dates) - 1);
      }
    }
    echo json_encode(
      array('Total'=>$Total,'message' => 'Successfully Done','success' =>true)
    );
  }
}


?>