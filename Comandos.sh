#crea un contenedor de Docker para un servicio de respaldo
docker run -d \
  --name backup_service \
  -v $(pwd)/Proyecto:/var/www/html \
  -v $(pwd)/backups:/backups \
  ubuntu:22.04 \
  sleep infinity

#RUTAS

/home/scripts/backup_web.sh
/home/scripts/backup_mysql.sh
/home/scripts/cleanup_backups.sh

#programa las tareas de respaldo y limpieza usando cron

chmod +x /home/scripts/backup_web.sh
chmod +x /home/scripts/backup_mysql.sh
chmod +x /home/scripts/cleanup_backups.sh

#Abrir:
crontab -e

# Agregar las siguientes líneas al archivo de crontab:
Backup web semanal (domingo 03:00)
0 3 * * 0 /usr/local/bin/backup_web.sh

Backup DB diario (02:00)
0 2 * * * /usr/local/bin/backup_db.sh

Limpieza diaria de copias viejas (04:00)
0 4 * * * /usr/local/bin/cleanup_backups.sh
