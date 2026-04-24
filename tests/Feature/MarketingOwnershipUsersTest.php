<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketingOwnershipUsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_marketing_only_sees_and_manages_their_owned_users(): void
    {
        $marketingA = User::factory()->create(['role' => 'marketing']);
        $marketingB = User::factory()->create(['role' => 'marketing']);

        $this->actingAs($marketingA);

        $this->post(route('marketing.users.store'), [
            'name' => 'Owned User',
            'email' => 'owned@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin',
        ])->assertRedirect(route('marketing.users.index'));

        $ownedUser = User::query()->where('email', 'owned@example.com')->firstOrFail();
        $this->assertSame(User::ROLE_USER, $ownedUser->role);
        $this->assertSame($marketingA->id, $ownedUser->marketing_owner_id);

        $this->get(route('marketing.users.index'))
            ->assertOk()
            ->assertSee('owned@example.com');

        $this->actingAs($marketingB);

        $this->get(route('marketing.users.index'))
            ->assertOk()
            ->assertDontSee('owned@example.com');

        $this->get(route('marketing.users.show', $ownedUser))->assertNotFound();
        $this->get(route('marketing.users.edit', $ownedUser))->assertNotFound();
    }
}

