<?php

namespace Ibtikar\GoogleServicesBundle\Service;

use sngrl\PhpFirebaseCloudMessaging\Recipient\Device;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Topic;
use sngrl\PhpFirebaseCloudMessaging\Notification;
use sngrl\PhpFirebaseCloudMessaging\Message;
use sngrl\PhpFirebaseCloudMessaging\Client;

/**
 * @author Mahmoud Mostafa <mahmoud.mostafa@ibtikar.net.sa>
 */
class FirebaseCloudMessaging
{

    /** @var $fireBaseHTTPClient Client */
    private $fireBaseHTTPClient;

    /**
     * @param string $fireBaseAPIKey
     * @throws \Exception
     */
    public function __construct($fireBaseAPIKey)
    {
        if (!$fireBaseAPIKey) {
            throw new \Exception('You should set "firebase_api_key" parameter under the bundle configuration "ibtikar_google_services"');
        }
        $this->fireBaseHTTPClient = new Client();
        $this->fireBaseHTTPClient->setApiKey($fireBaseAPIKey);
        $this->fireBaseHTTPClient->injectGuzzleHttpClient(new \GuzzleHttp\Client());
    }

    /**
     * @param Device|Topic $reciver
     * @param string $notificationTitle
     * @param string $notificationBody
     * @param array $notificationData
     * @param int $deviceNotificationsCount
     * @param string $messagePeriority
     * @return boolean
     */
    private function sendNotification($reciver, $notificationTitle, $notificationBody, array $notificationData = array(), $deviceNotificationsCount = null, $messagePeriority = 'high')
    {
        $message = new Message();
        $message->setPriority($messagePeriority);
        $message->addRecipient($reciver);
        $message->setData($notificationData);
        $notification = new Notification($notificationTitle, $notificationBody);
        if ($deviceNotificationsCount) {
            $notification->setBadge($deviceNotificationsCount);
        }
        $message->setNotification($notification);
        return $this->fireBaseHTTPClient->send($message)->getStatusCode() == 200 ? true : false;
    }

    /**
     * @param string $deviceToken
     * @param string $notificationTitle
     * @param string $notificationBody
     * @param array $notificationData
     * @param int $deviceNotificationsCount
     * @param string $messagePeriority
     * @return boolean
     */
    public function sendNotificationToDevice($deviceToken, $notificationTitle, $notificationBody, array $notificationData = array(), $deviceNotificationsCount = null, $messagePeriority = 'high')
    {
        return $this->sendNotification(new Device($deviceToken), $notificationTitle, $notificationBody, $notificationData, $deviceNotificationsCount, $messagePeriority);
    }

    /**
     * @param string $topicId
     * @param string $notificationTitle
     * @param string $notificationBody
     * @param array $notificationData
     * @param int $deviceNotificationsCount
     * @param string $messagePeriority
     * @return boolean
     */
    public function sendNotificationToTopic($topicId, $notificationTitle, $notificationBody, array $notificationData = array(), $deviceNotificationsCount = null, $messagePeriority = 'high')
    {
        return $this->sendNotification(new Topic($topicId), $notificationTitle, $notificationBody, $notificationData, $deviceNotificationsCount, $messagePeriority);
    }

    /**
     * @param string $topicId
     * @param array $devicesTokens
     * @return boolean
     */
    public function subscribeDevicesToTopic($topicId, array $devicesTokens)
    {
        return $this->fireBaseHTTPClient->addTopicSubscription($topicId, $devicesTokens)->getStatusCode() == 200 ? true : false;
    }

    /**
     * @param string $topicId
     * @param array $devicesTokens
     * @return boolean
     */
    public function removeDevicesFromTopic($topicId, array $devicesTokens)
    {
        return $this->fireBaseHTTPClient->removeTopicSubscription($topicId, $devicesTokens)->getStatusCode() == 200 ? true : false;
    }
}
