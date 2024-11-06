<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MqttService;

class DashboardController extends Controller
{
    protected $mqttService;

    public function __construct(MqttService $mqttService)
    {
        $this->mqttService = $mqttService;
    }

    // Show the dashboard with received messages
    public function showDashboard()
    {
        // Read messages from the file
        $messages = file_exists(storage_path('mqtt_messages.txt'))
            ? file(storage_path('mqtt_messages.txt'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
            : [];

        return view('dashboard', compact('messages'));
    }

    // Send a message via MQTT
    public function sendMessage(Request $request)
    {
        // Validate the request input
        $request->validate([
            'message' => 'required|string',
        ]);

        // Publish the message to the MQTT broker
        $this->mqttService->publishMessage('available_devices', $request->message);

        return redirect()->route('dashboard')->with('success', 'Message sent successfully!');
    }
}
