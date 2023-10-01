<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class FillKatalogDanKategoriPeralatan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:katalog-dan-kategori-peralatan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengisi daftar katalog dan kategori peralatan';

    /**
     * Data katalog dan kategori
     *
     * @var Collection
     */
    protected $katalogDanKategoriPeralatan;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->katalogDanKategoriPeralatan = $this->katalogDanKategoriPeralatan();
    }

    protected function katalogDanKategoriPeralatan(): Collection
    {
        $json = storage_path() . '/json/peralatan-katalog-dan-kategori.json';
        return collect(json_decode(file_get_contents($json)));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info($this->description);

        $this->katalogDanKategoriPeralatan->each(function ($alat) {
            $kategori = KategoriPeralatan::firstOrCreate([
                'slug_kategori' => Str::slug($alat['kategori']),
            ],[
                'kategori' => $alat['kategori'],
            ]);

            $katalog = KatalogBarang::firstOrCreate([
                'slug_nama_barang' => Str::slug($alat['nama_barang']),
            ],[
                'nama_barang' => $alat['nama_barang'],
                'brand' => $alat['brand'],
                'model' => $alat['model'],
                'harga' => is_null($alat['harga']) ? null : str_replace(',','', $alat['harga']),
                'satuan' => $alat['satuan'],
                'spesifikasi' => $alat['spesifikasi'],
                'url' => $alat['url'],
                'nip' => ,
            ]);
        });

        $this->info('Selesai');
    }
}
