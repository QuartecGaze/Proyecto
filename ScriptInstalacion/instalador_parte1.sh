#!/bin/bash
set -euo pipefail
IFS=$'\n\t'

############################################
# PARTE 1: CONFIGURACIÓN DE RED
# EJECUTAR ESTE SCRIPT DIRECTAMENTE DESDE
# LA CONSOLA DE LA MÁQUINA VIRTUAL
############################################

# Red / IP fija
INTERFACE="enp0s3"
STATIC_IP="192.168.1.50/24"
GATEWAY="192.168.1.1"
DNS="1.1.1.1 8.8.8.8"

LOGFILE="/var/log/setup_network.log"

log() { echo "[$(date '+%F %T')] $*" | tee -a "$LOGFILE"; }
is_root() { [ "$(id -u)" -eq 0 ]; }

if ! is_root; then
  echo "Ejecutar este script con sudo o como root."
  exit 1
fi

log "=========================================="
log "PARTE 1: CONFIGURACIÓN DE RED"
log "=========================================="

configure_static_ip() {
  log "Configurando IP estática: $STATIC_IP en $INTERFACE"
  
  # Verificar si es Rocky Linux / CentOS (usa NetworkManager)
  if command -v nmcli >/dev/null 2>&1; then
    log "Detectado NetworkManager (Rocky Linux)"
    
    # Obtener nombre de conexión actual
    CON_NAME=$(nmcli -t -f NAME,DEVICE connection show --active | grep "$INTERFACE" | cut -d: -f1)
    
    if [ -z "$CON_NAME" ]; then
      CON_NAME="$INTERFACE"
    fi
    
    log "Configurando conexión: $CON_NAME"
    
    # Configurar IP estática
    nmcli connection modify "$CON_NAME" ipv4.addresses "$STATIC_IP"
    nmcli connection modify "$CON_NAME" ipv4.gateway "$GATEWAY"
    nmcli connection modify "$CON_NAME" ipv4.dns "$DNS"
    nmcli connection modify "$CON_NAME" ipv4.method manual
    
    # Aplicar cambios
    nmcli connection down "$CON_NAME" && nmcli connection up "$CON_NAME"
    
    log "=========================================="
    log "IP estática configurada exitosamente"
    log "=========================================="
    log "Nueva IP: $(ip addr show $INTERFACE | grep 'inet ' | awk '{print $2}')"
    log ""
    log "PRÓXIMO PASO:"
    log "1. Conéctate por SSH usando: ssh user@${STATIC_IP%%/*}"
    log "2. Ejecuta el segundo script: sudo bash install_system_part2.sh"
    log "=========================================="
    
  elif command -v netplan >/dev/null 2>&1; then
    log "Detectado Netplan (Ubuntu/Debian)"
    cat > /etc/netplan/01-static-ip.yml <<EOF
network:
  version: 2
  renderer: networkd
  ethernets:
    ${INTERFACE}:
      dhcp4: false
      addresses: [${STATIC_IP}]
      gateway4: ${GATEWAY}
      nameservers:
        addresses: [${DNS}]
EOF
    netplan apply
    
    log "=========================================="
    log "IP estática configurada exitosamente"
    log "=========================================="
    log "Nueva IP: ${STATIC_IP%%/*}"
    log ""
    log "PRÓXIMO PASO:"
    log "1. Conéctate por SSH usando: ssh user@${STATIC_IP%%/*}"
    log "2. Ejecuta el segundo script: sudo bash install_system_part2.sh"
    log "=========================================="
  else
    log "ERROR: Sistema de red no reconocido"
    exit 1
  fi
}

configure_static_ip

log "Configuración de red completada."
log "El archivo de log está en: $LOGFILE"