<?php

namespace App\Http\Controllers;

use App\Models\Atendimento;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MainController extends Controller
{
    public function home(): View
    {

        $user = Auth::user();

        if ($user->role === 'admin') {

            $atendimentos = Atendimento::all();
        } else {

            $atendimentos = Atendimento::with('categoria')
                ->where('user_id', $user->id)
                ->get();
        }

        return view('home', [
            'atendimentos' => $atendimentos,
            'user' => $user
        ]);
    }
}
