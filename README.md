# makeaminyan
To get set up, make sure you copy all configuration file templates (*.yml.tmp) and update the files to include your secrets.

    cd YOUR_MAM_INSTALL_DIR
    cp config/databases.yml.tmp config/databases.yml
    vi config/databases.yml
    # put your db credentials in, etc
    # do the same for all files ending in *.yml.tmp


We also expect a working installation of Symfony 1.4 to be present at YOUR_MAM_INSTALL_DIR/../1.4

Then, point your webserver to YOUR_MAM_INSTALL_DIR/web/index.php

