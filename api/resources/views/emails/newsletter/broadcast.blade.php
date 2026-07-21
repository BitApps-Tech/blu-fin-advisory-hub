<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $headline }}</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
</head>
<body style="margin:0;padding:0;background-color:#f4f1ea;font-family:Georgia,'Times New Roman',serif;color:#1f2937;">
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;">
        {{ $previewText }}
    </div>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color:#f4f1ea;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:640px;background-color:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 12px 40px rgba(31,41,55,0.12);">
                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#1b4332 0%,#2d6a4f 55%,#40916c 100%);padding:36px 32px;text-align:center;">
                            @if(isset($message) && is_file($logoPath = resource_path('images/mamokacha-logo.png')))
                                <img src="{{ $message->embed($logoPath) }}" alt="MamoKacha" width="88" height="88" style="display:block;margin:0 auto 16px;border-radius:50%;border:3px solid rgba(255,255,255,0.35);">
                            @elseif(!empty($newsletterLogoCid))
                                <img src="cid:{{ $newsletterLogoCid }}" alt="MamoKacha" width="88" height="88" style="display:block;margin:0 auto 16px;border-radius:50%;border:3px solid rgba(255,255,255,0.35);">
                            @else
                                <div style="width:88px;height:88px;margin:0 auto 16px;border-radius:50%;background:rgba(255,255,255,0.15);border:3px solid rgba(255,255,255,0.35);line-height:88px;font-size:28px;color:#f5d78e;font-weight:bold;">
                                    MK
                                </div>
                            @endif
                            <p style="margin:0;font-size:28px;line-height:1.2;color:#ffffff;letter-spacing:0.04em;">MamoKacha</p>
                            <p style="margin:8px 0 0;font-size:13px;line-height:1.5;color:rgba(255,255,255,0.82);font-family:Arial,Helvetica,sans-serif;">
                                {{ $tagline }}
                            </p>
                        </td>
                    </tr>

                    <!-- Gold accent -->
                    <tr>
                        <td style="height:4px;background:linear-gradient(90deg,#c9a227 0%,#f5d78e 50%,#c9a227 100%);font-size:0;line-height:0;">&nbsp;</td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:36px 32px 28px;">
                            @if(!empty($recipientName))
                                <p style="margin:0 0 18px;font-size:15px;line-height:1.6;color:#4b5563;font-family:Arial,Helvetica,sans-serif;">
                                    Hello {{ $recipientName }},
                                </p>
                            @endif

                            <h1 style="margin:0 0 20px;font-size:26px;line-height:1.3;color:#1b4332;font-weight:normal;">
                                {{ $headline }}
                            </h1>

                            <div style="font-size:16px;line-height:1.8;color:#374151;font-family:Arial,Helvetica,sans-serif;">
                                {!! $bodyHtml !!}
                            </div>
                        </td>
                    </tr>

                    <!-- CTA -->
                    <tr>
                        <td style="padding:0 32px 36px;text-align:center;">
                            <a href="{{ $websiteUrl }}" style="display:inline-block;padding:14px 28px;background-color:#1b4332;color:#ffffff;text-decoration:none;border-radius:999px;font-size:14px;letter-spacing:0.08em;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;">
                                Visit MamoKacha
                            </a>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:24px 32px;background-color:#f8f6f1;border-top:1px solid #e7e2d8;text-align:center;">
                            <p style="margin:0 0 8px;font-size:13px;line-height:1.6;color:#6b7280;font-family:Arial,Helvetica,sans-serif;">
                                You are receiving this email because you subscribed to updates from MamoKacha.
                            </p>
                            <p style="margin:0;font-size:12px;line-height:1.6;color:#9ca3af;font-family:Arial,Helvetica,sans-serif;">
                                &copy; {{ date('Y') }} MamoKacha. Ethiopian coffee heritage, crafted with care.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
