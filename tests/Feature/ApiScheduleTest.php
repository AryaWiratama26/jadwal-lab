<?php

namespace Tests\Feature;

use App\Models\ApiKey;
use App\Models\Assistant;
use App\Models\Course;
use App\Models\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiScheduleTest extends TestCase
{
    use RefreshDatabase;

    private string $key;

    protected function setUp(): void
    {
        parent::setUp();

        [$plaintext] = ApiKey::generate('Test Suite');
        $this->key = $plaintext;

        $course = Course::create(['name' => 'Data Mining', 'code' => 'DM', 'color' => '#6366F1']);
        Schedule::create([
            'course_id' => $course->id,
            'angkatan' => '2023',
            'program' => 'Reguler',
            'kelas' => 'TI.23.A.1',
            'hari' => 'Senin',
            'waktu_mulai' => '08.00',
            'waktu_selesai' => '10.00',
            'dosen' => 'Dosen Tes',
            'ruangan' => 'Lab 1',
        ]);
    }

    private function authHeaders(): array
    {
        return ['X-API-KEY' => $this->key, 'Accept' => 'application/json'];
    }

    public function test_rejects_request_without_api_key(): void
    {
        $this->getJson('/api/v1/schedules')->assertUnauthorized();
    }

    public function test_rejects_request_with_invalid_api_key(): void
    {
        $this->getJson('/api/v1/schedules', ['X-API-KEY' => 'sk_salah'])
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated. Invalid or revoked API key.');
    }

    public function test_health_returns_ok(): void
    {
        $this->getJson('/api/v1/health', $this->authHeaders())
            ->assertOk()
            ->assertJsonPath('message', 'OK');
    }

    public function test_lists_schedules_with_relations_and_filters(): void
    {
        $this->getJson('/api/v1/schedules?hari=Senin', $this->authHeaders())
            ->assertOk()
            ->assertJsonStructure(['data' => [['id', 'kelas', 'waktu', 'course', 'assistants', 'assistant_ids']], 'meta'])
            ->assertJsonCount(1, 'data');

        $this->getJson('/api/v1/schedules?hari=Jumat', $this->authHeaders())
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_full_schedule_crud_cycle(): void
    {
        $course = Course::first();
        $assistant = Assistant::create(['name' => 'Asisten Tes', 'nim' => '99.99.999']);

        // Create
        $create = $this->postJson('/api/v1/schedules', [
            'course_id' => $course->id,
            'angkatan' => '2025',
            'program' => 'Ekstensi',
            'kelas' => 'TI.25.E.1',
            'hari' => 'Sabtu',
            'waktu_mulai' => '13.00',
            'waktu_selesai' => '15.00',
            'dosen' => 'Dosen Baru',
            'assistant_ids' => [$assistant->id],
        ], $this->authHeaders())->assertCreated();

        $id = $create->json('data.id');
        $this->assertTrue($create->json('data.is_active'));
        $this->assertEquals([$assistant->id], $create->json('data.assistant_ids'));

        // Show
        $this->getJson("/api/v1/schedules/{$id}", $this->authHeaders())->assertOk();

        // Update
        $this->putJson("/api/v1/schedules/{$id}", ['ruangan' => 'Lab 9'], $this->authHeaders())
            ->assertOk()
            ->assertJsonPath('data.ruangan', 'Lab 9');

        // Validation error is JSON
        $this->postJson('/api/v1/schedules', [], $this->authHeaders())
            ->assertStatus(422)
            ->assertJsonStructure(['message', 'errors']);

        // Delete
        $this->deleteJson("/api/v1/schedules/{$id}", [], $this->authHeaders())->assertOk();
        $this->assertDatabaseMissing('schedules', ['id' => $id]);
    }

    public function test_course_delete_guarded_when_has_schedules(): void
    {
        $course = Course::first();

        $this->deleteJson("/api/v1/courses/{$course->id}", [], $this->authHeaders())
            ->assertStatus(422);
    }

    public function test_revoked_key_is_rejected(): void
    {
        ApiKey::first()->revoke();

        $this->getJson('/api/v1/schedules', $this->authHeaders())->assertUnauthorized();
    }
}
