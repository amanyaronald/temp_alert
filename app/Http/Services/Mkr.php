<?php

namespace App\Http\Services;

use App\Models\Sensor;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class Mkr
{
    protected string $sensorId = '1';
    protected float $minTemp = 22.0;
    protected float $maxTemp = 30.0;
    protected string $phoneNumber = '+256782150448';
    public $s;

    public function __construct()
    {
        ##load default device
        $s = Sensor::first();
        $this->sensorId = $s->id;
        $th = $s->room->threshold;
        $this->minTemp = $th->min_temperature;
        $this->maxTemp = $th->max_temperature;

        Log::critical("[MOCK] Initialized Mkr service with Sensor ID:. $this->sensorId; Min Temp: {$this->minTemp}, Max Temp: {$this->maxTemp}, Phone: {$this->phoneNumber}");
    }

    public function setMin(float $value): void
    {
        $this->minTemp = $value;
        Log::info("[MOCK] MIN temp set to $value");
    }

    public function setMax(float $value): void
    {
        $this->maxTemp = $value;
        Log::info("[MOCK] MAX temp set to $value");
    }

    public function simulateReading(float $temp): void
    {
        $status = 'normal';
        if ($temp < $this->minTemp) {
            $status = 'low';
        } elseif ($temp > $this->maxTemp) {
            $status = 'high';
        }

        Log::debug("[MOCK] Simulated temperature: $temp, Status: $status. Min: {$this->minTemp}, Max: {$this->maxTemp}");
        $this->sendTempReport($temp, $status);
    }

    public function processIncomingSms(string $rawMessage): void
    {
        $message = str_replace('Sent from your Twilio Trial account', '', $rawMessage);
        $message = trim($message);

        if (stripos($message, 'TEST') !== false) {
            $this->sendSms("Test message received successfully.");
            return;
        }

        if (stripos($message, 'SET_MAX=') !== false || stripos($message, 'SET MIN=') !== false) {
            // Parse SET_MAX
            if (preg_match('/SET_MAX=([0-9.]+)/', $message, $matches)) {
                $this->setMax((float)$matches[1]);
                $this->sendThresholdResponse('max_set', $matches[1]);
            }

            // Parse SET MIN
            if (preg_match('/SET MIN=([0-9.]+)/', $message, $matches)) {
                $this->setMin((float)$matches[1]);
                $this->sendThresholdResponse('min_set', $matches[1]);
            }
        }
    }

    protected function sendSms(string $message): void
    {
        Log::info("[MOCK SMS to {$this->phoneNumber}] $message");

        ##send SMS

        ## directly trigger webhook
        $webhookUrl = env('SMS_WEBHOOK_URL', route('smsWebhook'));
        $response = \Illuminate\Support\Facades\Http::get($webhookUrl, [
            'Body' => $message,
        ]);

        Log::debug('RESPONSE FROM WEBHOOK', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
    }

    protected function sendTempReport(float $temp, string $status): void
    {
        $message = "sensor={$this->sensorId};temp=" . number_format($temp, 1) . ";status={$status}";
        $this->sendSms($message);
    }

    protected function sendThresholdResponse(string $key, float|string $value): void
    {
        $message = "sensor={$this->sensorId};{$key}=" . number_format((float)$value, 1);
        $this->sendSms($message);
    }

    public function scheduledReport(): void
    {
        $temp = $this->generateMockTemperature(); // optional
        $this->simulateReading($temp);
    }

    protected function generateMockTemperature(): float
    {
        ##load config
        $m_temp = l_config('m_temp')->k_value;
        $m_temp_variation = l_config('m_temp_variation')->k_value;

        ##randomly select a positive,negative or 0 and apply to the base
        $variation = mt_rand(-$m_temp_variation * 10, $m_temp_variation * 10) / 10;
        $baseTemp = (float)$m_temp;
        $temp = $baseTemp + $variation;

        Log::info("[MOCK] Generated temperature: $temp (Base: $baseTemp, Variation: $variation)");
        return $temp;
    }
}
