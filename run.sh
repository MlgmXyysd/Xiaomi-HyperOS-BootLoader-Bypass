#!/bin/bash
####
# Setup environment to run application in a docker container
#
# @todo:
#   
#
# @author: stev leibelt <artodeto@bazzline.net>
# @since: 2024-01-31
####

function _build ()
{
  # stop execution if one comand fails
  set -e

  local PATH_OF_THIS_SCRIPT

  PATH_OF_THIS_SCRIPT=$(realpath "$(dirname "${0}")")

  if [[ ! -f "${PATH_OF_THIS_SCRIPT}"/adb.php ]];
  then
    wget -O "${PATH_OF_THIS_SCRIPT}"/adb.php https://raw.githubusercontent.com/MlgmXyysd/php-adb/master/src/adb.php
  fi

  if [[ ! -f "${PATH_OF_THIS_SCRIPT}"/libraries/adb ]];
  then
    wget -O "${PATH_OF_THIS_SCRIPT}"/libraries/tools.zip https://dl.google.com/android/repository/platform-tools_r34.0.5-linux.zip
    unzip -d "${PATH_OF_THIS_SCRIPT}"/libraries "${PATH_OF_THIS_SCRIPT}"/libraries/tools.zip
    rm "${PATH_OF_THIS_SCRIPT}"/libraries/tools.zip
    mv "${PATH_OF_THIS_SCRIPT}"/libraries/platform-tools/* "${PATH_OF_THIS_SCRIPT}"/libraries/
    rmdir "${PATH_OF_THIS_SCRIPT}"/libraries/platform-tools
  fi
}

function _main ()
{
  case "${1}" in
    build)
      _build
      ;;
    login)
      _login
      ;;
    start)
      _start
      ;;
    stop)
      _stop
      ;;
    *)
      echo "Usage: ${0} {build|login|start|stop}"
      return 1
      ;;
  esac
}

function _login ()
{
  _start
  docker compose exec php-cli bash
}

function _start ()
{
  _stop
  _build
  if command -v adb &> /dev/null;
  then
    adb kill-server
  fi
  docker compose up -d
}

function _stop ()
{
  docker compose down
}

_main "${@}"
