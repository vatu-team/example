#!/usr/bin/env bash

# Check if `SpinupWP` is installed. | If not, install.
wp plugin is-installed spinupwp
if [ $? -eq 1 ]; then wp plugin install spinupwp --activate; fi

# Check if `SpinupWP` is activated. | If not, activate.
wp plugin is-active spinupwp
if [ $? -eq 1 ]; then wp plugin activate spinupwp; fi

# Update SpinupWP.
wp plugin update spinupwp

# Check if `Limit Login Attempts Reloaded` is installed. | If not, install.
wp plugin is-installed limit-login-attempts-reloaded
if [ $? -eq 1 ]; then wp plugin install limit-login-attempts-reloaded --activate; fi

# Check if `Limit Login Attempts Reloaded` is activated. | If not, activate.
wp plugin is-active limit-login-attempts-reloaded
if [ $? -eq 1 ]; then wp plugin activate limit-login-attempts-reloaded; fi

# Update Limit Login Attempts Reloaded.
wp plugin update limit-login-attempts-reloaded

# Update plugin language files.
wp language plugin update --all

# Run WordPress database update if available.
wp core update-db

# Delete expired transients
wp transient delete --expired

# Cache purge
wp cache flush
