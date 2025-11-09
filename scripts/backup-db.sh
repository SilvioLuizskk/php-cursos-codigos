#!/usr/bin/env bash
set -euo pipefail

# Backup script for database (sqlite or mysql)
# Usage: ./scripts/backup-db.sh [--upload-s3] [--keep N]

KEEP=7
UPLOAD_S3=0
S3_BUCKET="${S3_BUCKET:-}" # configure env S3_BUCKET
DB_CONNECTION="${DB_CONNECTION:-sqlite}"
TIMESTAMP=$(date -u +"%Y%m%dT%H%M%SZ")
OUTDIR="./backups"
mkdir -p "$OUTDIR"

while [[ $# -gt 0 ]]; do
  case "$1" in
    --upload-s3) UPLOAD_S3=1; shift;;
    --keep) KEEP="$2"; shift 2;;
    *) shift;;
  esac
done

if [[ "$DB_CONNECTION" == "sqlite" ]]; then
  DB_PATH="${DB_DATABASE:-./database/database.sqlite}"
  if [[ ! -f "$DB_PATH" ]]; then
    echo "SQLite DB not found at $DB_PATH" >&2
    exit 1
  fi
  OUTFILE="$OUTDIR/backup-sqlite-$TIMESTAMP.sqlite"
  cp "$DB_PATH" "$OUTFILE"
  echo "SQLite backup saved to $OUTFILE"
elif [[ "$DB_CONNECTION" == "mysql" || "$DB_CONNECTION" == "mariadb" ]]; then
  # requires mysqldump in PATH and env vars DB_USERNAME DB_PASSWORD DB_HOST DB_PORT DB_DATABASE
  OUTFILE="$OUTDIR/backup-mysql-$TIMESTAMP.sql.gz"
  echo "Dumping MySQL to $OUTFILE"
  mysqldump -h "${DB_HOST:-127.0.0.1}" -P "${DB_PORT:-3306}" -u "${DB_USERNAME:-root}" -p"${DB_PASSWORD:-}" "${DB_DATABASE:-app}" | gzip -c > "$OUTFILE"
  echo "MySQL backup saved to $OUTFILE"
else
  echo "Unsupported DB_CONNECTION: $DB_CONNECTION" >&2
  exit 1
fi

# Rotate backups
ls -1t "$OUTDIR" | tail -n +$((KEEP+1)) | xargs -r -I{} rm -f "$OUTDIR/{}"

echo "Rotated to keep last $KEEP backups"

if [[ "$UPLOAD_S3" -eq 1 ]]; then
  if [[ -z "$S3_BUCKET" ]]; then
    echo "S3_BUCKET not configured. Skipping upload." >&2
  else
    echo "Uploading to s3://$S3_BUCKET/"
    if command -v aws >/dev/null 2>&1; then
      aws s3 cp --recursive "$OUTDIR" "s3://$S3_BUCKET/" --acl private
      echo "Uploaded backups to s3://$S3_BUCKET/"
    else
      echo "aws cli not found. Install or set S3 upload tool." >&2
    fi
  fi
fi

exit 0
