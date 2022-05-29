#!/bin/bash
#$LogDate=
#LogFile="/config/sh/automation-log_$(date '+%Y%m%d').txt"
LogFile="/dev/null"

echo "$(date '+%Y-%m-%d %H:%M:%S') Beginning NAS Automation" >> $LogFile

# Automate the availability of ssh via key
if [ ! -d "/var/local/root/.ssh" ] ; then
    cd /var/local/root
    tar -xvzf /var/local/arch/nas_root_files.tar.gz
    echo "$(date '+%Y-%m-%d %H:%M:%S') .ssh folder created" >> $LogFile
fi

# Automate the availability of the stats web folder
# In the container, check that our directories exist
# Create the symlink, then destroy the container directory
# Assumes that '/mnt/HD/HD_a2/Nas_Prog/stats' exists (discussed elsewhere)
if [ ! -L "/var/www/stats" ] ; then
    mkdir -p /mnt/HD/HD_a2/Nas_Prog/stats
    ln -s /mnt/HD/HD_a2/Nas_Prog/stats /var/www/stats
    rm -rf /mnt/HD
    echo "$(date '+%Y-%m-%d %H:%M:%S') Link to Stats created" >> $LogFile
fi

echo "$(date '+%Y-%m-%d %H:%M:%S') Completed NAS Automation" >> $LogFile


