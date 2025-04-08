<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTimesheetRequest;
use App\Http\Resources\TimesheetResource;
use App\Models\Timesheet;
use App\Service\TimesheetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Timesheets",
 *     description="Gestión de registros de horas"
 * )
 */
class TimesheetController extends Controller
{
    protected TimesheetService $timesheetService;

    public function __construct(TimesheetService $timesheetService)
    {
        $this->timesheetService = $timesheetService;
    }

    /**
     * @OA\Get(
     *     path="/api/timesheets",
     *     summary="Listar todos los registros de horas del usuario autenticado",
     *     tags={"Timesheets"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="type", in="query", required=false, @OA\Schema(type="string"), example="work"),
     *     @OA\Parameter(name="from", in="query", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="to", in="query", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Response(response=200, description="Lista de timesheets", @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Timesheet"))),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $type = $request->get('type', 'work');
        $from = $request->get('from');
        $to = $request->get('to');

        $timesheets = $this->timesheetService->getPaginatedTimesheets($user, $type, 10, $from, $to);

        return TimesheetResource::collection($timesheets);
    }

    /**
     * @OA\Post(
     *     path="/api/timesheets",
     *     summary="Crear un nuevo registro de horas",
     *     tags={"Timesheets"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Timesheet")
     *     ),
     *     @OA\Response(response=201, description="Registro creado", @OA\JsonContent(ref="#/components/schemas/Timesheet")),
     *     @OA\Response(response=422, description="Error de validación"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function store(StoreTimesheetRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $timesheet = Timesheet::create($data);

        return new TimesheetResource($timesheet);
    }

    /**
     * @OA\Get(
     *     path="/api/timesheets/total",
     *     summary="Obtener total de minutos por tipo",
     *     tags={"Timesheets"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="type", in="query", required=false, @OA\Schema(type="string"), example="work"),
     *     @OA\Parameter(name="from", in="query", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="to", in="query", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Response(
     *         response=200,
     *         description="Total de minutos",
     *         @OA\JsonContent(
     *             @OA\Property(property="type", type="string"),
     *             @OA\Property(property="from", type="string", format="date"),
     *             @OA\Property(property="to", type="string", format="date"),
     *             @OA\Property(property="total_minutes", type="integer"),
     *             @OA\Property(property="total_time", type="string", example="8h 30min")
     *         )
     *     ),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function total(Request $request)
    {
        $user = $request->user();
        $type = $request->get('type', 'work');
        $from = $request->get('from');
        $to = $request->get('to');

        $minutes = $this->timesheetService->getTotalMinutesByType($user, $type, $from, $to);
        $formatted = $this->timesheetService->getFormattedTotalTime($minutes);

        return response()->json([
            'type' => $type,
            'from' => $from,
            'to' => $to,
            'total_minutes' => $minutes,
            'total_time' => $formatted,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/timesheets/{id}",
     *     summary="Mostrar un registro específico",
     *     tags={"Timesheets"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Registro encontrado", @OA\JsonContent(ref="#/components/schemas/Timesheet")),
     *     @OA\Response(response=404, description="No encontrado"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function show(string $id)
    {
        $timesheet = Timesheet::findOrFail($id);

        return new TimesheetResource($timesheet);
    }

    /**
     * @OA\Put(
     *     path="/api/timesheets/{id}",
     *     summary="Actualizar un registro existente",
     *     tags={"Timesheets"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/Timesheet")),
     *     @OA\Response(response=200, description="Actualizado con éxito", @OA\JsonContent(ref="#/components/schemas/Timesheet")),
     *     @OA\Response(response=404, description="No encontrado"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function update(Request $request, string $id)
    {
        $timesheet = Timesheet::findOrFail($id);
        $timesheet->update($request->all());

        return new TimesheetResource($timesheet);
    }

    /**
     * @OA\Delete(
     *     path="/api/timesheets/{id}",
     *     summary="Eliminar un registro",
     *     tags={"Timesheets"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Eliminado correctamente"),
     *     @OA\Response(response=404, description="No encontrado"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function destroy(string $id)
    {
        $timesheet = Timesheet::findOrFail($id);
        $timesheet->delete();

        return response()->json(null, 204);
    }
}
