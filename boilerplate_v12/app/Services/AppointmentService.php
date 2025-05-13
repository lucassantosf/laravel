<?php

namespace App\Services;

use App\Services\Contracts\AppointmentServiceInterface;
use App\Repositories\Contracts\AppointmentRepositoryInterface;
use Illuminate\Http\Request;

class AppointmentService implements AppointmentServiceInterface
{
    protected $appointmentRepository;

    public function __construct(AppointmentRepositoryInterface $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    public function index()
    {
        return $this->getAvailableSlots();
    }

    public function search(string $search)
    {
        $resource = $this->appointmentRepository->search($search);
        return $resource;
    }

    public function store(array $data)
    {
        $requestedDateTime = $data['datetime'] ?? null;
    
        if (!$requestedDateTime) {
            return response()->json(['message' => 'Horário não informado.'], 400);
        }
        // Gera todos os horários disponíveis
        $availabilities = $this->generateNextAvailabities();
    
        // Verifica se o horário enviado na request é um horário permitido
        if (!in_array($requestedDateTime, $availabilities)) {
            return response()->json(['message' => 'Horário inválido.'], 422);
        }
    
        // Pega todos os horários já agendados
        $appointments = $this->appointmentRepository->all()->getCollection()->toArray();
        $bookedDates = array_column($appointments, 'datetime');
    
        // Verifica se já foi reservado
        if (in_array($requestedDateTime, $bookedDates)) {
            return response()->json(['message' => 'Horário já reservado.'], 409);
        }
    
        return $this->appointmentRepository->create($data);
    }

    public function update(array $data)
    {
        $name = $data['name'] ?? null;
        $document = $data['document'] ?? null;
        $requestedDateTime = $data['datetime'] ?? null;

        if (!$name && !$document) {
            return response()->json(['message' => 'Nome ou documento do agendamento a ser alterado não informado.'], 400);
        }

        $search = $document ? $document : $name;

        $resource = $this->appointmentRepository->search($search);

        if (!$resource) {
            return response()->json(['message' => 'Agendamento não encontrado com os dados fornecidos.'], 404);
        }

        $requestedDateTime = $data['datetime'] ?? null;
        if (!$requestedDateTime) {
            return response()->json(['message' => 'Novo horário não informado.'], 400);
        }

        $availabilities = $this->generateNextAvailabities();
        if (!in_array($requestedDateTime, $availabilities)) {
            return response()->json(['message' => 'Novo horário inválido.'], 422);
        }

        // Pega todos os horários já agendados (excluindo o atual que estamos editando)
        $bookedDatesQuery = $this->appointmentRepository->all()->where('id', '!=', $resource->id);
        $bookedDates = $bookedDatesQuery->get()->pluck('datetime')->toArray();

        if (in_array($requestedDateTime, $bookedDates)) {
            return response()->json(['message' => 'Novo horário já reservado.'], 409);
        }

        return $this->appointmentRepository->update($data, $resource->id);
    }

    public function cancel(int $id)
    {
        $appointment = $this->appointmentRepository->find($id);
        if (!$appointment) {
            return response()->json(['message' => 'Agendamento não encontrado.'], 404);
        }
        return $this->appointmentRepository->destroy($id);
    }

    public function rules(): array
    {
        return [
            'document' => 'required',
            'name' => 'required',
            'datetime' => 'required|date_format:Y-m-d H:i:s',
        ];
    }

    public function rules_update(int $id): array
    {
        return [
            'datetime' => 'date_format:Y-m-d H:i:s',
        ];
    }

    /**
     * Retorna os horários disponíveis para agendamento baseado nos horários já agendados
     * e nos horários fixos de 10h as 18h
     */
    private function getAvailableSlots(): array
    {
        $appointments = $this->appointmentRepository->all()->getCollection()->toArray();
        $bookedDates = array_column($appointments, 'datetime');
        $availabilities = $this->generateNextAvailabities();

        return array_values(array_filter($availabilities, function ($slot) use ($bookedDates) {
            return !in_array($slot, $bookedDates);
        }));
    }

    /**
     * Gerar horários disponíveis para os próximos 3 dias fixos
     */
    private function generateNextAvailabities()
    {
        $slots = [];
        $startHour = 10;
        $endHour = 18;
    
        for ($i = 1; $i <= 3; $i++) {
            $date = date('Y-m-d', strtotime("+$i days"));
    
            for ($hour = $startHour; $hour <= $endHour; $hour++) {
                $slots[] = "$date " . str_pad($hour, 2, '0', STR_PAD_LEFT) . ":00:00";
            }
        }
    
        return $slots;
    }
}
 