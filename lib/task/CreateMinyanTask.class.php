<?php

class CreateMinyanTask extends sfDoctrineBaseTask {

    /**
     * @see sfTask
     */
    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'public'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
        ));

        $this->namespace = 'mam';
        $this->name = 'createMinyan';
        $this->briefDescription = 'create a minyan';

        $this->detailedDescription = "";
    }

    /**
     * @see sfTask
     */
    protected function execute($arguments = array(), $options = array()) {
        sfContext::createInstance($this->configuration);
        $displayName = $this->ask("Enter a minyan display name (Darchei Noam Glenbrook)");
        $identifier = $this->ask("Enter minyan identifier (dng)");
        $email = $this->ask("Enter username for minyan admin");

        $user = Doctrine::getTable('SfGuardUser')->findOneByUsername(trim(strtolower($email)));

        if (!$user){
            throw new Exception("User with email $email does not exist");
        }

        try {
            $con = Doctrine::getConnectionByTableName("SfGuardUser");
            $con->beginTransaction();
            $minyan = new Minyan();
            $minyan->setName($displayName);
            $minyan->setIdentifier(Utils::formatPermalink($identifier));
            $minyan->save();

            $minyanUser = new MinyanUser();
            $minyanUser->setIsAdmin(true);
            $minyanUser->setUserId($user->getId());
            $minyanUser->setMinyanId($minyan->getId());
            $minyanUser->save();

            $this->logSection('mam', "Minyan $identifier created successfully!");
            $con->commit();
        }
        catch (Exception $e){
            $con->rollback();
            throw $e;
        }

    }

}


