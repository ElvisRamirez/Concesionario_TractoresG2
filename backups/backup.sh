#!/bin/bash

# Variables
DB_NAME="Concesionario_Tractores"
DB_USER="postgres"
DB_PASSWORD="593"
BACKUP_PATH="/backups"
REMOTE_USER="kevin"
REMOTE_HOST="192.168.10.10"
REMOTE_PATH="/home/kevin/backups"

# Fecha actual
DATE=$(date +"%Y%m%d%H%M")

# Archivo de backup
BACKUP_FILE="$BACKUP_PATH/$DB_NAME-$DATE.sql"

# Crear el backup de PostgreSQL
export PGPASSWORD=$DB_PASSWORD
pg_dump -U $DB_USER -d $DB_NAME > $BACKUP_FILE

# Transferir el backup al servidor remoto
scp $BACKUP_FILE $REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH

# Eliminar el archivo de backup local después de transferirlo
rm $BACKUP_FILE