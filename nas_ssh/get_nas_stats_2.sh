#!/bin/sh
# get_nas_stats_2.sh
# Get stats from the NAS as a JSON output

nas_disk_space_total=`df | sed -n '/HD_a2/s/ \+/ /gp' | cut -d' ' -f 2`
nas_disk_space_used=`df | sed -n '/HD_a2/s/ \+/ /gp' | cut -d' ' -f 3`
nas_disk_space_free=`df | sed -n '/HD_a2/s/ \+/ /gp' | cut -d' ' -f 4`
nas_disk_perc_used=`df | sed -n '/HD_a2/s/ \+/ /gp' | cut -d' ' -f 5 | sed 's/%//g'`
nas_cpu_idle=`mpstat | sed -n '/all/s/ \+/ /gp' | cut -d' ' -f 12 | sed 's/%//g'`
nas_mem_tot=`cat /proc/meminfo | sed -n '/MemTotal:/s/ \+/ /gp' | cut -d' ' -f 2`
nas_mem_free=`cat /proc/meminfo | sed -n '/MemFree:/s/ \+/ /gp' | cut -d' ' -f 2`
nas_mem_buffered=`cat /proc/meminfo | sed -n '/Buffers:/s/ \+/ /gp' | cut -d' ' -f 2`
nas_mem_cached=`cat /proc/meminfo | sed -n '/Cached:/s/ \+/ /gp' | head -n 1 | cut -d' ' -f 2`
nas_swap_tot=`free -m | sed -n '/Swap/s/ \+/ /gp' | cut -d' ' -f 2`
nas_swap_free=`free -m | sed -n '/Swap/s/ \+/ /gp' | cut -d' ' -f 4`
nas_swap_used=`free -m | sed -n '/Swap/s/ \+/ /gp' | cut -d' ' -f 3`
nas_swap_cached=`cat /proc/meminfo | sed -n '/SwapCached/s/ \+/ /gp' | cut -d' ' -f 2`

echo "{ \"ndt\":\"$nas_disk_space_total\" , \"ndu\":\"$nas_disk_space_used\" , \"ndf\":\"$nas_disk_space_free\" , \"ndup\":\"$nas_disk_perc_used\" , \"nci\":\"$nas_cpu_idle\" , \"nmt\":\"$nas_mem_tot\" , \"nmf\":\"$nas_mem_free\" , \"nmb\":\"$nas_mem_buffered\" , \"nmc\":\"$nas_mem_cached\" , \"nst\":\"$nas_swap_tot\" , \"nsf\":\"$nas_swap_free\" , \"nsu\":\"$nas_swap_used\" , \"nsc\":\"$nas_swap_cached\" }"

