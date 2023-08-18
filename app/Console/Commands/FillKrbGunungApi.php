<?php

namespace App\Console\Commands;

use App\DataDasar;
use App\KrbGunungApi;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class FillKrbGunungApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:krb-gunung-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengisi KRB Gunung Api';

    /**
     * Pendahuluan KRB
     *
     * @var Collection
     */
    protected $pendahuluans;

    /**
     * Penjelasan KRB
     *
     * @var Collection
     */
    protected $penjelasans;

    /**
     * Potensi
     *
     * @var Collection
     */
    protected $potensis;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->pendahuluans = $this->pendahuluans();
        $this->penjelasans = $this->penjelasans();
        $this->potensis = $this->potensis();
    }

    protected function pendahuluans(): Collection
    {
        $json = storage_path() . '/json/krb-pendahuluan.json';
        return collect(json_decode(file_get_contents($json)));
    }

    protected function penjelasans(): Collection
    {
        $json = storage_path() . '/json/krb-penjelasan.json';
        return collect(json_decode(file_get_contents($json)));
    }

    protected function potensis(): Collection
    {
        $json = storage_path() . '/json/krb-potensi.json';
        return collect(json_decode(file_get_contents($json)));
    }

    protected function krbCode(string $code, string $year): string
    {
        return "$code-$year";
    }

    protected function mapIndexToArray(string $code, string $indices): array
    {
        return collect(explode(';', $indices))->transform(function ($number) use ($code) {
            return [
                'code' => $code,
                'nomor_lembar_peta' => $number
            ];
        })->toArray();
    }

    protected function penjelasanToArray(string $code, Collection $penjelasans): array
    {
        return $penjelasans->transform(function ($penjelasan) use ($code) {

            $area = collect($this->potensis['area'])
                ->where('code', $code)->where('krb', $penjelasan->krb)->first();

            $radius = collect($this->potensis['radius'])
                ->where('code', $code)->where('krb', $penjelasan->krb)->first();

            return [
                'code' => $code,
                'zona_krb' => $penjelasan->krb,
                'indonesia' => $penjelasan->id,
                'english' => $penjelasan->en ?? null,
                'nip' => '198803152015031005',
                'area_id' => $area->id ?? null,
                'area_en' => $area->en ?? null,
                'radius' => $radius->radius ?? null,
                'radius_id' => $radius->id ?? null,
                'radius_en' => $radius->en ?? null,
            ];
        })->toarray();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->pendahuluans->each(function ($pendahuluan) {
            $code = $pendahuluan->code;

            $penjelasans = $this->penjelasans->where('code', $code)->values();

            $krb = KrbGunungApi::firstOrCreate([
                'krb_code' => $this->krbCode($code, $pendahuluan->year)
            ],[
                'code' => $code,
                'nip' => '198803152015031005',
                'indonesia' => $pendahuluan->id,
                'english' => $pendahuluan->en ?? null,
                'created_by' => $pendahuluan->by,
                'year_published' => $pendahuluan->year,
                'scale' => $pendahuluan->scale,
                'mapped_by' => $pendahuluan->mapped_by ?? null,
                'year_mapped' => $pendahuluan->year_mapped ?? null,
                'revised_by' => $pendahuluan->revised_by ?? null,
                'year_revision' => $pendahuluan->year_revision ?? null,
                'text_by' => $pendahuluan->text_by ?? null,
                'reviewed_by' => $pendahuluan->reviewed_by ?? null,
                'manuscript_by' => $pendahuluan->manuscript_by ?? null,
                'digitized_by' => $pendahuluan->digitized_by ?? null,
                'nip' => '198803152015031005',
                'is_active' => 1,
            ]);

            if (isset($pendahuluan->map_index)) {
                $krb->indexMaps()->createMany($this->mapIndexToArray(
                    $code, $pendahuluan->map_index));
            }

            $krb->penjelasans()->createMany($this->penjelasanToArray(
                $code, $penjelasans
            ));
        });
    }
}
