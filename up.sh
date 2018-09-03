#!/bin/sh

RED=`tput setaf 1`
GREEN=`tput setaf 2`
RESET=`tput sgr0`

function print_info {
    printf "\n"
    echo "---------------------------------------------------"
    echo "${GREEN}$@${RESET}"
    echo "---------------------------------------------------"
    printf "\n"
}

function print_error {
    printf "\n"
    echo "---------------------------------------------------"
    echo "${RED}$@${RESET}"
    echo "---------------------------------------------------"
    printf "\n"
}

{
    print_info "Here we go!!! 🚀"

    while true
    do
        read -p "Please, provide your MessageBird API KEY 😎 " answer
        case $answer in
        "" ) echo "⚠️  Error ⚠️\n";;

        * ) sed "s/MBAPIKEY/$answer/g" ".env.sample" > .env; break ;;
        esac
    done

    print_info "Insalling application dependencies 📦"
    composer install -o

    print_info "Building the container 🐳"
    docker-compose up -d
} || {
    print_error "Ooops... Something goes wrong 😱"
}