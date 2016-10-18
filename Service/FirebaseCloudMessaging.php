<?php

namespace Ibtikar\GoogleServicesBundle\Service;

/**
 * @author Mahmoud Mostafa <mahmoud.mostafa@ibtikar.net.sa>
 */
class FirebaseCloudMessaging
{

    /** @var $fireBaseAPIKey string */
    private $fireBaseAPIKey;

    /**
     * @param string $fireBaseAPIKey
     * @throws \Exception
     */
    public function __construct($fireBaseAPIKey)
    {
        if (!$fireBaseAPIKey) {
            throw new \Exception('You should set "firebase_api_key" parameter under the bundle configuration "ibtikar_google_services"');
        }
        $this->fireBaseAPIKey = $fireBaseAPIKey;
    }

}
