<?php

namespace App\Http\Controllers;

use App\Models\TherapySession;
use App\Models\SpecialistSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function day(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $currentDate = Carbon::parse($date);
        
        // Получаем начало и конец недели
        $startOfWeek = $currentDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $currentDate->copy()->endOfWeek(Carbon::SUNDAY);
        
        // Получаем занятия на всю неделю
        $query = TherapySession::whereBetween('start_time', [$startOfWeek, $endOfWeek])
            ->with(['child', 'specialist'])
            ->orderBy('start_time');
        
        // Фильтруем по organization_id
        if (auth()->user()->isOrganization() || auth()->user()->isSpecialist()) {
            $query->where('organization_id', auth()->user()->organization_id);
        } elseif (auth()->user()->isParent()) {
            $query->whereHas('child', function($q) {
                $q->where('parent_id', auth()->id());
            });
        }
        
        $sessions = $query->get();
        
        // Получаем расписание работы специалиста из work_calendar
        $workCalendar = null;
        if (auth()->user()->isSpecialist() && auth()->user()->specialistProfile) {
            $workCalendar = auth()->user()->specialistProfile->work_calendar;
        }
        
        return view('calendar.day', compact('currentDate', 'sessions', 'workCalendar'));
    }
    
    public function singleDay(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $currentDate = Carbon::parse($date);
        
        // Получаем занятия на выбранный день
        $query = TherapySession::whereDate('start_time', $currentDate)
            ->with(['child', 'specialist'])
            ->orderBy('start_time');
        
        // Фильтруем по organization_id
        if (auth()->user()->isOrganization() || auth()->user()->isSpecialist()) {
            $query->where('organization_id', auth()->user()->organization_id);
        } elseif (auth()->user()->isParent()) {
            $query->whereHas('child', function($q) {
                $q->where('parent_id', auth()->id());
            });
        }
        
        $sessions = $query->get();
        
        // Получаем расписание работы для конкретного дня из work_calendar
        $workSchedule = null;
        if (auth()->user()->isSpecialist() && auth()->user()->specialistProfile) {
            $workCalendar = auth()->user()->specialistProfile->work_calendar;
            $dateKey = $currentDate->format('Y-m-d');
            
            if ($workCalendar && isset($workCalendar['workingDays'][$dateKey])) {
                $workSchedule = $workCalendar['workingDays'][$dateKey];
            }
        }
        
        return view('calendar.single-day', compact('currentDate', 'sessions', 'workSchedule'));
    }
}
