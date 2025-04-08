<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Timesheet",
 *     required={"date", "hours", "type"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=2),
 *     @OA\Property(property="date", type="string", format="date", example="2024-04-07"),
 *     @OA\Property(property="hours", type="number", format="float", example=8),
 *     @OA\Property(property="type", type="string", example="work"),
 *     @OA\Property(property="description", type="string", example="Desarrollo de nuevas funcionalidades")
 * )
 */
class TimesheetSchema {}
