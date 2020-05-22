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

    protected function getUrlWinston()
    {
        return config('app.winston_host');
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $this->scheduleLiveSeismogram($schedule);
        $this->scheduleCompileMagmaVar($schedule);
        $this->scheduleGunungApi($schedule);
        $this->scheduleGerakanTanah($schedule);
        $this->scheduleAdministrasi($schedule);
        $this->scheduleGempaBumi($schedule);
    }


    /**
     * Scheduler untuk mengkompilasi laporan MAGMA-VAR 24 jam:
     * 
     * Data VAR
     * 
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function scheduleCompileMagmaVar(Schedule $schedule)
    {
        $filePath = storage_path('logs/scheduler-compile-var-'.now()->format('Y-m-d').'.log');

        $schedule->command('compile:var')
            ->cron('15,30 0,1,2,3,4,5 * * *')
            ->pingBefore($this->getUrlMagma())
            ->appendOutputTo($filePath);
    }

    /**
     * Scheduler untuk Import Data Gunung Api, meliputi:
     * 
     * Data Rekomendasi
     * Data VAR
     * Data VAR Visual
     * Data VAR Klimatologi
     * Data VAR Kegempaan
     * Data VAR Harian
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
            ->cron('0 1,7,13,19 * * *')
            ->pingBefore($this->getUrlMagma())
            ->withoutOverlapping();

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

        $schedule->command('import:vardaily')
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
            ->pingBefore('https://data.bmkg.go.id/')
            ->withoutOverlapping(3);

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
     * Scheduler untuk Update data Live Seismogram per 10 menit:
     * 
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function scheduleLiveSeismogram(Schedule $schedule)
    {
        $filePath = storage_path('logs/scheduler-live-seismogram'.now()->format('Y-m-d').'.log');

        $schedule->command('update:live_seismogram')
            ->cron('*/10 * * * *')
            ->pingBefore($this->getUrlWinston())
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
        $this->load(__DIR__.'/Commands/v1');

        require base_path('routes/console.php');
    }
}
