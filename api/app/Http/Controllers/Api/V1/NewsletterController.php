<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Mail\NewsletterBroadcast;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use App\Services\EmailDeliveryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function campaigns(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);

        $campaigns = NewsletterCampaign::query()
            ->with('creator:id,name,email')
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return response()->json($campaigns);
    }

    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'headline' => 'nullable|string|max:255',
            'preview_text' => 'nullable|string|max:255',
            'body' => 'required|string|min:10',
            'subscriber_ids' => 'nullable|array',
            'subscriber_ids.*' => 'integer|exists:newsletter_subscribers,id',
        ]);

        $query = NewsletterSubscriber::query();
        if (!empty($validated['subscriber_ids'])) {
            $query->whereIn('id', $validated['subscriber_ids']);
        }

        $subscribers = $query->orderBy('id')->get();

        if ($subscribers->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No subscribers found to send this newsletter.',
            ], 422);
        }

        $headline = $validated['headline'] ?: $validated['subject'];
        $previewText = $validated['preview_text'] ?: substr(strip_tags($validated['body']), 0, 150);

        $campaign = NewsletterCampaign::create([
            'subject' => $validated['subject'],
            'headline' => $headline,
            'preview_text' => $previewText,
            'body' => $validated['body'],
            'created_by' => $request->user()?->id,
        ]);

        $sent = 0;
        $failed = 0;
        $failedEmails = [];

        foreach ($subscribers as $subscriber) {
            $delivered = app(EmailDeliveryService::class)->sendOrLog(
                $subscriber->email,
                new NewsletterBroadcast(
                    emailSubject: $validated['subject'],
                    headline: $headline,
                    previewText: $previewText,
                    body: $validated['body'],
                    recipientName: $subscriber->name,
                ),
                $subscriber->name,
                true,
                'Newsletter',
                'Newsletter send failed'
            );

            if ($delivered) {
                $sent++;
            } else {
                $failed++;
                $failedEmails[] = $subscriber->email;
            }
        }

        $campaign->update([
            'sent_count' => $sent,
            'failed_count' => $failed,
            'sent_at' => now(),
        ]);

        return response()->json([
            'success' => $failed === 0,
            'message' => $failed === 0
                ? "Newsletter sent to {$sent} subscriber(s)."
                : "Newsletter sent to {$sent} subscriber(s). {$failed} failed.",
            'data' => [
                'campaign_id' => $campaign->id,
                'sent' => $sent,
                'failed' => $failed,
                'failed_emails' => $failedEmails,
            ],
        ], $failed === 0 ? 200 : 207);
    }
}
