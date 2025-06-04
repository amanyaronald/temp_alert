<?php

namespace App\Http\Services;

use App\Models\AlertLog;
use Illuminate\Support\Facades\Log;
use App\Models\TemperatureReading;
use App\Models\Sensor;
use Sensy\Scrud\app\Http\Helpers\Model;
use Twilio\Rest\Api;
use Twilio\Rest\Client;

class SmsService
{
    public function webhook($request)
    {
        $req = request();

        $sms_status = $req->SmsStatus;
        $sms_sid = $req->SmsSid;
        $sms_message_sid = $req->SmsMessageSid;
        $message_sid = $req->MessageSid;
        $from = $req->From;
        $body = $req->Body;

//        // Save raw message
//        $message = Message::create([
//            'sms_status' => $sms_status,
//            'sms_sid' => $sms_sid,
//            'sms_message_sid' => $sms_message_sid,
//            'message_sid' => $message_sid,
//            'from' => $from,
//            'body' => $body,
//        ]);

        Log::info('====SMS FROM: ' . $from . ' BODY: === :' . $body);

        $body = str_replace('Sent from your Twilio Trial account -', '', $body);

        // Handle temperature message
        if (strpos($body, 'sensor=') !== false && strpos($body, 'temp=') !== false) {

            $data = $this->parseTempMessage($body);
//            dd($data,$body);

            if ($data) {
                $data = [
                    'sensor_id' => $data['sensor'],
                    'temperature_value' => $data['temp'],
                    'status' => $data['status'],
                    'received_from' => $from,
                ];

                // Store temperature reading
                $request->data = $data;
                $req = Model::call($request, 'TemperatureReading', 'store',isApi: true);
                $req = json_decode($req->getContent(),true);

                if ($req['status'] == 0) return $req;

                // If it's an alert, store in alerts table
                if (isset($data['type']) && strtolower($data['type']) === 'alert') {
                    $sensor = Sensor::find($data['sensor']);

                    if ($sensor) {

                        $alert = [
                            'room_id' => $sensor->room_id,
                            'sensor_id' => $sensor->id,
                            'temperature_value' => $data['temp'],
                            'alert_type' => $data['status'],
                            'triggered_at' => now(),
                            'status' => 'triggered',
                            'created_by' => 1, // Assuming 1 - System
                        ];

                        $request->merge(['data' => $alert]);
                        $req = Model::call($request, 'TemperatureReading', 'store');
                        return $req;
                    }

                    return [
                        'status' => 0,
                        'message' => 'Sensor not found for alert creation',
                        'data' => null
                    ];
                }

                return $req;
            }

            return [
                'status' => 0,
                'message' => 'Failed to parse temperature data',
                'data' => null
            ];
        }

        // If the message does not contain temperature data, return an error
        return [
            'status' => 0,
            'message' => "Invalid message format or missing temperature data :::: $body",
            'data' => null
        ];
    }

    private function parseTempMessage($body)
    {
        $parts = explode(';', $body);

        $data = [];

        foreach ($parts as $part) {
            $pair = explode('=', $part, 2);
            if (count($pair) === 2) {
                $key = trim($pair[0]);
                $value = trim($pair[1]);
                $data[$key] = $value;
            }
        }

        if (isset($data['sensor'], $data['temp'], $data['status'])) {
            return $data; // 'type' is optional
        }

        return null;
    }

    public function sendMessage($request)
    {
        $req = request();

        $to = $req->to;
        $message = $req->message;

        // Validate input
        if (empty($to) || empty($message)) {
            return [
                'status' => 0,
                'message' => 'To and message fields are required',
                'data' => null
            ];
        }

        try {
            if(!config('services.twilio.active')) {
                return [
                    'status' => 0,
                    'message' => 'SMS configurations disabled',
                    'data' => null
                ];
            }
            $twilio = new Client(config('services.twilio.account_sid'), config('services.twilio.auth_token'));
            $req = $twilio->messages->create($to, ['from' => config('services.twilio.from'), 'body' => $message]);

            if ($req) {
                Log::info("SMS sent to {$to}: {$message}");

                return [
                    'status' => 1,
                    'message' => 'Message sent successfully',
                    'data' => null
                ];
            }

            return [
                'status' => 0,
                'message' => 'Failed to send message--',
                'data' => null
            ];
        } catch (\Exception $e) {
            Log::error("Failed to send SMS: " . $e->getMessage());

            return [
                'status' => 0,
                'message' => 'Failed to send message: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }
}
