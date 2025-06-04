<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pregled;
use App\Models\ZdravstveniKarton;

class PregledController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isDoktor()) {
                return response()->json(['message' => 'Nedozvoljen pristup'], 403);
            }
            return $next($request);
        });
    }

    public function index()
    {
        return response()->json(Pregled::with(['karton', 'lekar'])->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'karton_id' => 'required|exists:zdravstveni_kartoni,id',
            'opis' => 'required|string',
            'datum' => 'required|date',
            'tip_pregleda' => 'required|string|max:255'
        ]);

        $validated['lekar_id'] = auth()->id();

        $pregled = Pregled::create($validated);

        return response()->json($pregled, 201);
    }

    public function show(Pregled $pregled)
    {
        return response()->json($pregled->load(['karton', 'lekar']));
    }

    public function update(Request $request, Pregled $pregled)
    {
        if ($pregled->lekar_id !== auth()->id()) {
            return response()->json(['message' => 'Nemate pravo da menjate ovaj pregled'], 403);
        }

        $validated = $request->validate([
            'opis' => 'sometimes|required|string',
            'datum' => 'sometimes|required|date',
            'tip_pregleda' => 'sometimes|required|string|max:255'
        ]);

        $pregled->update($validated);

        return response()->json($pregled);
    }

    public function destroy(Pregled $pregled)
    {
        if ($pregled->lekar_id !== auth()->id()) {
            return response()->json(['message' => 'Nemate dozvolu da obrišete ovaj pregled.'], 403);
        }
        $pregled->delete();

        return response()->json(['message' => 'Pregled obrisan']);
    }
}
