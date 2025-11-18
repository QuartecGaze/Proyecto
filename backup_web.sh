#!/bin/bash

# Contenedor de Docker donde se encuentra el servicio web
CONTAINER=backup_service
# Rutas dentro del contenedor
WEB_DIR="/var/www/html"
# Directorio de respaldo dentro del contenedor
BACKUP_DIR="/backups"
# Fecha actual
DATE=$(date +"%Y-%m-%d")
# Crear directorio de respaldo
docker exec $CONTAINER mkdir -p $BACKUP_DIR/$DATE
# Realizar el respaldo usando rsync
docker exec $CONTAINER rsync -av --delete $WEB_DIR/ $BACKUP_DIR/$DATE/
