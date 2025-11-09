#!/usr/bin/env bash
# Simple backup script for MySQL/Postgres depending on env
# Usage: ./backup_db.sh /path/to/backup/dir
set -euo pipefail
BACKUP_DIR=${1:-./backups}
TIMESTAMP=$(date +"%Y%m%dT%H%M%S")
mkdir -p "$BACKUP_DIR"

# If using MySQL
if [ -n "${DB_CONNECTION:-}" ] && [ "$DB_CONNECTION" = "mysql" ]; then
  echo "Running MySQL dump..."
  mysqldump -h ${DB_HOST:-localhost} -u ${DB_USERNAME:-root} -p${DB_PASSWORD:-} ${DB_DATABASE:-app} > "$BACKUP_DIR/db_$TIMESTAMP.sql"
fi

# If using Postgres
if [ -n "${DB_CONNECTION:-}" ] && [ "$DB_CONNECTION" = "pgsql" ]; then
  echo "Running pg_dump..."
  PGPASSWORD=${DB_PASSWORD:-} pg_dump -h ${DB_HOST:-localhost} -U ${DB_USERNAME:-} -F c -b -v -f "$BACKUP_DIR/db_$TIMESTAMP.dump" ${DB_DATABASE:-}
fi

echo "Backup completed: $BACKUP_DIR"
