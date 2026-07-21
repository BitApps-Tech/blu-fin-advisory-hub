<?php

namespace App\Services;

use App\Models\Order;
use App\Models\SmsSettings;
use Illuminate\Support\Facades\Log;

class OrderSmsService
{
    private SmsService $sms;

    public function __construct(SmsService $sms)
    {
        $this->sms = $sms;
    }

    public function isEnabled(): bool
    {
        $settings = SmsSettings::getActive();

        return $settings !== null
            && $settings->is_active
            && $this->sms->isConfigured();
    }

    /**
     * Send an order confirmation SMS to the customer when SMS is enabled.
     */
    public function sendOrderPlacedNotification(Order $order): bool
    {
        if (!$this->isEnabled() || empty($order->phone)) {
            return false;
        }

        $order->loadMissing('items');

        try {
            $result = $this->sms->send(
                $order->phone,
                $this->buildOrderPlacedMessage($order),
                $order->customer_name,
                'transactional'
            );

            return (bool) ($result['success'] ?? false);
        } catch (\Throwable $e) {
            Log::error('Order confirmation SMS failed', [
                'order_id' => $order->id,
                'order_code' => $order->code,
                'phone' => $order->phone,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function buildOrderPlacedMessage(Order $order): string
    {
        $total = number_format((float) $order->total, 2);

        return "Hi {$order->customer_name}, thank you for your order {$order->code}! "
            . "We've received it and will process it shortly. Total: {$total} ETB. - MamoKacha";
    }
}
