#!/bin/bash

# Directorio de respaldos
BACKUP_DIR="/backups"

# Número de días para conservar los respaldos
DIAS=30

# Eliminar archivos y directorios de respaldo más antiguos que el número de días especificado
find "$BACKUP_DIR" -type f -mtime +$DIAS -exec rm -f {} \;

# Eliminar directorios vacíos más antiguos que el número de días especificado
find "$BACKUP_DIR" -type d -empty -mtime +$DIAS -exec rmdir {} \;
