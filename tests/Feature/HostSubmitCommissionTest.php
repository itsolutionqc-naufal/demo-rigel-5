<?php

namespace Tests\Feature;

use App\Models\Commission;
use App\Models\SaleTransaction;
use App\Models\Service;
use App\Models\HostSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HostSubmitCommissionTest extends TestCase
{
    use RefreshDatabase;

    private function setHunterCommissionAmount(int $amount): void
    {
        putenv("HUNTER_COMMISSION_AMOUNT={$amount}");
        $_ENV['HUNTER_COMMISSION_AMOUNT'] = (string) $amount;
        $_SERVER['HUNTER_COMMISSION_AMOUNT'] = (string) $amount;
    }

    public function test_commission_follows_service_minimum_nominal_when_admin_approves_host_submit_transaction(): void
    {
        $hunter = User::factory()->create([
            'role' => User::ROLE_USER,
        ]);

        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $service = Service::query()->create([
            'name' => 'Test Service',
            'category' => 'talent_hunter',
            'minimum_nominal' => 7000,
        ]);

        $saleTransaction = SaleTransaction::query()->create([
            'transaction_code' => 'HUNTER-TEST-001',
            'user_id' => $hunter->id,
            'status' => 'process',
            'transaction_type' => 'host_submit',
            'service_name' => $service->name,
            'amount' => 0,
            'commission_rate' => 0,
            'commission_amount' => 0,
        ]);

        HostSubmission::query()->create([
            'sale_transaction_id' => $saleTransaction->id,
            'service_id' => $service->id,
            'host_id' => 'HOST-001',
            'nickname' => 'Host Test',
            'whatsapp_number' => '6281234567890',
            'form_filled' => true,
        ]);

        $this->actingAs($admin);

        $this->post(route('sales.approve', $saleTransaction))
            ->assertRedirect();

        $commission = Commission::query()
            ->where('user_id', $hunter->id)
            ->where('sale_transaction_id', $saleTransaction->id)
            ->first();

        $this->assertNotNull($commission);
        $this->assertSame(7000.0, (float) $commission->amount);

        $saleTransaction->refresh();
        $this->assertSame('success', $saleTransaction->status);
        $this->assertSame(7000.0, (float) $saleTransaction->commission_amount);
    }

    public function test_commission_uses_env_fallback_when_service_cannot_be_mapped(): void
    {
        $this->setHunterCommissionAmount(1234);

        $hunter = User::factory()->create([
            'role' => User::ROLE_USER,
        ]);

        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $saleTransaction = SaleTransaction::query()->create([
            'transaction_code' => 'HUNTER-TEST-002',
            'user_id' => $hunter->id,
            'status' => 'process',
            'transaction_type' => 'host_submit',
            'service_name' => 'Test Service',
            'amount' => 0,
            'commission_rate' => 0,
            'commission_amount' => 0,
        ]);

        $this->actingAs($admin);

        $this->post(route('sales.approve', $saleTransaction))
            ->assertRedirect();

        $commission = Commission::query()
            ->where('user_id', $hunter->id)
            ->where('sale_transaction_id', $saleTransaction->id)
            ->first();

        $this->assertNotNull($commission);
        $this->assertSame(1234.0, (float) $commission->amount);

        $saleTransaction->refresh();
        $this->assertSame(1234.0, (float) $saleTransaction->commission_amount);
    }

    public function test_commission_is_removed_when_admin_moves_host_submit_back_from_success(): void
    {
        $hunter = User::factory()->create([
            'role' => User::ROLE_USER,
        ]);

        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $service = Service::query()->create([
            'name' => 'Test Service',
            'category' => 'talent_hunter',
            'minimum_nominal' => 7000,
        ]);

        $saleTransaction = SaleTransaction::query()->create([
            'transaction_code' => 'HUNTER-TEST-002',
            'user_id' => $hunter->id,
            'status' => 'process',
            'transaction_type' => 'host_submit',
            'service_name' => $service->name,
            'amount' => 0,
            'commission_rate' => 0,
            'commission_amount' => 0,
        ]);

        HostSubmission::query()->create([
            'sale_transaction_id' => $saleTransaction->id,
            'service_id' => $service->id,
            'host_id' => 'HOST-001',
            'nickname' => 'Host Test',
            'whatsapp_number' => '6281234567890',
            'form_filled' => true,
        ]);

        $this->actingAs($admin);

        $this->post(route('sales.approve', $saleTransaction))
            ->assertRedirect();

        $this->assertDatabaseHas('commissions', [
            'user_id' => $hunter->id,
            'sale_transaction_id' => $saleTransaction->id,
        ]);

        $this->post(route('sales.process', $saleTransaction))
            ->assertRedirect();

        $this->assertDatabaseMissing('commissions', [
            'user_id' => $hunter->id,
            'sale_transaction_id' => $saleTransaction->id,
        ]);
    }
}
