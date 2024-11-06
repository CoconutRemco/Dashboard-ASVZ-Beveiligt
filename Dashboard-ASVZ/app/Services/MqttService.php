<?php

namespace App\Services;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\ConnectionSettings;

class MqttService
{
    protected $mqttClient;

    public function __construct()
    {
        $this->mqttClient = new MqttClient('asvz.local', 1883, 'mqtt-subscriber');
    }

    public function connect()
    {
        try {
            $settings = (new ConnectionSettings)
                ->setUsername('asvz')
                ->setPassword('asvz')
                ->setKeepAliveInterval(60)
                ->setLastWillTopic('last/will')
                ->setLastWillMessage('disconnected')
                ->setLastWillQualityOfService(1);

            $this->mqttClient->connect($settings, true);
            \Log::info('Connected to MQTT broker.');
        } catch (MqttClientException $e) {
            \Log::error('Connection to the MQTT broker failed: ' . $e->getMessage());

            // Try to provide more details about why the connection failed
            if (strpos($e->getMessage(), 'php_network_getaddresses') !== false) {
                \Log::error('Failed to resolve the broker address. Ensure that the broker address is correct and reachable.');
            } elseif (strpos($e->getMessage(), 'Socket error') !== false) {
                \Log::error('Socket connection failed. Verify that the broker is running and accessible.');
            }

            throw new \Exception('Could not connect to the MQTT broker.');
        }
    }

    public function subscribeToAvailableDevices(callable $callback)
    {
        $this->connect();

        try {
            $this->mqttClient->subscribe('available_devices', function ($topic, $message) use ($callback) {
                $callback($message);
            }, 0);

            $this->mqttClient->loop(true);
        } catch (MqttClientException $e) {
            \Log::error("Failed to subscribe to MQTT topic: {$e->getMessage()}");
            throw new \Exception('Could not subscribe to MQTT topic.');
        }
    }

    public function publishMessage($topic, $message)
    {
        $this->connect();

        try {
            $this->mqttClient->publish($topic, $message, 0);
            $this->mqttClient->disconnect();
        } catch (MqttClientException $e) {
            \Log::error("Failed to publish MQTT message: {$e->getMessage()}");
        }
    }
}
