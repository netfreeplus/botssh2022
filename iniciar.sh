clear

if [ ! -d "bot" ]; then
	mkdir bot
fi

cd bot

if [ -e "dadosBot.ini" ] ; then

	screen -X -S bot quit > /dev/null
	screen -dmS bot php bot.php
	echo "EL BOT YA ESTA CORRIENDO EN SEGUNDO PLANO MI KING NETCOL TE DA LA BIENVENIDA"

else

echo "INSTALANDO RECURSOS Y PAQUETES NECESARIOS MI KING..."

#add-apt-repository ppa:ondrej/php > /dev/null 2>&1

apt-get update > /dev/null 2>&1
apt-get upgrade -y > /dev/null 2>&1
apt-get install php -y > /dev/null 2>&1
apt-get install php-redis -y > /dev/null 2>&1
apt-get install php-curl -y > /dev/null 2>&1
apt-get install php5 -y > /dev/null 2>&1
apt-get install php5-redis -y > /dev/null 2>&1
apt-get install php5-curl -y > /dev/null 2>&1
apt-get install redis-server -y > /dev/null 2>&1
apt-get install redis -y > /dev/null 2>&1
apt-get install screen -y > /dev/null 2>&1
apt-get install zip -y > /dev/null 2>&1

wget https://www.dropbox.com/s/hnqqqby0f358ilh/gerarusuario-sshplus.sh?dl=0 -O gerarusuario.sh; chmod +x gerarusuario.sh > /dev/null

wget https://github.com/gatesccn01/botssh2022/blob/main/@admysshbot.zip?raw=true -O bot.zip && unzip bot.zip > /dev/null

rm dadosBot.ini > /dev/null

clear

ip=$(wget -qO- ipv4.icanhazip.com/)

echo "TODO LISTO AHORA INGRESA EL TOKEN DE TU BOT SIN ESPACIOS NI NADA TAL CUAL LO COPIASTE:"
read token
clear
echo "ip=$ip
token=$token
limite=100" >> dadosBot.ini

screen -dmS bot php bot.php

rm bot.zip

echo "LISTO PARA INICIAR BRO O BRA XD BUENO ESTE BOT YA ESTA EJECUTANDOSE EN SEGUNDO PLANO AHORA VE A TELEGRAM Y BUSCA  TU BOT E INICIALO /START RECUERDA AGRADECER A @NETCOLVIP"

fi
