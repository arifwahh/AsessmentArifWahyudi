<?php

namespace Tests\Feature;

use App\Models\Courier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourierTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@example.com',
            'phone' => '081234567890',
            'level' => 2,
            'vehicle_type' => 'motorcycle',
            'vehicle_plate' => 'B 1234 XYZ',
            'city' => 'Bandung',
            'address' => 'Jl. Merdeka No. 1',
            'is_active' => true,
        ], $overrides);
    }

    public function test_index_is_paginated_and_sorted_by_name_by_default(): void
    {
        foreach (range(1, 20) as $number) {
            Courier::factory()->create(['name' => 'Kurir '.str_pad((string) $number, 2, '0', STR_PAD_LEFT)]);
        }

        Courier::factory()->create(['name' => 'Ahmad Zaki']);

        $this->getJson('/api/couriers')
            ->assertOk()
            ->assertJsonPath('data.0.name', 'Ahmad Zaki')
            ->assertJsonPath('per_page', 15)
            ->assertJsonPath('total', 21)
            ->assertJsonCount(15, 'data');
    }

    public function test_index_can_be_sorted_by_registration_date(): void
    {
        $older = Courier::factory()->create(['name' => 'Older Courier']);
        $newer = Courier::factory()->create(['name' => 'Newer Courier']);

        $older->created_at = now()->subDay();
        $older->save();

        $this->getJson('/api/couriers?sort=-created_at')
            ->assertOk()
            ->assertJsonPath('data.0.id', $newer->id)
            ->assertJsonPath('data.1.id', $older->id);
    }

    public function test_index_can_search_by_every_word_in_the_name(): void
    {
        $target = Courier::factory()->create(['name' => 'Budiono Hadi Agung']);
        Courier::factory()->create(['name' => 'Budi Santoso']);
        Courier::factory()->create(['name' => 'Agung Prasetyo']);

        $response = $this->getJson('/api/couriers?search=budi+agung');

        $response->assertOk()->assertJsonCount(1, 'data');
        $this->assertSame([$target->id], $response->json('data.*.id'));
    }

    public function test_index_can_filter_by_multiple_levels(): void
    {
        $levelTwo = Courier::factory()->create(['level' => 2]);
        $levelThree = Courier::factory()->create(['level' => 3]);
        Courier::factory()->create(['level' => 1]);
        Courier::factory()->create(['level' => 5]);

        $response = $this->getJson('/api/couriers?level=2,3');

        $response->assertOk()->assertJsonCount(2, 'data');
        $this->assertEqualsCanonicalizing(
            [$levelTwo->id, $levelThree->id],
            $response->json('data.*.id'),
        );
    }

    public function test_index_respects_per_page_and_keeps_query_string_on_pagination_links(): void
    {
        Courier::factory()->count(3)->create(['level' => 2]);

        $response = $this->getJson('/api/couriers?level=2&per_page=2');

        $response->assertOk()
            ->assertJsonPath('per_page', 2)
            ->assertJsonPath('total', 3)
            ->assertJsonCount(2, 'data');

        $this->assertStringContainsString('level=2', $response->json('first_page_url'));
    }

    public function test_show_returns_all_courier_data(): void
    {
        $courier = Courier::factory()->create([
            'name' => 'Siti Aminah',
            'level' => 4,
            'vehicle_type' => 'van',
            'city' => 'Jakarta',
            'is_active' => false,
        ]);

        $this->getJson("/api/couriers/{$courier->id}")
            ->assertOk()
            ->assertJson([
                'id' => $courier->id,
                'name' => 'Siti Aminah',
                'email' => $courier->email,
                'phone' => $courier->phone,
                'level' => 4,
                'vehicle_type' => 'van',
                'vehicle_plate' => $courier->vehicle_plate,
                'city' => 'Jakarta',
                'is_active' => false,
            ]);
    }

    public function test_store_creates_a_courier(): void
    {
        $response = $this->postJson('/api/couriers', $this->payload());

        $response->assertCreated()->assertJsonPath('name', 'Budi Santoso');

        $this->assertDatabaseHas('couriers', [
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@example.com',
            'phone' => '081234567890',
            'level' => 2,
            'vehicle_type' => 'motorcycle',
            'vehicle_plate' => 'B 1234 XYZ',
            'is_active' => true,
        ]);
    }

    public function test_store_validates_required_fields_and_unique_values(): void
    {
        $this->postJson('/api/couriers', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'phone', 'level', 'vehicle_type', 'vehicle_plate']);

        $existing = Courier::factory()->create();

        $this->postJson('/api/couriers', $this->payload([
            'email' => $existing->email,
            'phone' => $existing->phone,
            'vehicle_plate' => $existing->vehicle_plate,
            'level' => 6,
            'vehicle_type' => 'plane',
        ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email', 'phone', 'vehicle_plate', 'level', 'vehicle_type']);
    }

    public function test_update_changes_the_courier_and_ignores_its_own_unique_values(): void
    {
        $courier = Courier::factory()->create();

        $response = $this->patchJson("/api/couriers/{$courier->id}", [
            'name' => 'Budi Updated',
            'level' => 5,
            'email' => $courier->email,
            'phone' => $courier->phone,
            'vehicle_plate' => $courier->vehicle_plate,
        ]);

        $response->assertOk()->assertJsonPath('name', 'Budi Updated');

        $this->assertDatabaseHas('couriers', [
            'id' => $courier->id,
            'name' => 'Budi Updated',
            'level' => 5,
        ]);
    }

    public function test_update_validates_input_and_unique_values_from_other_couriers(): void
    {
        $courier = Courier::factory()->create();
        $other = Courier::factory()->create();

        $this->patchJson("/api/couriers/{$courier->id}", [
            'email' => $other->email,
            'level' => 6,
            'vehicle_type' => 'plane',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email', 'level', 'vehicle_type']);
    }

    public function test_destroy_deletes_the_courier(): void
    {
        $courier = Courier::factory()->create();

        $this->deleteJson("/api/couriers/{$courier->id}")->assertNoContent();

        $this->assertDatabaseMissing('couriers', ['id' => $courier->id]);
    }

    public function test_missing_courier_returns_not_found(): void
    {
        $this->getJson('/api/couriers/999999')->assertNotFound();
        $this->deleteJson('/api/couriers/999999')->assertNotFound();
    }
}
