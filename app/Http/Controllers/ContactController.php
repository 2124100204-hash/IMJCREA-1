<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contacto;         

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'asunto'  => 'nullable|string|max:100',
            'message' => 'required|string|max:2000',
        ]);

        Contacto::create($request->only('name', 'email', 'asunto', 'message'));

        return redirect()->route('contact')
                         ->with('success', '¡Mensaje enviado! Te responderemos pronto.');
    }
}