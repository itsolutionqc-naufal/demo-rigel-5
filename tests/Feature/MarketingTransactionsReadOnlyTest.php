<?php

namespace Tests\Feature;

use App\Models\SaleTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketingTransactionsReadOnlyTest extends TestCase
{
    use RefreshDatabase;

    public function test_marketing_transactions_are_read_only_and_scoped_to_owned_users(): void
    {
        $marketingA = User::factory()->create(['role' => 'marketing']);
        $marketingB = User::factory()->create(['role' => 'marketing']);

        $userA = User::factory()->create(['role' => 'user', 'marketing_owner_id' => $marketingA->id]);
        $userB = User::factory()->create(['role' => 'user', 'marketing_owner_id' => $marketingB->id]);

        $inScope = SaleTransaction::factory()->create([
            'user_id' => $userA->id,
            'status' => 'success',
            'customer_name' => 'IN_SCOPE',
        ]);

        $outScope = SaleTransaction::factory()->create([
            'user_id' => $userB->id,
            'status' => 'success',
            'customer_name' => 'OUT_SCOPE',
        ]);

        $this->actingAs($marketingA);

        $index = $this->get('/marketing/transactions');
        $index->assertOk();
        $index->assertSee('IN_SCOPE');
        $index->assertDontSee('OUT_SCOPE');
        $index->assertDontSee('Tambah Transaksi');
        $index->assertDontSee('openStatusModal');
        $index->assertViewHas('metrics', function (array $metrics) {
            return ($metrics['total_transactions'] ?? null) === 1
                && ($metrics['success_transactions'] ?? null) === 1;
        });

        $this->post('/marketing/transactions')->assertStatus(405);
        $this->put("/marketing/transactions/{$inScope->id}/status")->assertNotFound();
        $this->delete("/marketing/transactions/{$inScope->id}")->assertStatus(405);

        $this->get("/marketing/transactions/{$inScope->id}")
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->get("/marketing/transactions/{$outScope->id}")
            ->assertForbidden();
    }
}
