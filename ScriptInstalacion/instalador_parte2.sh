#!/bin/bash
set -euo pipefail
IFS=$'\n\t'

############################################
# PARTE 2: INSTALACIÓN PRINCIPAL
# EJECUTAR ESTE SCRIPT POR SSH DESPUÉS DE
# HABER CONFIGURADO LA RED CON LA PARTE 1
############################################

# Git repo del proyecto
GIT_REPO="https://github.com/QuartecGaze/Proyecto"
GIT_BRANCH="main"
APP_DIR="/opt/mi_proyecto"

# Docker / MySQL settings
MYSQL_ROOT_PASSWORD="RootAdministrador5749"
MYSQL_DB="cooperativa"

# Configuración de backups
BACKUP_BASE_DIR="/backups"
SCRIPTS_DIR="/home/scripts"

# Puerto rsync y contenedor ubuntu
RSYNC_PORT=873
UBUNTU_RSYN_IMAGE="sys_ubuntu_rsync:latest"
UBUNTU_RSYN_CONTAINER="ubuntu-rsync"

# Otros
DOCKER_COMPOSE_FILE="docker-compose.yml"
LOGFILE="/var/log/install_system.log"

############################################
# FUNCIONES UTILES
############################################
log() { echo "[$(date '+%F %T')] $*" | tee -a "$LOGFILE"; }
is_root() { [ "$(id -u)" -eq 0 ]; }

if ! is_root; then
  echo "Ejecutar este script con sudo o como root."
  exit 1
fi

log "=========================================="
log "PARTE 2: INSTALACIÓN PRINCIPAL DEL SISTEMA"
log "=========================================="

############################################
# 1) Limpiar caché y actualizar certificados
############################################
log "Limpiando caché y actualizando certificados..."
dnf clean all
rm -rf /var/cache/dnf
dnf -y reinstall ca-certificates
update-ca-trust force-enable
update-ca-trust extract

############################################
# 2) Instalar paquetes base
############################################
install_base_packages() {
  log "Instalando paquetes base"
  
  # Actualizar sin paquetes problemáticos
  dnf -y update --exclude=kmod-kvdo --skip-broken || log "Algunas actualizaciones fallaron, continuando..."

  dnf -y install \
    ca-certificates curl gnupg2 redhat-lsb-core \
    git openssh-server iptables iptables-services rsync \
    mysql cronie \
    make gcc gcc-c++ jq
  
  # Habilitar crond
  systemctl enable --now crond
}
install_base_packages

############################################
# 3) Instalar Docker
############################################
install_docker() {
  log "Instalando Docker y docker-compose en Rocky Linux"

  if ! command -v docker >/dev/null 2>&1; then
    log "Agregando repositorio oficial de Docker..."
    
    # Eliminar posibles conflictos
    dnf -y remove podman buildah || true

    # Instalar utilidades necesarias
    dnf -y install dnf-plugins-core ca-certificates curl gnupg2 redhat-lsb-core

    # Agregar el repositorio oficial de Docker (CentOS funciona para Rocky)
    dnf config-manager --add-repo https://download.docker.com/linux/centos/docker-ce.repo

    # Actualizar metadata e instalar Docker y complementos
    dnf -y install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin --nobest

    # Habilitar e iniciar Docker
    systemctl enable --now docker

    log "✓ Docker instalado y en ejecución."
  else
    log "Docker ya está instalado, saltando."
  fi

  # Verificar docker compose plugin
  if ! docker compose version >/dev/null 2>&1; then
    log "No se detectó docker compose plugin, reinstalando..."
    dnf -y reinstall docker-compose-plugin
  fi

  log "✓ Docker y Docker Compose instalados correctamente."
}

install_docker

############################################
# 4) Descargar imágenes base
############################################
pull_images() {
  log "Descargando imágenes base de Docker..."
  docker pull php:8.1-apache
  docker pull mysql:8.0
  docker pull phpmyadmin:latest
  docker pull ubuntu:22.04
  log "✓ Imágenes descargadas"
}
pull_images

############################################
# 5) Clonar proyecto
############################################
clone_repo() {
  log "Clonando repo $GIT_REPO"
  if [ -d "$APP_DIR" ]; then
    cd "$APP_DIR" && git pull origin "$GIT_BRANCH"
  else
    git clone --branch "$GIT_BRANCH" "$GIT_REPO" "$APP_DIR"
  fi
  log "✓ Repositorio clonado"
}
clone_repo

############################################
# 6) Levantar entorno con docker-compose
############################################
start_compose_from_repo() {
  cd "$APP_DIR"
  export MYSQL_ROOT_PASSWORD="${MYSQL_ROOT_PASSWORD}"
  export MYSQL_DATABASE="${MYSQL_DB}"

  # Verificar estructura del proyecto
  log "Contenido del proyecto clonado:"
  ls -la "$APP_DIR" | tee -a "$LOGFILE"

  if [ -f "$DOCKER_COMPOSE_FILE" ]; then
    log "Usando docker-compose.yml del repositorio"
    
    # Corregir la ruta del volumen del SQL en docker-compose.yml
    if grep -q "/home/proyecto/cooperativa.sql" "$DOCKER_COMPOSE_FILE"; then
      log "Corrigiendo ruta del archivo SQL en docker-compose.yml..."
      sed -i "s|/home/proyecto/cooperativa.sql|$APP_DIR/cooperativa.sql|g" "$DOCKER_COMPOSE_FILE"
    fi
    
    # Corregir el montaje de Proyecto a raíz
    if grep -q "./Proyecto:/var/www/html" "$DOCKER_COMPOSE_FILE"; then
      log "Corrigiendo montaje del volumen web..."
      sed -i "s|./Proyecto:/var/www/html|.:/var/www/html|g" "$DOCKER_COMPOSE_FILE"
    fi
    
    # Verificar que el archivo cooperativa.sql existe
    if [ ! -f "$APP_DIR/cooperativa.sql" ]; then
      log "ADVERTENCIA: cooperativa.sql no encontrado, comentando importación automática..."
      sed -i 's|^\s*- .*cooperativa.sql:/docker-entrypoint-initdb.d/cooperativa.sql|      # - ./cooperativa.sql:/docker-entrypoint-initdb.d/cooperativa.sql|' "$DOCKER_COMPOSE_FILE"
    fi
    
    log "docker-compose.yml corregido:"
    cat "$DOCKER_COMPOSE_FILE" | tee -a "$LOGFILE"
    
    docker compose up -d --build
  else
    log "docker-compose.yml no encontrado, creando uno básico..."
    # Compose alternativo básico (php + mysql + phpmyadmin)
    # IMPORTANTE: Los archivos están en la raíz del repo, no en /src
    cat > docker-compose.yml <<EOF
version: '3.8'
services:
  db:
    image: mysql:8.0
    container_name: db_mysql
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: ${MYSQL_ROOT_PASSWORD}
      MYSQL_DATABASE: ${MYSQL_DB}
    volumes:
      - db_data:/var/lib/mysql
    ports:
      - "3306:3306"

  php:
    image: php:8.1-apache
    container_name: php_app
    volumes:
      - .:/var/www/html
    depends_on:
      - db
    ports:
      - "80:80"
    command: bash -c "docker-php-ext-install mysqli && apache2-foreground"

  phpmyadmin:
    image: phpmyadmin:latest
    restart: always
    environment:
      PMA_HOST: db
      PMA_USER: root
      PMA_PASSWORD: ${MYSQL_ROOT_PASSWORD}
    ports:
      - "8081:80"
    depends_on:
      - db

volumes:
  db_data:
EOF
    docker compose up -d --build
  fi
  
  # Verificar qué se montó en el contenedor
  log "Verificando contenido montado en el contenedor PHP:"
  PHP_CONTAINER=$(docker ps --format '{{.Names}}' | grep 'php' | head -n1)
  if [ -n "$PHP_CONTAINER" ]; then
    docker exec "$PHP_CONTAINER" ls -la /var/www/html | head -20 | tee -a "$LOGFILE"
  else
    log "ADVERTENCIA: No se encontró contenedor PHP"
  fi
  
  # Configurar permisos para uploads y archivos
  log "Configurando permisos de escritura para www-data..."
  if [ -d "$APP_DIR/Recursos" ]; then
    chown -R 33:33 "$APP_DIR/Recursos"
    chmod -R 775 "$APP_DIR/Recursos"
    log "✓ Permisos configurados en Recursos/"
  fi
  
  if [ -d "$APP_DIR/Fotos" ]; then
    chown -R 33:33 "$APP_DIR/Fotos"
    chmod -R 775 "$APP_DIR/Fotos"
    log "✓ Permisos configurados en Fotos/"
  fi
  
  # Configurar permisos en cualquier carpeta de uploads
  for dir in uploads tmp cache storage; do
    if [ -d "$APP_DIR/$dir" ]; then
      chown -R 33:33 "$APP_DIR/$dir"
      chmod -R 775 "$APP_DIR/$dir"
      log "✓ Permisos configurados en $dir/"
    fi
  done
  
  log "✓ Permisos de escritura configurados correctamente."
}
start_compose_from_repo

############################################
# 7) Inicializar MySQL automáticamente
############################################
init_or_fix_mysql() {
  log "Esperando a que MySQL esté listo..."

  # Nombre del contenedor
  MYSQL_CONT=$(docker ps --format '{{.Names}}' | grep -E 'mysql|db' | head -n1)
  if [ -z "$MYSQL_CONT" ]; then
    log "No se encontró contenedor MySQL corriendo. Intentando iniciar..."
    cd "$APP_DIR"
    docker compose up -d db
    sleep 5
    MYSQL_CONT=$(docker ps --format '{{.Names}}' | grep -E 'mysql|db' | head -n1)
    if [ -z "$MYSQL_CONT" ]; then
      log "ERROR: No se pudo iniciar el contenedor MySQL"
      log "Verifica los logs: docker logs db_mysql"
      return 1
    fi
  fi

  # Espera activa hasta que MySQL responda
  MAX_TRIES=30
  COUNT=0
  until docker exec "$MYSQL_CONT" mysqladmin ping -h localhost --silent 2>/dev/null; do
    COUNT=$((COUNT + 1))
    if [ $COUNT -ge $MAX_TRIES ]; then
      log "ERROR: MySQL no respondió después de $MAX_TRIES intentos"
      log "Logs de MySQL:"
      docker logs "$MYSQL_CONT" | tail -50 | tee -a "$LOGFILE"
      return 1
    fi
    sleep 3
    log "Esperando a que MySQL responda... (intento $COUNT/$MAX_TRIES)"
  done

  # Verificar conexión con la contraseña configurada
  if docker exec "$MYSQL_CONT" mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" -e "SELECT 1;" 2>/dev/null; then
    log "✓ Conexión a MySQL verificada correctamente."
  else
    log "ERROR: No se pudo conectar a MySQL con la contraseña configurada."
    log "Intentando recrear MySQL desde cero..."
    cd "$APP_DIR"
    docker compose down -v
    docker volume prune -f
    sleep 3
    docker compose up -d --build
    
    # Esperar más tiempo para que MySQL se inicialice completamente
    log "Esperando 45 segundos para que MySQL se inicialice completamente..."
    sleep 45
    
    # Obtener el nombre del contenedor nuevamente
    MYSQL_CONT=$(docker ps --format '{{.Names}}' | grep -E 'mysql|db' | head -n1)
    
    # Verificar que MySQL responde
    MAX_TRIES=20
    COUNT=0
    until docker exec "$MYSQL_CONT" mysqladmin ping -h localhost --silent 2>/dev/null; do
      COUNT=$((COUNT + 1))
      if [ $COUNT -ge $MAX_TRIES ]; then
        log "ERROR: MySQL no responde después de recrear"
        docker logs "$MYSQL_CONT" | tail -50 | tee -a "$LOGFILE"
        return 1
      fi
      sleep 3
      log "Esperando respuesta de MySQL... ($COUNT/$MAX_TRIES)"
    done
    
    # Reintentar conexión
    if docker exec "$MYSQL_CONT" mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" -e "SELECT 1;" 2>/dev/null; then
      log "✓ MySQL recreado exitosamente."
    else
      log "ERROR: No se pudo conectar a MySQL después de recrear."
      log "Logs de MySQL:"
      docker logs "$MYSQL_CONT" | tail -50 | tee -a "$LOGFILE"
      return 1
    fi
  fi

  # Crear base de datos si no existe
  log "Creando base de datos ${MYSQL_DB} si no existe..."
  docker exec "$MYSQL_CONT" mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" -e "CREATE DATABASE IF NOT EXISTS ${MYSQL_DB};" 2>/dev/null

  # Importar SQL si existe y la BD está vacía
  if [ -f "$APP_DIR/cooperativa.sql" ]; then
    log "Verificando si es necesario importar cooperativa.sql..."
    TABLES_COUNT=$(docker exec "$MYSQL_CONT" mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" -e "USE ${MYSQL_DB}; SHOW TABLES;" 2>/dev/null | wc -l)
    if [ "$TABLES_COUNT" -le 1 ]; then
      log "Importando cooperativa.sql..."
      docker exec -i "$MYSQL_CONT" mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" "${MYSQL_DB}" < "$APP_DIR/cooperativa.sql" 2>&1 | tee -a "$LOGFILE"
      log "✓ Base de datos importada."
    else
      log "La base de datos ya tiene tablas, saltando importación."
    fi
  fi

  # Crear usuarios API si no existen
  log "Creando/verificando usuarios API..."
  docker exec -i "$MYSQL_CONT" mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" 2>/dev/null <<EOF
CREATE USER IF NOT EXISTS 'api_usuarios'@'%' IDENTIFIED BY 'Usuarios123!';
CREATE USER IF NOT EXISTS 'api_backoffice'@'%' IDENTIFIED BY 'BackOffice123!';
CREATE USER IF NOT EXISTS 'api_cooperativa'@'%' IDENTIFIED BY 'Cooperativa123!';
CREATE USER IF NOT EXISTS 'api_traducciones'@'%' IDENTIFIED BY 'Traducciones123!';

GRANT SELECT, INSERT, UPDATE, DELETE ON ${MYSQL_DB}.* TO 'api_usuarios'@'%';
GRANT SELECT, INSERT, UPDATE, DELETE ON ${MYSQL_DB}.* TO 'api_backoffice'@'%';
GRANT SELECT, INSERT, UPDATE, DELETE ON ${MYSQL_DB}.* TO 'api_cooperativa'@'%';
GRANT SELECT, UPDATE ON ${MYSQL_DB}.* TO 'api_traducciones'@'%';

FLUSH PRIVILEGES;
EOF
  
  if [ $? -eq 0 ]; then
    log "✓ Usuarios MySQL verificados o creados correctamente."
  else
    log "ADVERTENCIA: Hubo problemas creando usuarios MySQL"
  fi
}
init_or_fix_mysql

############################################
# 8) Configurar scripts de backup desde GitHub
############################################
setup_backup_scripts() {
  log "Configurando scripts de backup..."
  
  # Crear directorios necesarios
  mkdir -p "$BACKUP_BASE_DIR"
  mkdir -p "$SCRIPTS_DIR"
  
  # Copiar scripts desde el repositorio
  if [ -f "$APP_DIR/backup_mysql.sh" ]; then
    log "Copiando scripts de backup desde el repositorio..."
    cp "$APP_DIR/backup_mysql.sh" "$SCRIPTS_DIR/"
    cp "$APP_DIR/backup_web.sh" "$SCRIPTS_DIR/"
    cp "$APP_DIR/cleanup_backups.sh" "$SCRIPTS_DIR/"
    
    # Hacer ejecutables
    chmod +x "$SCRIPTS_DIR/backup_mysql.sh"
    chmod +x "$SCRIPTS_DIR/backup_web.sh"
    chmod +x "$SCRIPTS_DIR/cleanup_backups.sh"
    
    log "✓ Scripts de backup copiados y configurados."
  else
    log "ADVERTENCIA: Scripts de backup no encontrados en $APP_DIR"
  fi
}
setup_backup_scripts

############################################
# 9) Contenedor Ubuntu con rsync (backup_service)
############################################
run_ubuntu_rsync() {
  log "Configurando contenedor de backup..."
  
  docker rm -f "$UBUNTU_RSYN_CONTAINER" >/dev/null 2>&1 || true
  docker rm -f backup_service >/dev/null 2>&1 || true
  
  cat > /tmp/Dockerfile_ubuntu_rsync <<'EOF'
FROM ubuntu:22.04
RUN apt-get update && apt-get install -y rsync openssh-server && rm -rf /var/lib/apt/lists/*
RUN mkdir -p /var/run/sshd
EXPOSE 873 22
CMD ["/usr/sbin/sshd","-D"]
EOF
  
  docker build -t "$UBUNTU_RSYN_IMAGE" -f /tmp/Dockerfile_ubuntu_rsync /tmp
  
  # Crear contenedor según Comandos.sh
  docker run -d \
    --name backup_service \
    -v "$APP_DIR":/var/www/html \
    -v "$BACKUP_BASE_DIR":/backups \
    ubuntu:22.04 \
    sleep infinity
  
  log "✓ Contenedor backup_service creado."
}
run_ubuntu_rsync

############################################
# 10) Configurar crontab para backups
############################################
configure_cron() {
  log "Configurando tareas programadas (cron)..."
  
  # Crear archivo de crontab temporal
  cat > /tmp/backup_cron <<EOF
# Backup web semanal (domingo 03:00)
0 3 * * 0 $SCRIPTS_DIR/backup_web.sh

# Backup DB diario (02:00)
0 2 * * * $SCRIPTS_DIR/backup_mysql.sh

# Limpieza diaria de copias viejas (04:00)
0 4 * * * $SCRIPTS_DIR/cleanup_backups.sh
EOF
  
  # Instalar crontab
  crontab /tmp/backup_cron
  rm /tmp/backup_cron
  
  log "✓ Cron configurado correctamente."
  crontab -l
}
configure_cron

############################################
# 11) Configurar iptables
############################################
configure_iptables() {
  log "Configurando firewall con iptables..."
  
  # Deshabilitar firewalld si está activo
  systemctl stop firewalld 2>/dev/null || true
  systemctl disable firewalld 2>/dev/null || true
  
  # Configurar reglas de iptables
  iptables -I INPUT -p tcp --dport 22 -j ACCEPT
  iptables -I INPUT -p tcp --dport 80 -j ACCEPT
  iptables -I INPUT -p tcp --dport 443 -j ACCEPT
  iptables -I INPUT -p tcp --dport 3306 -j ACCEPT
  iptables -I INPUT -p tcp --dport 8081 -j ACCEPT
  iptables -I INPUT -p tcp --dport ${RSYNC_PORT} -j ACCEPT
  iptables -I INPUT -m conntrack --ctstate ESTABLISHED,RELATED -j ACCEPT
  
  # Guardar reglas
  service iptables save 2>/dev/null || iptables-save > /etc/sysconfig/iptables 2>/dev/null || true
  systemctl enable iptables 2>/dev/null || true
  
  log "✓ Firewall configurado con iptables"
}
configure_iptables

############################################
# 12) Verificación final
############################################
final_checks() {
  log ""
  log "=========================================="
  log "INSTALACIÓN COMPLETADA CON ÉXITO"
  log "=========================================="
  log ""
  log "Contenedores activos:"
  docker ps --format "table {{.Names}}\t{{.Image}}\t{{.Status}}\t{{.Ports}}"
  log ""
  log "Servicios disponibles:"
  IP_ADDR=$(hostname -I | awk '{print $1}')
  log "  • Aplicación web:  http://$IP_ADDR"
  log "  • phpMyAdmin:      http://$IP_ADDR:8081"
  log "  • MySQL (puerto):  $IP_ADDR:3306"
  log ""
  log "Logs de instalación: $LOGFILE"
  log "=========================================="
}
final_checks