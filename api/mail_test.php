try {
    \Illuminate\Support\Facades\Mail::raw('MamoKacha SMTP test at ' . now(), function ($m) {
        $m->to('test@example.com')->subject('MamoKacha SMTP Test');
    });
    echo "SENT_OK\n";
} catch (\Throwable $e) {
    echo "SEND_FAILED: " . $e->getMessage() . "\n";
}
