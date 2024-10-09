<?php

namespace App\Listeners;

use App\Enums\VaccineStatus;
use App\Events\UserRegistered;
use App\Models\User;
use App\Models\VaccineCenter;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;

/** @codeCoverageIgnore */
class AllocateVaccinationDate implements ShouldQueue
{
    /**
     * The number of times the queued listener may be attempted.
     */
    public int $tries = 3;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        $user = $event->user;

        try {
            $user->scheduled_date = $this->findNextAvailableDateFor($user->vaccineCenter);
            $user->status = VaccineStatus::Scheduled->value;
            $user->save();

            Cache::forget("user_status_{$user->nid}");

        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * @throws \Exception
     */
    public function findNextAvailableDateFor(VaccineCenter $center): Carbon
    {
        $currentDate = now()->copy()->addDay();

        while ($currentDate) {
            if ($this->isWorkingDay($currentDate)) {
                $scheduledCount = User::where('vaccine_center_id', $center->id)
                    ->where('scheduled_date', $currentDate->format('Y-m-d'))
                    ->count();

                if ($scheduledCount < $center->daily_capacity) {
                    return $currentDate;
                }
            }

            $currentDate->addDay();
        }

        throw new \RuntimeException('No available date found');
    }

    private function isWorkingDay(Carbon $currentDate): bool
    {
        return ! $currentDate->isFriday() && ! $currentDate->isSaturday();
    }
}
