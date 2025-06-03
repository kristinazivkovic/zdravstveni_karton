<?php

namespace App\Http\Controllers;

use App\Models\ZdravstveniKarton;
use App\Models\Pacijent;
use Illuminate\Http\Request;

class ZdravstveniKartonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isDoktor() && !auth()->user()->isAdmin()) {
                return response()->json(['message' => 'Nedozvoljen pristup'], 403);
            }
            return $next($request);
        })->except(['index', 'show']);
    }

    /**
     * Prikaz svih zdravstvenih kartona
     */
    public function index()
    {
        $kartoni = ZdravstveniKarton::with(['pacijent', 'lekar'])->get();
        return response()->json($kartoni);
    }

    /**
     * Kreiranje novog zdravstvenog kartona
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pacijent_id' => 'required|exists:pacijenti,id',
            'visina' => 'required|numeric|min:0',
            'tezina' => 'required|numeric|min:0',
            'krvni_pritisak' => 'required|string',
            'dijagnoza' => 'required|string',
            'tretman' => 'required|string',
        ]);

        $validated['user_id'] = auth()->id();

        $karton = ZdravstveniKarton::create($validated);

        return response()->json([
            'message' => 'Zdravstveni karton uspešno kreiran',
            'data' => $karton
        ], 201);
    }

    /**
     * Prikaz određenog zdravstvenog kartona
     */
    public function show(ZdravstveniKarton $karton)
    {
        return response()->json($karton->load(['pacijent', 'lekar']));
    }

    /**
     * Ažuriranje zdravstvenog kartona
     */
    public function update(Request $request, ZdravstveniKarton $karton)
    {
        $validated = $request->validate([
            'pacijent_id' => 'sometimes|required|exists:pacijenti,id',
            'visina' => 'sometimes|required|numeric|min:0',
            'tezina' => 'sometimes|required|numeric|min:0',
            'krvni_pritisak' => 'sometimes|required|string',
            'dijagnoza' => 'sometimes|required|string',
            'tretman' => 'sometimes|required|string',
        ]);

        $karton->update($validated);

        return response()->json([
            'message' => 'Zdravstveni karton uspešno ažuriran',
            'data' => $karton
        ]);
    }

    /**
     * Brisanje zdravstvenog kartona
     */
    public function destroy(ZdravstveniKarton $karton)
    {
        $karton->delete();

        return response()->json([
            'message' => 'Zdravstveni karton uspešno obrisan'
        ], 200);
    }
}