#!/bin/bash

# Contenedor de Docker donde se encuentra la base de datos MySQL
DB_CONTAINER="db_mysql"

# Directorio de respaldo dentro del contenedor
BACKUP_DIR="/backups"

# Fecha actual
DATE=$(date +"%Y-%m-%d")

# Nombre del archivo de respaldo
FILE="backup-$DATE.sql"

# Realizar el respaldo de la base de datos MySQL
docker exec $DB_CONTAINER sh -c "mysqldump -u root -pRootAdministrador5749 cooperativa" > /backups/$FILE
