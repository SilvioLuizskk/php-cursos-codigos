#!/usr/bin/env bash
set -euo pipefail

# Helper script to run Behat via docker-compose.bdd.yml
# Usage:
#   ./scripts/run-behat-docker.sh           # builds and runs behat (default)
#   ./scripts/run-behat-docker.sh up        # bring up services in background
#   ./scripts/run-behat-docker.sh run       # run behat once

COMPOSE_FILE=docker-compose.bdd.yml

cmd=${1:-behat}

case "$cmd" in
  behat)
    echo "Building and running behat (will start web, db and selenium)..."
    docker-compose -f "$COMPOSE_FILE" up --build behat
    ;;
  up)
    echo "Starting services in background: web db selenium"
    docker-compose -f "$COMPOSE_FILE" up --build -d web db selenium
    ;;
  run)
    echo "Running behat against running services"
    docker-compose -f "$COMPOSE_FILE" run --rm behat
    ;;
  down)
    echo "Stopping and removing containers"
    docker-compose -f "$COMPOSE_FILE" down -v
    ;;
  *)
    echo "Unknown command: $cmd"
    echo "Usage: $0 [behat|up|run|down]"
    exit 2
    ;;
esac
