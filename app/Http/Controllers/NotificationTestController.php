<?php
namespace App\Http\Controllers;
use App\Models\StockSignal;
use App\Models\User;
use App\Notifications\StockSignalNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class NotificationTestController extends Controller
{ /** * Test notification untuk seluruh user. * * Logic: * * 1. Ambil signal terbaru untuk setiap saham. * 2. Ambil seluruh user dengan role "user". * 3. Ambil saham yang dipilih masing-masing user dari user_stocks. * 4. Hanya kirim signal jika saham tersebut dipilih oleh user. * 5. Jangan kirim signal yang sama jika user sudah pernah * menerima notification untuk StockSignal tersebut. * * Endpoint: * * GET /notification-test */
    public function send(Request $request): JsonResponse
    { /* |-------------------------------------------------------------------------- | 1. Ambil signal terbaru untuk setiap saham |-------------------------------------------------------------------------- | | Satu saham bisa mempunyai banyak StockSignal. | | Contoh: | | BBCA: | - signal ID 10 | - signal ID 20 | - signal ID 30 | | Yang digunakan untuk testing hanya signal terbaru, | yaitu ID 30. | */
        $signals = StockSignal::with('stock')->whereIn('id', StockSignal::query()->selectRaw('MAX(id)')->groupBy('stock_code'))->orderBy('stock_code')->get();
        if ($signals->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Belum ada StockSignal di database.',], 404);
        } /* |-------------------------------------------------------------------------- | 2. Ambil seluruh user |-------------------------------------------------------------------------- */
        $users = User::role('user')->with('stocks')->get();
        if ($users->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Belum ada user dengan role "user".',], 404);
        } /* |-------------------------------------------------------------------------- | 3. Statistik |-------------------------------------------------------------------------- */
        $totalUsers = $users->count();
        $totalSignals = $signals->count();
        $totalSent = 0;
        $totalSkipped = 0;
        $results = []; /* |-------------------------------------------------------------------------- | 4. Loop setiap user |-------------------------------------------------------------------------- */
        foreach ($users as $user) { /* |-------------------------------------------------------------------------- | Stock yang dipilih user |-------------------------------------------------------------------------- */
            $selectedStockCodes = $user->stocks->pluck('stock_code')->map(fn($code) => strtoupper($code))->toArray(); /* |-------------------------------------------------------------------------- | Hasil testing untuk user |-------------------------------------------------------------------------- */
            $userResult = ['user_id' => $user->id, 'user_name' => $user->name, 'selected_stocks' => $selectedStockCodes, 'notifications_sent' => [], 'notifications_skipped' => [],]; /* |-------------------------------------------------------------------------- | 5. Cek setiap signal |-------------------------------------------------------------------------- */
            foreach ($signals as $signal) {
                $stockCode = strtoupper($signal->stock_code); /* |-------------------------------------------------------------------------- | User tidak memilih saham ini |-------------------------------------------------------------------------- */
                if (!in_array($stockCode, $selectedStockCodes, true)) {
                    continue;
                } /* |-------------------------------------------------------------------------- | 6. Cek apakah notification untuk signal ini | sudah pernah diterima user |-------------------------------------------------------------------------- | | Data notification Laravel disimpan di: | | notifications | | Kita mencari StockSignal ID di kolom JSON "data". | */
                $alreadySent = $user->notifications()->where('type', StockSignalNotification::class)->whereJsonContains('data->stock_signal_id', $signal->id)->exists(); /* |-------------------------------------------------------------------------- | Signal sudah pernah dikirim |-------------------------------------------------------------------------- */
                if ($alreadySent) {
                    $totalSkipped++;
                    $userResult['notifications_skipped'][] = ['signal_id' => $signal->id, 'stock_code' => $signal->stock_code, 'stock_name' => $signal->stock?->stock_name, 'signal' => $signal->signal, 'signal_strength' => $signal->signal_strength, 'reason' => 'Notification untuk signal ini sudah pernah dikirim.',];
                    continue;
                } /* |-------------------------------------------------------------------------- | 7. Kirim notification |-------------------------------------------------------------------------- */
                $user->notify(new StockSignalNotification($signal)); /* |-------------------------------------------------------------------------- | 8. Catat hasil |-------------------------------------------------------------------------- */
                $totalSent++;
                $userResult['notifications_sent'][] = ['signal_id' => $signal->id, 'stock_code' => $signal->stock_code, 'stock_name' => $signal->stock?->stock_name, 'signal' => $signal->signal, 'signal_strength' => $signal->signal_strength,];
            } /* |-------------------------------------------------------------------------- | Simpan hasil user |-------------------------------------------------------------------------- */
            $results[] = $userResult;
        } /* |-------------------------------------------------------------------------- | 9. Response |-------------------------------------------------------------------------- */
        return response()->json(['success' => true, 'message' => 'Test notification selesai.', 'summary' => ['total_users' => $totalUsers, 'latest_signals_per_stock' => $totalSignals, 'notifications_sent' => $totalSent, 'notifications_skipped_already_sent' => $totalSkipped,], 'results' => $results,]);
    }
}