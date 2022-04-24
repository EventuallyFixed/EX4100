<?php
  $rtn = array();

  $cmd = "cat /proc/meminfo | sed -n '/MemTotal:/s/ \+/ /gp' | cut -d' ' -f 2";
  // Execute the command, and get the result from the returned array
  exec($cmd, $memtotres, $int);
  $rtn["memory"]["total"] = $memtotres[0];

  $cmd = "cat /proc/meminfo | sed -n '/MemFree:/s/ \+/ /gp' | cut -d' ' -f 2";
  // Execute the command, and get the result from the returned array
  exec($cmd, $memfreeres, $int);
  $rtn["memory"]["free"] = $memfreeres[0];

  $cmd = "cat /proc/meminfo | sed -n '/Buffers:/s/ \+/ /gp' | cut -d' ' -f 2";
  // Execute the command, and get the result from the returned array
  exec($cmd, $membufres, $int);
  $rtn["memory"]["buffered"] = $membufres[0];

  $cmd = "cat /proc/meminfo | sed -n '/Cached:/s/ \+/ /gp' | head -n 1 | cut -d' ' -f 2";
  // Execute the command, and get the result from the returned array
  exec($cmd, $memcachres, $int);
  $rtn["memory"]["cached"] = $memcachres[0];

  $cmd = "free -m | sed -n '/Swap/s/ \+/ /gp' | cut -d' ' -f 2";
  // Execute the command, and get the result from the returned array
  exec($cmd, $swaptotres, $int);
  $rtn["swap"]["total"] = $swaptotres[0];

  $cmd = "free -m | sed -n '/Swap/s/ \+/ /gp' | cut -d' ' -f 4";
  // Execute the command, and get the result from the returned array
  exec($cmd, $swapfreeres, $int);
  $rtn["swap"]["free"] = $swapfreeres[0];

  $cmd = "free -m | sed -n '/Swap/s/ \+/ /gp' | cut -d' ' -f 3";
  // Execute the command, and get the result from the returned array
  exec($cmd, $swapusedres, $int);
  $rtn["swap"]["used"] = $swapusedres[0];

  $cmd = "cat /proc/meminfo | sed -n '/SwapCached/s/ \+/ /gp' | cut -d' ' -f 2";
  // Execute the command, and get the result from the returned array
  exec($cmd, $swapcachres, $int);
  $rtn["swap"]["cached"] = $swapcachres[0];

  // Return to the client
  echo json_encode($rtn, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
  // {"memory":{"total":2084352,"free":907360,"buffered":165088,"cached":554944},"swap":{"total":2097056,"free":2097056,"used":0,"cached":0}}
?>