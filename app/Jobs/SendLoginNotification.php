<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Exception;

use App\Notifications\UserLogin;
use Illuminate\Support\Facades\Log;

class SendLoginNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    protected $user;

    protected $type;

    protected $opsi;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($type = 'web', $user, $opsi = [])
    {
        $this->user = $user;
        $this->type = $type;
        $this->opsi = $opsi;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user->notify(new UserLogin(
            $this->type,
            $this->user,
            $this->opsi
        ));
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        Log::info('[FAILED] Dispatched User Login : '.$this->user->name);
    }
}