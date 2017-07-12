<?php

namespace Ibtikar\GoogleServicesBundle\Service;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

/**
 * Ola Ali <ola.ali@ibtikar.net.sa>
 */
class FireBaseRetrievingData {

    protected $firebase_credentials_path;

    public function __construct($firebase_credentials_path) {
        if (!$firebase_credentials_path) {
            throw new \Exception('You should set firebase_credentials_path in config.yml');
        }
        $this->firebase_credentials_path = $firebase_credentials_path;
    }

    public function getDatabaseObject() {
        $serviceAccount = ServiceAccount::fromJsonFile($this->firebase_credentials_path);
        $firebase= (new Factory)
                        ->withServiceAccount($serviceAccount)
                        ->create();

        return $firebase->getDatabase();
    }

}
