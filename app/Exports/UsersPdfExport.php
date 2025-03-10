<?php

namespace App\Exports;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class UsersPdfExport
{
    public function download()
    {
        $users = User::all();
        
        $pdf = PDF::loadView('exports.users-pdf', [
            'users' => $users
        ]);
        
        return $pdf->download('users.pdf');
    }
} 