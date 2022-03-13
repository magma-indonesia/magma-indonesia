<?php

namespace App\Http\Controllers;

use App\Migration;
use Illuminate\Http\Request;

class MigrationController extends Controller
{
    public function index()
    {
        $migrations = Migration::all();

        return view('migration.index', [
            'migrations' => $migrations,
        ]);
    }

    public function destroy(Migration $migration)
    {
        return $migration->delete() ? [
            'message' => $migration->migration . ' berhasil dihapus',
            'success' => true
        ] : [
            'message' => $migration->migration . ' gagal dihapus',
            'success' => false
        ];
    }
}
