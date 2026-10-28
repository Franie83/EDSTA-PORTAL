<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/health',      [ApiController::class, 'health']);
Route::get('/attractions', [ApiController::class, 'attractions']);
Route::get('/guides',      [ApiController::class, 'guides']);
Route::get('/hotels',      [ApiController::class, 'hotels']);
Route::get('/events',      [ApiController::class, 'events']);
Route::get('/search',      [ApiController::class, 'search']);
Route::get('/stats',       [ApiController::class, 'stats']);

// Protected trigger for GitHub Actions. Requires a Bearer secret.
Route::post('/internal/permit-expiry-reminders/run', function (\Illuminate\Http\Request $request) {
    $expected = (string) config('app.permit_reminder_secret');
    $provided = (string) $request->bearerToken();

    if ($expected === '' || $provided === '' || !hash_equals($expected, $provided)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    try {
        $simulatedDate = trim((string) $request->input('date', ''));

if ($simulatedDate !== '') {
    try {
        $parsedDate = \Illuminate\Support\Carbon::createFromFormat('!Y-m-d', $simulatedDate);

        if (!$parsedDate || $parsedDate->format('Y-m-d') !== $simulatedDate) {
            return response()->json(['error' => 'Invalid date. Use YYYY-MM-DD.'], 422);
        }
    } catch (\Throwable $e) {
        return response()->json(['error' => 'Invalid date. Use YYYY-MM-DD.'], 422);
    }
}

$arguments = $simulatedDate !== '' ? ['--date' => $simulatedDate] : [];

$exitCode = \Illuminate\Support\Facades\Artisan::call(
    'permits:send-expiry-reminders',
    $arguments
);
        $output = trim(\Illuminate\Support\Facades\Artisan::output());

        if ($exitCode !== 0) {
            \Illuminate\Support\Facades\Log::error('Permit reminder trigger failed.', [
                'exit_code' => $exitCode,
                'output' => $output,
            ]);

            return response()->json(['status' => 'failed'], 500);
        }

        return response()->json([
            'status' => 'success',
            'simulated_today' => $simulatedDate !== '' ? $simulatedDate : null,
            'output' => $output,
        ]);
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::error('Permit reminder trigger exception.', [
            'exception' => get_class($e),
        ]);

        return response()->json(['status' => 'failed'], 500);
    }
})->middleware('throttle:5,1');
