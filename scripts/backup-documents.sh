#!/bin/bash

# CONFIGURATION
SOURCE_DIR="/home/sites/preprod/storage/app"
DEST_USER="iphcvbe"
DEST_HOST="ftp.cluster100.hosting.ovh.net"
DEST_PATH="/homez.2213/iphcvbe/sauvegardes_rf"
DATE=$(date +%Y-%m-%d_%H-%M-%S)
ARCHIVE_NAME="app_backup_$DATE.tar.gz"

# 1. Créer l’archive
echo "Compression en cours..."
tar -czf /tmp/$ARCHIVE_NAME -C "$SOURCE_DIR" .

if [ $? -ne 0 ]; then
    echo "Erreur lors de la compression"
    exit 1
fi

# 2. Envoyer via SSH (scp)
echo "Envoi vers le serveur distant..."
scp /tmp/$ARCHIVE_NAME $DEST_USER@$DEST_HOST:$DEST_PATH

if [ $? -ne 0 ]; then
    echo "Erreur lors du transfert SSH"
    exit 1
fi

# 3. Nettoyage local
rm /tmp/$ARCHIVE_NAME

echo "Backup terminé avec succès"
