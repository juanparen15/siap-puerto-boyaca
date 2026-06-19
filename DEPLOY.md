# Despliegue de SIAP en el servidor

Servidor: `ticsistemas@srvaplicacion` · Apache2 · puerto **8006** · IP **192.168.93.19**
Túnel Cloudflare → **https://siap.ticsistemas.com.co**

Requisitos: PHP 8.2+ (con extensiones de Laravel), Composer, Node 18+ y npm, MySQL/MariaDB, Git.

---

## 1. Clonar el repositorio

```bash
cd /var/www/html
git clone https://github.com/juanparen15/siap-puerto-boyaca.git
cd siap-puerto-boyaca
```

## 2. Dependencias PHP y assets (frontend)

```bash
composer install --no-dev --optimize-autoloader

npm ci
npm run build        # genera public/build (gitignored)
```
> Si el servidor no tiene Node, compila local (`npm run build`) y sube la carpeta `public/build` por SFTP/rsync.

## 3. Variables de entorno

```bash
cp .env.example .env
php artisan key:generate
nano .env
```
Ajusta como mínimo:
```dotenv
APP_NAME="SIAP Puerto Boyacá"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://siap.ticsistemas.com.co

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siap_puerto_boyaca
DB_USERNAME=siap
DB_PASSWORD=********

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS="siap@puertoboyaca-boyaca.gov.co"
MAIL_FROM_NAME="SIAP Puerto Boyacá"
```

## 4. Base de datos

Crea la BD (por phpMyAdmin o CLI):
```sql
CREATE DATABASE siap_puerto_boyaca CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'siap'@'localhost' IDENTIFIED BY '********';
GRANT ALL PRIVILEGES ON siap_puerto_boyaca.* TO 'siap'@'localhost';
FLUSH PRIVILEGES;
```

Migra y crea roles + usuario administrador:
```bash
php artisan migrate --force
php artisan db:seed --class=Database\\Seeders\\RolesSeeder --force   # crea roles super_admin/operador/supervisor/interventoria

# Crear el primer funcionario super_admin
php artisan tinker --execute="\$u=App\Models\User::create(['name'=>'Administrador','email'=>'admin@siap.gov.co','password'=>bcrypt('CAMBIA_ESTA_CLAVE')]); \$u->assignRole('super_admin'); echo 'OK';"
```

## 5. Storage, permisos y cachés

```bash
php artisan storage:link

sudo chown -R www-data:www-data /var/www/html/siap-puerto-boyaca
sudo find /var/www/html/siap-puerto-boyaca -type d -exec chmod 755 {} \;
sudo find /var/www/html/siap-puerto-boyaca -type f -exec chmod 644 {} \;
sudo chmod -R 775 storage bootstrap/cache

php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 6. Videos del inicio (opcional)

Los 3 videos del hero **no están en el repo** (superan 100 MB de GitHub). Súbelos por SFTP a
`public/video/` con estos nombres exactos, o quítalos del hero si no los usarás:
- `Alumbrado-Publico-Vereda-Pavitas.mp4`
- `Alumbrado Público Puerto Gutiérrez.mp4`
- `Alumbrado Calderón.mp4`

## 7. Apache (puerto 8006)

```bash
# Escuchar el puerto 8006
echo "Listen 8006" | sudo tee -a /etc/apache2/ports.conf   # (solo si no existe ya)

# Copiar el vhost incluido en el repo
sudo cp deploy/apache/siap.conf /etc/apache2/sites-available/siap.conf

sudo a2enmod rewrite
sudo a2ensite siap
sudo apache2ctl configtest
sudo systemctl reload apache2
```
Prueba local en el servidor: `curl -I http://192.168.93.19:8006`

## 8. Túnel Cloudflare

En la config del túnel (`/etc/cloudflared/config.yml` o el panel Zero Trust), añade el ingress:
```yaml
ingress:
  - hostname: siap.ticsistemas.com.co
    service: http://192.168.93.19:8006
  # ... resto de reglas ...
  - service: http_status:404
```
Luego: `sudo systemctl restart cloudflared` (o crea la ruta DNS desde el panel).

## 9. Verificación

- https://siap.ticsistemas.com.co → portal público.
- https://siap.ticsistemas.com.co/admin → panel (inicia con el super_admin creado).
- En **Configuración del Sistema** carga las credenciales de Gemini y WhatsApp (Twilio/Meta).
- El botón "Mi ubicación" del mapa requiere HTTPS → funciona vía el túnel.

## Actualizaciones futuras

```bash
cd /var/www/html/siap-puerto-boyaca
git pull
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan optimize:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache
sudo systemctl reload apache2
```
