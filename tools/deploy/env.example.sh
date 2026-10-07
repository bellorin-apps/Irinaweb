# Copiar a tools/deploy/env.sh (ignorado por Git). Sin contraseñas: solo ruta a la clave.
SSH_HOST="89.117.7.12"
SSH_PORT="65002"
SSH_USER="u855694717"
SSH_KEY="$HOME/.ssh/id_ed25519"          # clave ya autorizada en hPanel (la misma de Sensia)
DOMAIN_ROOT="/home/u855694717/domains/drairinagonzalez.com"   # verificar con: ls ~/domains
WEBROOT="$DOMAIN_ROOT/public_html"
SITE_URL="https://www.drairinagonzalez.com"
NOTIFY_EMAIL="bellgiga@gmail.com"
