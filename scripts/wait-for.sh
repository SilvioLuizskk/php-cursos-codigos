#!/usr/bin/env bash
set -euo pipefail

# wait-for.sh host:port [host:port ...] -- wait for TCP ports to be available
# usage: ./wait-for.sh db:3306 web:8000

TIMEOUT=${WAIT_FOR_TIMEOUT:-60}

check(){
  host=$1
  port=$2
  (echo > /dev/tcp/${host}/${port}) >/dev/null 2>&1
}

start=$(date +%s)
for target in "$@"; do
  host=${target%%:*}
  port=${target##*:}
  echo "Waiting for ${host}:${port} (timeout ${TIMEOUT}s)"
  while true; do
    if check "$host" "$port"; then
      echo "${host}:${port} is available"
      break
    fi
    now=$(date +%s)
    if [ $((now - start)) -ge "$TIMEOUT" ]; then
      echo "Timeout waiting for ${host}:${port}" >&2
      exit 1
    fi
    sleep 1
  done
done

exit 0
