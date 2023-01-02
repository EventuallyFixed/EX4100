auto_ssh README
---------------
One benefit of running Home Assistant on the WD NAS is that it provides a reliable pseudo-cron.
Here this is taken advantage of to provide automatic placement of the .ssh directory after a reboot.
This will thus provide passwordless (depending on the key) login to the NAS from authorised machines.

How it works
------------
The automation runs every two minutes
It calls the script via the Home Assistant Shell_Command
The script checks for the .ssh directory in the /home/root directory of the NAS
If it's not there, it untars the file to that area

Assumptions
-----------
- ssh access to the WD NAS.
- Knowledge of Docker, and of Docker Networks.
- Experience of Home Assistant, and specifically how to add scripts, and automations.
- Knowledge of the mechanics of ssh key exchange, and a working key pair.

Get Docker for WD Nas
---------------------
It is possible to run Home Assistant on a WD NAS, although it can take a lot of the limited RAM space.
- Visit the WD Package Source GitHub repo to get the latest binary of Docker
  - https://github.com/WDCommunity/wdpksrc
  - Click on 'Releases' on the right-hand side
  - Download the latest docker-<version>.bin (~60MB)
- Install it via the manual install route, in the WD User Interface.

Prepare
-------
- Create folder: /mnt/HD/HD_a2/docker/homeassistant/config  (configurations for Home Assistant)
- Create folder: /mnt/HD/HD_a2/docker/root_ssh
  - Create file nas_root_files.tar.gz
    - Contents are: 
      - .ssh directory, permissioned as root:root, chmod 700
      - .ssh/authorized_keys

Download Home Assistant
-----------------------
- ssh into the NAS
- Issue command: docker pull homeassistant/home-assistant:latest
- After a lot of time, when downloaded and expanded, issue the following command to start Home Assistant

Optional: Create a Docker Network
---------------------------------
Not needed if using the SQLITE database.
Allows the use of a private instance of MariaDB with Home Assistant, which avoids clashes of port number with the default WD install.
To avoid that clash, this step will create a private network to allow communication with MariaDB from the Home Assistant container
# docker network create -d bridge <network_name>

Optional: Install MariaDB
-------------------------
Not needed if using the SQLITE database.
Install a MariaDB docker image.  See: https://hub.docker.com/r/linuxserver/mariadb, which worked for me (in 2022) in the WD NAS.
Create a container.  Add e.g. a '--ip 72.20.10.30' specifier in the 'docker run -d...' command
Caveats: See the Notes: section below.

Start Home Assistant
--------------------
Create a Home Assistant container
'--net network_name' is not needed if the SQLITE database is being used, rather than a MariaDB instance.

docker run -d \
  --net network_name \
  --ip 172.20.10.10 \
  --name homeassistant \
  --restart always \
  --security-opt seccomp=unconfined \
  -p 8123:8123 \
  -v /mnt/HD/HD_a2/docker/homeassistant/config:/config \
  -v /etc/localtime:/etc/localtime:ro \
  -e DISABLE_JEMALLOC=true \
  -v /home/root:/var/local/root \
  -v /var/www:/var/www \
  -v /mnt/HD/HD_a2/docker/root_ssh:/var/local/arch:ro \
  homeassistant/home-assistant

Notes: 
- Had to add '--security-opt seccomp=unconfined' as per the WD Forum: https://github.com/WDCommunity/wdpksrc/issues/57#issuecomment-731076738
- Using --net <network_name> allows the connection to mariadb via the docker private network, called <network_name>.
- Publishing to host port 8123 preserves the default Home Assistant port for external access.
- However port 80 already being used by the NAS prevents use of the Amazon Alexa add-on.
- DISABLE_JEMALLOC as per https://www.home-assistant.io/installation/alternative/#optimizations

Add Script to Home Assistant
----------------------------
- Copy file, 'automate_nas_startup.sh' into hass_config_folder/sh
- Set appropriate permissions to the folder and .sh file (execute)
- In configuration.yaml, add the following:

shell_command:
  nas_startup: bash /config/sh/nas_startup.sh

Add Automation to Home Assistant
--------------------------------
Add the following automation into hass_config_folder/automations.yaml

- id: '1624705639298'
  alias: Execute shell scripts to get stats
  description: ''
  trigger:
  - platform: template
    value_template: '{{ now().minute|int % 2 == 0 }}'
  condition: []
  action:
  - service: shell_command.nas_startup
    data: {}
  mode: single


