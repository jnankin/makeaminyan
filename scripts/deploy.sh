echo "###############################################"
echo "## BUILDING MAKE A MINYAN - $ENV"
echo "###############################################"

chmod 777 -R log

echo
echo "## SETTING UP CONFIGURATION FOR $ENV ENVIRONMENT"

cp config/environments/$ENV/* config
cp apps/public/config/environments/$ENV/* apps/public/config

echo "Copied configuration files from config/environments/$ENV"

echo 
echo "## Database Migrations###########################"

# migrate?
echo Run database migration? [y/n]
read choice

if [ "$choice" = "y" ]; then
        ./symfony doctrine:migrate
        ./symfony doctrine:build-model
        ./symfony cache:clear
        ./symfony doctrine:build-forms
fi


echo
echo "## Cleanup ###########################"

# remove development front controllers
echo Clear non-production controllers? [y/n]
read choice

if [ "$choice" = "y" ]; then 
	./symfony project:clear-controllers
fi

# clear-cache
./symfony cache:clear


echo
echo '## DONE ##############################'
echo "Make a minyan is cocked, locked, and ready to rock ($ENV)!"
