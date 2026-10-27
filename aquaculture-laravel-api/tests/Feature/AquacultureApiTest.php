<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AquacultureApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_healthcheck_returns_ok(): void
    {
        $response = $this->getJson('/api/health');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'ok',
                'service' => 'NagroSmart Aquaculture API',
            ]);
    }

    public function test_user_can_login_via_api(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@aquafarm.test',
            'password' => 'password',
            'device_name' => 'Automated Test Suite',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email'],
                    'token',
                    'token_type',
                ],
            ]);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@aquafarm.test',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_authenticated_user_can_get_profile(): void
    {
        $user = User::where('email', 'admin@aquafarm.test')->first();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/auth/me');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'email' => 'admin@aquafarm.test',
                ],
            ]);
    }

    public function test_can_list_ponds(): void
    {
        $user = User::first();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/ponds');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonCount(5, 'data');
    }

    public function test_can_create_new_pond(): void
    {
        $user = User::first();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/ponds', [
            'name' => 'Kolam Eksperimen D1',
            'code' => 'PL-D1',
            'pond_type' => 'hdpe',
            'depth_m' => 1.4,
            'area_sqm' => 800,
            'status' => 'preparation',
            'location' => 'Blok Selatan',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'code' => 'PL-D1',
                ],
            ]);

        $this->assertDatabaseHas('ponds', ['code' => 'PL-D1']);
    }

    public function test_can_get_cycle_summary(): void
    {
        $user = User::first();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/cycles/1/summary');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'cycle',
                    'analytics' => [
                        'current_doc',
                        'total_feed_consumed_kg',
                        'total_feeding_sessions',
                    ],
                ],
            ]);
    }

    public function test_can_fetch_latest_water_quality_for_active_ponds(): void
    {
        $user = User::first();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/water-quality-logs/latest');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'pond_id',
                        'pond_name',
                        'pond_code',
                        'latest_quality' => [
                            'parameters' => [
                                'temperature_celsius',
                                'ph',
                                'dissolved_oxygen_mgl',
                                'salinity_ppt',
                            ],
                            'condition_status',
                        ],
                    ],
                ],
            ]);
    }
}
