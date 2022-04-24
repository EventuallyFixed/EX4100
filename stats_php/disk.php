<?php
  $rtn = array();

  $cmd = "df | sed -n '/HD_a2/s/ \+/ /gp' | cut -d' ' -f 2";
  // Execute the command, and get the result from the returned array
  exec($cmd, $disktotres, $int);
  $rtn["disk"]["kB"]["total"] = $disktotres[0];

  $cmd = "df | sed -n '/HD_a2/s/ \+/ /gp' | cut -d' ' -f 3";
  // Execute the command, and get the result from the returned array
  exec($cmd, $diskusedres, $int);
  $rtn["disk"]["kB"]["used"] = $diskusedres[0];

  $cmd = "df | sed -n '/HD_a2/s/ \+/ /gp' | cut -d' ' -f 4";
  // Execute the command, and get the result from the returned array
  exec($cmd, $diskfreeres, $int);
  $rtn["disk"]["kB"]["free"] = $diskfreeres[0];

  $cmd = "df | sed -n '/HD_a2/s/ \+/ /gp' | cut -d' ' -f 5";
  // Execute the command, and get the result from the returned array
  exec($cmd, $diskpusedres, $int);
  $pctused = str_replace('%','',$diskpusedres[0]);
  $rtn["disk"]["percent"]["used"] = str_replace('%','',$pctused);
  $rtn["disk"]["percent"]["free"] = 100 - $pctused;

  // Return to the client
  echo json_encode($rtn, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
  // {"disk":{"kB":{"total":3837326424,"used":744981880,"free":3053341972},"percent":{"used":20,"free":80}}}
?>
