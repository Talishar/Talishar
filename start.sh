#!/bin/sh
cp -n HostFiles/RedirectorTemplate.php HostFiles/Redirector.php

# Create a text file with the contents "1" if it doesn't exist
if [ ! -f "HostFiles/GameIDCounter.txt" ]; then
  echo "1" > HostFiles/GameIDCounter.txt
fi

# Set permissions for the file so anyone can update
chmod 666 HostFiles/GameIDCounter.txt

# Create a blank folder if it doesn't exist
mkdir -p Games

# Set permissions for the folder so anyone can update
chmod 777 Games

docker compose up -d
