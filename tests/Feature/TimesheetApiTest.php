<?php

use App\Models\Timesheet;
use App\Models\User;
use Illuminate\Support\Carbon;
use function Pest\Laravel\{actingAs, getJson, postJson};

it('returns a list of timesheets for authenticated user', function () {
    $user = User::factory()->create();
    Timesheet::factory()->count(5)->create(['user_id' => $user->id]);

    actingAs($user);

    getJson('/api/timesheets')
        ->assertOk()
        ->assertJsonStructure(['data']);
});

it('stores a new timesheet record', function () {
    $user = User::factory()->create();

    actingAs($user);

    $payload = [
        'type' => 'work',
        'day_in' => now()->subHours(9)->toISOString(),
        'day_out' => now()->toISOString(),
    ];

    postJson('/api/timesheets', $payload)
        ->assertCreated()
        ->assertJsonStructure(['data' => ['id', 'type', 'start', 'end', 'duration']]);
});

it('returns total work minutes for user', function () {
    $user = User::factory()->create();

    Timesheet::factory()->create([
        'user_id' => $user->id,
        'type' => 'work',
        'day_in' => Carbon::now()->subHours(4),
        'day_out' => Carbon::now(),
    ]);

    actingAs($user);

    getJson('/api/timesheets/total')
        ->assertOk()
        ->assertJsonStructure(['total_minutes', 'total_time']);
});
