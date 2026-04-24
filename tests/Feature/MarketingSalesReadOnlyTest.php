<?php

namespace Tests\Feature;

use App\Models\SaleTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class MarketingSalesReadOnlyTest extends TestCase
{
    use RefreshDatabase;

    public function test_marketing_can_only_view_details_for_in_scope_transactions(): void
    {
        $marketingA = User::factory()->create(['role' => 'marketing']);
        $marketingB = User::factory()->create(['role' => 'marketing']);

        $userA = User::factory()->create(['role' => 'user', 'marketing_owner_id' => $marketingA->id]);
        $userB = User::factory()->create(['role' => 'user', 'marketing_owner_id' => $marketingB->id]);

        $inScope = SaleTransaction::factory()->create([
            'user_id' => $userA->id,
            'status' => 'success',
            'customer_name' => 'IN_SCOPE',
            'created_at' => Carbon::parse('2026-04-13 10:00:00'),
        ]);

        $outScope = SaleTransaction::factory()->create([
            'user_id' => $userB->id,
            'status' => 'success',
            'customer_name' => 'OUT_SCOPE',
            'created_at' => Carbon::parse('2026-04-13 11:00:00'),
        ]);

        $this->actingAs($marketingA);

        $index = $this->get('/marketing/sales');
        $index->assertOk();
        $index->assertSee('IN_SCOPE');
        $index->assertDontSee('OUT_SCOPE');

        $showInScope = $this->get("/marketing/sales/{$inScope->id}");
        $showInScope->assertOk();

        $showOutScope = $this->get("/marketing/sales/{$outScope->id}");
        $showOutScope->assertForbidden();

        $this->get('/marketing/sales/create')->assertNotFound();
        $this->get("/marketing/sales/{$inScope->id}/edit")->assertNotFound();
        $this->post("/marketing/sales/{$inScope->id}/approve")->assertNotFound();
        $this->post("/marketing/sales/{$inScope->id}/reject")->assertNotFound();
        $this->post("/marketing/sales/{$inScope->id}/process")->assertNotFound();
    }
}

