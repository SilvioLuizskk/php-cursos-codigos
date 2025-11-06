#!/usr/bin/env bash
set -euo pipefail

# Wrapper to run the Behat docker-compose runner with sudo and fix file ownership
# Usage: ./scripts/run-behat-docker-sudo.sh [behat|up|run|down]

COMPOSE_FILE=docker-compose.bdd.yml
CMD=${1:-behat}

# Determine original user uid/gid for chown after running containers
ORIG_UID=${SUDO_UID:-$(id -u)}
ORIG_GID=${SUDO_GID:-$(id -g)}

run_compose(){
  # run docker-compose with sudo if needed
  if [ "$(id -u)" -ne 0 ]; then
    sudo docker-compose -f "$COMPOSE_FILE" "$@"
  else
    docker-compose -f "$COMPOSE_FILE" "$@"
  fi
}

case "$CMD" in
  behat)
    echo "Building and running behat (with sudo if necessary)..."
    run_compose up --build --remove-orphans behat
    EXIT_CODE=$?
    ;;
  up)
    echo "Starting services in background: web db selenium (with sudo if necessary)"
    run_compose up --build -d web db selenium
    EXIT_CODE=$?
    ;;
  run)
    echo "Running behat against running services (with sudo if necessary)"
    run_compose run --rm behat
    EXIT_CODE=$?
    ;;
  down)
    echo "Stopping and removing containers (with sudo if necessary)"
    run_compose down -v
    EXIT_CODE=$?
    ;;
  *)
    echo "Unknown command: $CMD"
    echo "Usage: $0 [behat|up|run|down]"
    exit 2
    ;;
esac

# Fix ownership of files created by the container (vendor/, storage/, etc.)
# Only attempt if we're not already the original user
if [ "${ORIG_UID}" != "$(id -u)" ] || [ "${ORIG_GID}" != "$(id -g)" ]; then
  echo "Fixing file ownership to UID:GID ${ORIG_UID}:${ORIG_GID}"
  sudo chown -R "${ORIG_UID}:${ORIG_GID}" . || true
fi

exit ${EXIT_CODE:-0}
