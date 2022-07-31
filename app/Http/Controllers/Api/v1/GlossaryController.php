<?php

namespace App\Http\Controllers\Api\v1;

use App\Glossary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class GlossaryController extends Controller
{
    public function index()
    {
        $glossaries = Cache::remember('glossaries', 60, function () {
            return Glossary::with('glossary_files')->whereIsPublished(1)->orderBy('judul')->get();
        });

        $glossaries =
        Glossary::with('glossary_files')->whereIsPublished(1)->orderBy('judul')->get();

        // return $glossaries;

        return $glossaries->transform(function ($glossary) {
                return [
                    'judul' => $glossary->judul,
                    'slug' => $glossary->slug,
                    'deskripsi' => $glossary->deskripsi,
                    'files' => $glossary->glossary_files->transform(function ($file) {
                        return [
                            'thumbnail' => $file->thumbnail,
                            'image_url' => $file->url,
                        ];
                    }),
                ];
        });
    }
}
