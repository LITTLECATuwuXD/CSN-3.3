#!/bin/bash
set -e

# 0. Esperar a que la base de datos esté lista (máx. 90 s) — corrige el 502 por orden de arranque
echo "Esperando a la base de datos..."
for i in {1..18}; do
  if php artisan db:show > /dev/null 2>&1; then
    echo "Base de datos lista."; break
  fi
  echo "Intento $i fallido; reintentando en 5 s..."
  sleep 5
done

# 1. Migraciones idempotentes: crea tablas SOLO si faltan. No ejecuta seeders.
php artisan migrate --force


# Asegurar que exista el directorio de vistas compiladas
mkdir -p /tmp/views

# 2. Symlink de storage (portadas subidas como archivo)
php artisan storage:link || true


# 3. Cachés de producción (generadas DESPUÉS de que las variables de Railway existen)
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
