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
    }

    public function index()
    {
        $user = auth()->user();
        
        if ($user->isDoktor()) {
            // Doktor vidi sve preglede koje je izvršio
            $pregledi = Pregled::with(['karton', 'lekar'])
                ->where('lekar_id', $user->id)
                ->get();
        } elseif ($user->isPacijent()) {
            // Pacijent vidi samo svoje preglede
            $karton = ZdravstveniKarton::where('user_id', $user->id)->first();
            
            if (!$karton) {
                return response()->json(['message' => 'Zdravstveni karton nije pronađen'], 404);
            }
            
            $pregledi = Pregled::with(['karton', 'lekar'])
                ->where('karton_id', $karton->id)
                ->get();
        } else {
            // Admin ili drugi korisnici nemaju pristup
            return response()->json(['message' => 'Nedozvoljen pristup'], 403);
        }

        return response()->json($pregledi);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->isDoktor()) {
            return response()->json(['message' => 'Samo doktori mogu kreirati preglede'], 403);
        }

        $validated = $request->validate([
            'karton_id' => 'required|exists:zdravstveni_kartoni,id',
            'opis' => 'required|string',
            'datum' => 'required|date',
            'tip_pregleda' => 'required|string|max:255'
        ]);

        $validated['lekar_id'] = $user->id;

        $pregled = Pregled::create($validated);

        return response()->json($pregled, 201);
    }

    public function show(Pregled $pregled)
    {
        $user = auth()->user();
        
        if ($user->isDoktor()) {
            // Doktor može videti pregled samo ako je on njegov
            if ($pregled->lekar_id !== $user->id) {
                return response()->json(['message' => 'Nemate pristup ovom pregledu'], 403);
            }
        } elseif ($user->isPacijent()) {
            // Pacijent može videti pregled samo ako pripada njegovom kartonu
            $karton = ZdravstveniKarton::where('user_id', $user->id)->first();
            
            if (!$karton || $pregled->karton_id !== $karton->id) {
                return response()->json(['message' => 'Nemate pristup ovom pregledu'], 403);
            }
        } else {
            return response()->json(['message' => 'Nedozvoljen pristup'], 403);
        }

        return response()->json($pregled->load(['karton', 'lekar']));
    }

    public function update(Request $request, Pregled $pregled)
    {
        $user = auth()->user();
        
        if (!$user->isDoktor() || $pregled->lekar_id !== $user->id) {
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
        $user = auth()->user();
        
        if (!$user->isDoktor() || $pregled->lekar_id !== $user->id) {
            return response()->json(['message' => 'Nemate dozvolu da obrišete ovaj pregled.'], 403);
        }
        
        $pregled->delete();

        return response()->json(['message' => 'Pregled obrisan']);
    }
}