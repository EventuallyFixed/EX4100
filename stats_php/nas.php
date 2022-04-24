<?php
  $rtn = array();

  $cmd = "/usr/sbin/fan_control -g 0";
  // Execute the command, and get the result from the returned array
  exec($cmd, $fanres, $int);
  //$rtn["res"] = $fanres;
  $rtn["temp"]["board"] = explode(" ", $fanres[0])[3];
  $rtn["temp"]["hd0"] = explode("=", $fanres[1])[1];
  $rtn["temp"]["hd1"] = explode("=", $fanres[2])[1];
  $rtn["temp"]["hd2"] = explode("=", $fanres[3])[1];
  $rtn["temp"]["hd3"] = explode("=", $fanres[4])[1];
  $rtn["temp"]["cpu"] = explode("=", $fanres[5])[1];
  
  $cmd = "/usr/local/sbin/getSmartStatus.sh";
  // Execute the command, and get the result from the returned array
  exec($cmd, $smtres, $int);
  $rtn["smart"] = $smtres[0];

  $cmd = "/usr/local/sbin/getNewFirmwareAvailable.sh";
  // Execute the command, and get the result from the returned array
  exec($cmd, $nfres, $int);
  $rtn["firmware"] = str_replace('"', '', $nfres[0]);
  
  $cmd = "/usr/sbin/mpstat | sed -n '/all/s/ \+/ /gp' | cut -d' ' -f 12 | sed 's/%//g'";
  // Execute the command, and get the result from the returned array
  exec($cmd, $mpres, $int);
  $rtn["cpu"]["idle"] = $mpres[0];
  $rtn["cpu"]["used"] = 100 - $mpres[0];

  $cmd = "fan_control -g 4 | cut -d' ' -f 4";
  // Execute the command, and get the result from the returned array
  exec($cmd, $fanrpmres, $int);
  $rtn["fan"]["rpm"] = $fanrpmres[0];

  // Return to the client
  echo json_encode($rtn, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);

// JSON result is like, e.g.
// {"temp":{"board":28,"hd0":30,"hd1":30,"hd2":32,"hd3":31,"cpu":54},"smart":"good","firmware":"no upgrade","cpu":{"idle":96.5,"used":3.5},"fan":{"rpm":600}}
?>