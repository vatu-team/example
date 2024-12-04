#!/bin/bash
#
# Setup Development Site.
#
# Run script in docker via
# `docker-compose -f ./.docker/docker-compose.yml --env-file ./.docker/.env run wpcli bash ./bin/setup.sh`.
#
# We expect this file may need some customisation work for each project, so check its contents before running.

# Set WP Options
wp language core install en_GB
wp site switch-language en_GB
echo '✔ Installed and activated en_GB'

## General Settings.

### Empty Blog description.
wp option update blogdescription ""
echo '✔ Removed blog description'

### Membership: Anyone can register.
wp option update users_can_register "0"
echo '✔ Disabled user registration'

### New User Default Role.
wp option update default_role "subscriber"
echo '✔ Set default role to Subscriber'

### Timezone
wp option update timezone_string "Europe/London"
echo '✔ Set timezone to Europe/London'

### Date Format.
wp option update date_format "l j F Y"
echo '✔ Set the date format to be `l j F Y`'

### Time Format.
wp option update time_format "g:i a"
echo '✔ Set time format to be `g:i a`'

### Week Starts On
wp option update start_of_week "1"
echo '✔ Set the week to start on Monday'

## Discussion Settings.

### Default Post Settings
wp option update default_pingback_flag "" 
wp option update default_ping_status "" 
wp option update default_comment_status "" 
echo '✔ Disabled Pingbacks and Comment status'

### Avatar Display
wp option update show_avatars "" 
echo '✔ Disabled avatars'

## Media Settings

### Image sizes
wp option update thumbnail_size_w "480" 
wp option update thumbnail_size_h "480" 
wp option update thumbnail_crop "1" 
wp option update medium_size_w "1024" 
wp option update medium_size_h "1024" 
wp option update large_size_w "2000" 
wp option update large_size_h "2000" 
echo '✔ Set default image sizes'

### Uploading Files
wp option update uploads_use_yearmonth_folders "1" 
echo '✔ Set uploads to use yearmonth folders'

## Permalink Settings

### Common Settings
wp option update permalink_structure "/%category%/%postname%" 
echo '✔ Set permalink structure to `/%category%/%postname%`'

## Posts
wp post delete 1 --force 
echo '✔ Removed default post'

## Pages
wp post delete 2 --force 
echo '✔ Removed default page'

# Create test users.
# wp user create editor editor@client.test --role=editor --user_pass=password 
# echo '✔ Created default users'

# Activate theme.
# wp theme activate theme-name 
# echo '✔ Activated the theme'

# Activate plugins.
wp plugin activate disable-emojis wp-robots-txt aryo-activity-log better-passwords redirection wordpress-seo debug-bar-post-types duplicate-post log-deprecated-notices query-monitor redis-cache rewrite-rules-inspector rewrite-testing transients-manager user-switching wordpress-beta-tester wordpress-importer wp-crontrol 
echo '✔ Activated the plugins'

# Create menus
wp menu create "Main Menu" 
wp menu create "Footer Menu" 
echo '✔ Created the menus'

# Build homepage hero
homepage="$(wp post create --post_title="Homepage" --post_type=page --post_status=publish --porcelain )"
wp option update show_on_front page 
wp option update page_on_front $homepage 
echo '✔ Created homepage and set as page_on_front'

echo '✔ All done, time for a brew.'
