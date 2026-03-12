<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        // Mengambil data log beserta relasi user (aktor) dan booking terkait
        $logs = Log::with(['user', 'booking.user', 'booking.room'])
                   ->orderBy('created_at', 'desc')
                   ->get();
                   
        return view('admin.logs.index', compact('logs'));
    }
}