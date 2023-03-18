<?php

namespace App\Jobs;

use App\v1\MagmaVarOptimize;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

class DownloadImageFromCendana implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $var;

    public function getFileExtension(): string
    {
        return collect(explode('.', $this->var->var_image))->last();
    }

    public function imagePath(): string
    {
        return "var/{$this->var->ga_code}/{$this->var->ga_code}{$this->var->var_noticenumber}.{$this->getFileExtension()}";
    }

    public function downloadImage(): string
    {
        $path = $this->imagePath();

        if (Storage::disk('public')->put($path, file_get_contents($this->var->var_image)))
            return asset("storage/$path");

        return $this->var->var_image;
    }

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(MagmaVarOptimize $var)
    {
        $this->var = $var;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->var->update([
            'var_image' => $this->downloadImage()
        ]);
    }
}
