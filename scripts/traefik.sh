#!/bin/bash

set -e

TRAEFIK_CONTAINER_NAME="engency-traefik"
SERVICE_GATEWAY_NETWORK_NAME="engency_service_gateway"

#######################################
# Print message
#######################################
print_message() {
    local separator="---------------------------------------------------------------------------------------"

    echo $separator
    echo "$1"
    echo $separator
}

  # Check if the docker network: engency_service_gateway exists, otherwise create it
    if [ ! "$(docker network ls -q -f name=${SERVICE_GATEWAY_NETWORK_NAME})" ]; then
        docker network create "$SERVICE_GATEWAY_NETWORK_NAME"
    fi

    # Check if the engency-traefik container is running, otherwise start it
    if [ ! "$(docker ps -q -f name=${TRAEFIK_CONTAINER_NAME})" ]; then
        if [ "$(docker ps -aq -f status=exited -f status=created -f name=${TRAEFIK_CONTAINER_NAME})" ]; then
            # cleanup
            docker rm ${TRAEFIK_CONTAINER_NAME}
        fi

        # run your container
        docker run \
            -d \
            --name ${TRAEFIK_CONTAINER_NAME} \
            -p 8080:8080 \
            -p 80:80 \
            -p 443:443 \
            -v /var/run/docker.sock:/var/run/docker.sock:ro \
            --network ${SERVICE_GATEWAY_NETWORK_NAME} \
            traefik:v2.10 \
            --providers.docker=true \
            --log.level='DEBUG' \
            --entrypoints.web.address=':80' \
            --entrypoints.websecure.address=':443' \
            --api.insecure=true > /dev/null
    fi

    print_message "Traefik is running on http://localhost:8080"
