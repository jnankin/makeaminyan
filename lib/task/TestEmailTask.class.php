<?php

class TestEmailTask extends sfDoctrineBaseTask {

    /**
     * @see sfTask
     */
    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'public'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
        ));

        $this->namespace = 'mam';
        $this->name = 'test-email';
        $this->briefDescription = 'make sure that email works';

        $this->detailedDescription = "";
    }

    /**
     * @see sfTask
     */
    protected function execute($arguments = array(), $options = array()) {
        sfContext::createInstance($this->configuration);

        $blast = Doctrine::getTable('Blast')->find(1);
        $response = Doctrine::getTable('BlastResponse')->find(1);
        
        BlastManager::createTextResponse($response);
    }

}

