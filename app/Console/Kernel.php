<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [

    ];

    protected function getUrlMagma()
    {
        return config('app.magma_old_url');
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $this->scheduleGunungApi($schedule);
        $this->scheduleGempaBumi($schedule);
        $this->scheduleGerakanTanah($schedule);
        $this->scheduleAdministrasi($schedule);
    }

    /**
     * Scheduler untuk Import Data Gunung Api, meliputi:
     * 
     * Data Rekomendasi
     * Data VAR
     * Data VAR Visual
     * Data VAR Klimatologi
     * Data VAR Kegempaan
     * Data Informasi Letusan (VEN)
     * Data VONA
     * 
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function scheduleGunungApi(Schedule $schedule)
    {
        $filePath = storage_path('logs/scheduler-import-var-'.now()->format('Y-m-d').'.log');

        // Crontab reference https://crontab.guru/
        $schedule->command('import:rekomendasi')
            ->cron('0 */12 * * *')
            ->pingBefore($this->getUrlMagma());

        $schedule->command('import:var')
            ->cron('0 2,8,14,20 * * *')
            ->pingBefore($this->getUrlMagma())
            ->withoutOverlapping()
            ->appendOutputTo($filePath);

        $schedule->command('import:visual')
            ->cron('0 2,8,14,20 * * *')
            ->pingBefore($this->getUrlMagma())
            ->appendOutputTo($filePath);

        $schedule->command('import:klimatologi')
            ->cron('0 2,8,14,20 * * *')
            ->pingBefore($this->getUrlMagma())
            ->appendOutputTo($filePath);
    
        $schedule->command('import:gempa')
            ->cron('0 2,8,14,20 * * *')
            ->pingBefore($this->getUrlMagma())
            ->appendOutputTo($filePath);

        $schedule->command('import:ven')
            ->daily()
            ->pingBefore($this->getUrlMagma())
            ->appendOutputTo($filePath);

        $schedule->command('import:vona')
            ->daily()
            ->pingBefore($this->getUrlMagma())
            ->appendOutputTo($filePath);
    }

    /**
     * Scheduler untuk Import Kegempaan (MAGMA-ROQ), meliputi:
     * 
     * Data BMKG
     * Data MAGMA-ROQ
     * 
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function scheduleGempaBumi(Schedule $schedule)
    {
        $filePath = storage_path('logs/scheduler-import-roq-'.now()->format('Y-m-d').'.log');

        $schedule->command('gempa:bmkg')
            ->everyTenMinutes()
            ->pingBefore('http://data.bmkg.go.id/')
            ->withoutOverlapping();

        $schedule->command('import:roq')
            ->daily()
            ->pingBefore($this->getUrlMagma())
            ->appendOutputTo($filePath);
    }

    /**
     * Scheduler untuk Import Data Gerakan Tanah (Sigertan), meliputi:
     * 
     * Data CRS
     * Data MAGMA-Sigertan
     * 
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function scheduleGerakanTanah(Schedule $schedule)
    {
        $filePath = storage_path('logs/scheduler-import-sigertan-'.now()->format('Y-m-d').'.log');

        $schedule->command('import:crs')
            ->daily()
            ->pingBefore($this->getUrlMagma())
            ->withoutOverlapping()
            ->appendOutputTo($filePath);

        $schedule->command('import:sigertan')
            ->daily()
            ->pingBefore($this->getUrlMagma())
            ->appendOutputTo($filePath);
    }

    /**
     * Scheduler untuk Import Data Administrasi, meliputi:
     * 
     * Data Absensi Pegawai
     * Data Pengajuan
     * 
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function scheduleAdministrasi(Schedule $schedule)
    {
        $filePath = storage_path('logs/scheduler-import-administrasi-'.now()->format('Y-m-d').'.log');

        $schedule->command('import:absensi')
            ->daily()
            ->pingBefore($this->getUrlMagma())
            ->appendOutputTo($filePath);

        $schedule->command('import:pengajuan')
            ->daily()
            ->pingBefore($this->getUrlMagma())
            ->appendOutputTo($filePath);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
