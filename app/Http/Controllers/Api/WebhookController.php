<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function midtransCallback(Request $request)
    {
        try {
            // Validasi signature Midtrans
            $this->validateMidtransSignature($request);

            // Proses pemberitahuan
            $data = $request->all();
            // Lakukan tindakan yang diperlukan berdasarkan pemberitahuan, misalnya, update status transaksi di database.

            Log::info('Midtrans Webhook Received', $data);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    private function validateMidtransSignature(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $signatureKey = $request->header('Signature');
        $body = $request->getContent();

        $expectedSignature = hash('sha512', $body . $serverKey);

        if ($signatureKey !== $expectedSignature) {
            throw new \Exception('Invalid Midtrans Signature');
        }
    }
}
