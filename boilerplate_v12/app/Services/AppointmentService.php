<?php

namespace App\Services;

use App\Services\Contracts\AppointmentServiceInterface;
use App\Repositories\Contracts\AppointmentRepositoryInterface;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

    public function store(Request|array $data)
    {
        if ($data instanceof Request) {
            $data = $data->all();
        } elseif (is_array($data)) {
            $data = $data;
        } else {
            throw new \InvalidArgumentException('O argumento deve ser uma instância de Request ou um array.');
        }

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

    public function update(Request|array $data, int $id)
    {
        if ($data instanceof Request) {
            $data = $data->all();
        } elseif (is_array($data)) {
            $data = $data;
        } else {
            throw new \InvalidArgumentException('O argumento deve ser uma instância de Request ou um array.');
        }

        $requestedDateTime = $data['datetime'] ?? null;

        $appointment = $this->appointmentRepository->find($id);
        if (!$appointment) {
            return response()->json(['message' => 'Agendamento não encontrado.'], 404);
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
        $bookedDatesQuery = $this->appointmentRepository->all()->where('id', '!=', $appointment->id);
        $bookedDates = $bookedDatesQuery->pluck('datetime')->toArray();

        if (in_array($requestedDateTime, $bookedDates)) {
            return response()->json(['message' => 'Novo horário já reservado.'], 409);
        }

        return $this->appointmentRepository->update($data, $appointment->id);
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
        $startHourClinic = 10; // Horário de abertura da clínica
        $endHourClinic = 18;   // Horário de fechamento da clínica

        // --- Para o dia atual ---
        $now = Carbon::now();
        // Buffer de 1 hora: agendamentos devem ser daqui a pelo menos 1 hora e no próximo bloco de 30 ou 60 minutos
        $nextAvailableTime = $now->copy()->addHour();
        // Arredonda para a próxima hora cheia se necessário ou considera blocos de 30 min se preferir
        if ($nextAvailableTime->minute > 0 && $nextAvailableTime->minute <= 30) {
            $nextAvailableTime->minute(30)->second(0);
        } else if ($nextAvailableTime->minute > 30) {
             $nextAvailableTime->addHour()->minute(0)->second(0);
        } else {
            $nextAvailableTime->minute(0)->second(0);
        } 

        // Garante que o horário de início do dia atual não é antes do horário de abertura da clínica
        $currentDayStartHour = max($startHourClinic, $nextAvailableTime->hour);

        for ($hour = $currentDayStartHour; $hour <= $endHourClinic; $hour++) {
            $slotTime = Carbon::parse($now->format('Y-m-d') . ' ' . str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00:00');
            // Adiciona o slot apenas se for depois do nosso buffer de 1 hora do tempo atual
            if ($slotTime->greaterThanOrEqualTo($nextAvailableTime)) {
                $slots[] = $slotTime->format('Y-m-d H:i:s');
            }
        }

        // --- Para os próximos 3 dias ---
        for ($i = 1; $i <= 3; $i++) {
            $date = Carbon::now()->addDays($i)->format('Y-m-d');

            for ($hour = $startHourClinic; $hour <= $endHourClinic; $hour++) {
                $slots[] = "$date " . str_pad($hour, 2, '0', STR_PAD_LEFT) . ":00:00";
            }
        }
    
        return $slots;
    }
} 