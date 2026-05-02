<?php

namespace Tests\Feature;

use App\Models\DeviceToken;
use App\Models\SaleTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Kreait\Firebase\Contract\Messaging;
use Tests\TestCase;

class TransactionsUpdateStatusWithInvalidFirebaseCredentialsTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_status_succeeds_even_when_firebase_credentials_json_is_invalid(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $marketing = User::factory()->create(['role' => 'marketing']);

        $transaction = SaleTransaction::factory()->create([
            'user_id' => $marketing->id,
            'status' => 'process',
            'amount' => 10000,
            'commission_rate' => 10,
            'commission_amount' => 0,
        ]);

        DeviceToken::create([
            'user_id' => $marketing->id,
            'token' => 'test-token',
            'token_hash' => hash('sha256', 'test-token'),
            'platform' => 'android',
        ]);

        $invalidJsonPath = sys_get_temp_dir().DIRECTORY_SEPARATOR.'invalid-firebase-service-account.json';
        file_put_contents($invalidJsonPath, "{\n  \"auth_uri\": \"https://accounts.google.com/o/oauth2\n  ///auth\"\n}\n");

        putenv("FIREBASE_CREDENTIALS={$invalidJsonPath}");
        $_ENV['FIREBASE_CREDENTIALS'] = $invalidJsonPath;
        $_SERVER['FIREBASE_CREDENTIALS'] = $invalidJsonPath;

        $this->app->forgetInstance(Messaging::class);

        $this->actingAs($admin);

        $this->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->put(route('transactions.updateStatus', ['transaction' => $transaction->id]), [
                'status' => 'success',
            ])
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Status transaksi berhasil diperbarui.',
                'new_status' => 'success',
                'fcm_sent' => false,
            ])
            ->assertJsonPath('fcm_error', fn ($value) => is_null($value) || (is_string($value) && $value !== ''));

        $this->assertSame('success', $transaction->fresh()->status);
    }
}
