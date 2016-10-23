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
     * @param array $data
     * @param string $messagePeriority
     * @return boolean
     */
    private function sendMessage($reciver, array $data, $messagePeriority = 'high')
    {
        if (count($data) === 0) {
            throw new \Exception('You must set the data to send.');
        }
        $message = new Message();
        $message->setPriority($messagePeriority);
        $message->addRecipient($reciver);
        if (count($data) > 0) {
            $message->setData($data);
        }
        return $this->fireBaseHTTPClient->send($message)->getStatusCode() == 200 ? true : false;
    }

    /**
     * @param string $deviceToken
     * @param array $data
     * @param string $messagePeriority
     * @return boolean
     */
    public function sendMessageToDevice($deviceToken, array $data, $messagePeriority = 'high')
    {
        return $this->sendMessage(new Device($deviceToken), $data, $messagePeriority);
    }

    /**
     * @param string $topicId
     * @param array $data
     * @param string $messagePeriority
     * @return boolean
     */
    public function sendMessageToTopic($topicId, array $data, $messagePeriority = 'high')
    {
        return $this->sendMessage(new Topic($topicId), $data, $messagePeriority);
    }

    /**
     * @param Device|Topic $reciver
     * @param string $notificationTitle
     * @param string $notificationBody
     * @param array $data
     * @param int $deviceNotificationsCount
     * @param string $messagePeriority
     * @return boolean
     */
    private function sendNotification($reciver, $notificationTitle, $notificationBody, array $data = array(), $deviceNotificationsCount = null, $messagePeriority = 'high')
    {
        $notification = new Notification($notificationTitle, $notificationBody);
        $notification->setPriority($messagePeriority);
        $notification->addRecipient($reciver);
        if (count($data) > 0) {
            $notification->setData($data);
        }
        if ($deviceNotificationsCount) {
            $notification->setBadge($deviceNotificationsCount);
        }
        return $this->fireBaseHTTPClient->send($notification)->getStatusCode() == 200 ? true : false;
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
