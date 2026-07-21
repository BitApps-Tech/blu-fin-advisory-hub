<?php

namespace App\Services;

use App\Models\SmsSettings;
use App\Models\SmsLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Format phone number to Ethiopian format (2519XXXXXXXX)
     * Accepts: +251912345678, 251912345678, 0912345678, 912345678
     * Returns: 2519XXXXXXXX
     *
     * @param string $phone
     * @return string
     */
    private function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters except +
        $phone = preg_replace('/[^\d+]/', '', $phone);
        
        // Remove leading + if present
        $phone = ltrim($phone, '+');
        
        // If starts with 0, remove it (local format: 0912345678 -> 912345678)
        if (strpos($phone, '0') === 0) {
            $phone = substr($phone, 1);
        }
        
        // If doesn't start with 251, add it (assuming Ethiopian number)
        if (strpos($phone, '251') !== 0) {
            $phone = '251' . $phone;
        }
        
        return $phone;
    }

    /**
     * Send SMS using configured API settings
     *
     * @param string $to Phone number
     * @param string $message Message content
     * @param string|null $recipientName Optional recipient name
     * @param string $type SMS type (promotional, transactional, notification, alert)
     * @return array
     */
    public function send(string $to, string $message, ?string $recipientName = null, string $type = 'transactional'): array
    {
        // Format phone number to Ethiopian format
        $formattedPhone = $this->formatPhoneNumber($to);
        
        // Create SMS log entry with formatted phone
        $smsLog = SmsLog::create([
            'recipient_phone' => $formattedPhone,
            'recipient_name' => $recipientName,
            'message' => $message,
            'type' => $type,
            'status' => 'pending',
        ]);

        try {
            // Get SMS settings from database
            $smsSettings = SmsSettings::getActive();

            if (!$smsSettings || !$smsSettings->api_url || !$smsSettings->api_key) {
                // Update log with error
                $smsLog->update([
                    'status' => 'failed',
                    'error_message' => 'SMS API settings not configured. Please configure in Settings > SMS.',
                ]);

                throw new \Exception('SMS API settings not configured. Please configure in Settings > SMS.');
            }

            // Make API request to send SMS (using SMS Ethiopia format with formatted phone)
            $response = Http::timeout(30)
                ->withHeaders([
                    'KEY' => $smsSettings->api_key,
                    'Content-Type' => 'application/json',
                ])
                ->post($smsSettings->api_url, [
                    'text' => $message,
                    'msisdn' => $formattedPhone,
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Update log with success
                $smsLog->update([
                    'status' => 'sent',
                    'response' => json_encode($responseData),
                    'message_id' => $responseData['message_id'] ?? $responseData['id'] ?? null,
                    'sent_at' => now(),
                ]);

                return [
                    'success' => true,
                    'message' => 'SMS sent successfully',
                    'data' => $responseData,
                    'log_id' => $smsLog->id,
                ];
            }

            // Update log with failure
            $errorMessage = 'Failed to send SMS: ' . ($response->json()['message'] ?? 'Unknown error');
            $smsLog->update([
                'status' => 'failed',
                'response' => $response->body(),
                'error_message' => $errorMessage,
            ]);

            // Log failed response for debugging
            Log::error('SMS API Error', [
                'log_id' => $smsLog->id,
                'status' => $response->status(),
                'body' => $response->body(),
                'original_phone' => $to,
                'formatted_phone' => $formattedPhone,
            ]);

            return [
                'success' => false,
                'message' => $errorMessage,
                'error' => $response->body(),
                'log_id' => $smsLog->id,
            ];

        } catch (\Exception $e) {
            // Update log with exception
            $smsLog->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            Log::error('SMS Service Exception', [
                'log_id' => $smsLog->id,
                'message' => $e->getMessage(),
                'original_phone' => $to,
                'formatted_phone' => $formattedPhone ?? $to,
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'log_id' => $smsLog->id,
            ];
        }
    }

    /**
     * Send SMS to multiple recipients
     *
     * @param array $recipients Array of phone numbers or [['phone' => '...', 'name' => '...']]
     * @param string $message Message content
     * @param string $type SMS type
     * @return array
     */
    public function sendBulk(array $recipients, string $message, string $type = 'promotional'): array
    {
        $results = [
            'success' => [],
            'failed' => [],
        ];

        foreach ($recipients as $recipient) {
            // Handle both simple array of phone numbers or array of objects with phone and name
            $phone = is_array($recipient) ? ($recipient['phone'] ?? $recipient) : $recipient;
            $name = is_array($recipient) ? ($recipient['name'] ?? null) : null;

            $result = $this->send($phone, $message, $name, $type);
            
            if ($result['success']) {
                $results['success'][] = [
                    'phone' => $phone,
                    'log_id' => $result['log_id'],
                ];
            } else {
                $results['failed'][] = [
                    'phone' => $phone,
                    'error' => $result['message'],
                    'log_id' => $result['log_id'],
                ];
            }
        }

        return [
            'success' => true,
            'message' => sprintf(
                'SMS sent to %d recipients. %d failed.',
                count($results['success']),
                count($results['failed'])
            ),
            'data' => $results,
        ];
    }

    /**
     * Check if SMS service is configured
     *
     * @return bool
     */
    public function isConfigured(): bool
    {
        $smsSettings = SmsSettings::getActive();

        return $smsSettings && !empty($smsSettings->api_url) && !empty($smsSettings->api_key);
    }
}

