#!/bin/sh
# get_nas_stats.sh
# Get stats from the NAS as a JSON output
fan_control -g 0 > /tmp/fan_control.txt
nas_cpu_temp=`cat /tmp/fan_control.txt | grep CPU | cut -d' ' -f 2 | cut -d'=' -f 2`
nas_board_temp=`cat /tmp/fan_control.txt | grep Current | cut -d' ' -f 4`
nas_hd0_temp=`cat /tmp/fan_control.txt | grep hd0 | cut -d' ' -f 2 | cut -d'=' -f 2`
nas_hd1_temp=`cat /tmp/fan_control.txt | grep hd1 | cut -d' ' -f 2 | cut -d'=' -f 2`
nas_hd2_temp=`cat /tmp/fan_control.txt | grep hd2 | cut -d' ' -f 2 | cut -d'=' -f 2`
nas_hd3_temp=`cat /tmp/fan_control.txt | grep hd3 | cut -d' ' -f 2 | cut -d'=' -f 2`
nas_fan_rpm=`fan_control -g 4 | cut -d' ' -f 4`
nas_smart_status=`/usr/local/sbin/getSmartStatus.sh`
nas_firmware_status=`/usr/local/sbin/getNewFirmwareAvailable.sh | sed 's/"//g' | sed 's/no upgrade/current/g'`

echo "{ \"nct\":\"$nas_cpu_temp\" , \"nbt\":\"$nas_board_temp\" , \"n0t\":\"$nas_hd0_temp\" , \"n1t\":\"$nas_hd1_temp\" , \"n2t\":\"$nas_hd2_temp\" , \"n3t\":\"$nas_hd3_temp\" , \"nfr\":\"$nas_fan_rpm\" , \"nss\":\"$nas_smart_status\" , \"nfs\":\"$nas_firmware_status\" }"

