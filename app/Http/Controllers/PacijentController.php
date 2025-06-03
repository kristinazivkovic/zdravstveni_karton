<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pacijent;


class PacijentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Pacijent::all());
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ime' => 'required',
            'prezime' => 'required',
            'jmbg' => 'required|unique:pacijenti,jmbg',
            'datum_rodjenja' => 'required|date',
            'pol' => 'required',
            'email' => 'required|email',
            'telefon' => 'required',
            'istorija_pacijenta' => 'required',
        ]);
        $validated['user_id'] = auth()->id(); // ako je lekar ulogovan

        $pacijent = Pacijent::create($validated);
        return response()->json($pacijent, 201);
    }

    public function show(Pacijent $pacijent)
    {
        return response()->json($pacijent);
    }

    

    public function update(Request $request, Pacijent $pacijent)
    {
        // Provera da li je korisnik doktor (može i preko middleware)
        if (!auth()->user()->isDoktor()) {
            return response()->json([
                'message' => 'Samo doktori mogu ažurirati podatke pacijenata'
            ], 403);
        }
    
        $validated = $request->validate([
            'ime' => 'sometimes|required',
            'prezime' => 'sometimes|required',
            'datum_rodjenja' => 'sometimes|required|date',
            'pol' => 'sometimes|required',
            'email' => 'sometimes|required|email|unique:pacijenti,email,'.$pacijent->id,
            'telefon' => 'sometimes|required',
            'istorija_pacijenta' => 'sometimes|required',
        ]);
    
        $pacijent->update($validated);
    
        return response()->json([
            'message' => 'Pacijent uspešno ažuriran',
            'data' => $pacijent
        ]);
    }

    public function destroy(Pacijent $pacijent)
    {
        // Provera da li je korisnik doktor
        if (!auth()->user()->isDoktor()) {
            return response()->json([
                'message' => 'Samo doktori mogu brisati pacijente'
            ], 403);
        }
    
        $pacijent->delete();
    
        return response()->json([
            'message' => 'Pacijent uspešno obrisan'
        ], 200); // 200 ili 204 (No Content)
    }

    public function __construct()
    {
        $this->middleware('auth:sanctum'); // Provera autentikacije
        
        // Možete koristiti policy ili gate umesto inline provere
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isDoktor()) {
                return response()->json(['message' => 'Nedozvoljen pristup'], 403);
            }
            return $next($request);
        })->except(['index', 'show']); // Primena samo za write operacije
    }
}
