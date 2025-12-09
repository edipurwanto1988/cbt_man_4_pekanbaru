<?php
namespace App\Http\Controllers;

use App\Models\PretestHasil;
use App\Models\PosttestHasil;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function history()
    {
        $nisn = Auth::guard('siswa')->user()->nisn;

        // Ambil semua riwayat pretest
        $pretestHistory = PretestHasil::with('bankSoal')
            ->where('nisn', $nisn)
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil semua riwayat posttest
        $posttestHistory = PosttestHasil::with('bankSoal')
            ->where('nisn', $nisn)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('participant.history.index', [
            'pretestHistory' => $pretestHistory,
            'posttestHistory' => $posttestHistory,
        ]);
    }
}
