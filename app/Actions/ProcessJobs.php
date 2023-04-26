<?php

namespace App\Actions;

use App\Models\User;
use App\Notifications\CronJobNotification;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;

class ProcessJobs
{
    public static function handle($jobs, string $busName)
    {
        Bus::batch($jobs)->name($busName)
            ->finally(function (Batch $batch) {
                self::notifyAdmins('Cron Job ' . $batch->name . 'executed successfully');
            })
            ->catch(function (Batch $batch, Throwable $e) {
                self::notifyAdmins('Error on Cron Job ' . $batch->name, $e->getMessage());
            })->dispatch();
    }

    private static function notifyAdmins(string $message, string $erroMessage = ''): void
    {
        User::admin()
            ->each(
                fn ($user) => $user->notify(
                    new CronJobNotification($message, $erroMessage)
                )
            );
    }
}
