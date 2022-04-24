README.txt
----------
mkdir -p /mnt/HD/HD_a2/Nas_Prog/stats
ln -s /mnt/HD/HD_a2/Nas_Prog/stats /var/www/stats

Copy files into /mnt/HD/HD_a2/Nas_Prog/stats
Rename 'htaccess' to '.htaccess'

Then navigate to
- http://your.nas/stats/disk.php   - kB total, used, free; perc used, free
- http://your.nas/stats/memory.php - memory total, free, buffered, cached; swap total, free, used, cached
- http://your.nas/stats/nas.php    - temperature board, disks, cpu; smart status, firmware, cpu used, free; fan speed
