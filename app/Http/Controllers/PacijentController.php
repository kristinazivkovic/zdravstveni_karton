<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PacijentResource;
use App\Models\Pacijent;
use App\Models\ZdravstveniKarton;

class PacijentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $pacijenti = Pacijent::with(['user', 'zdravstveniKarton'])->get();
            return PacijentResource::collection($pacijenti);
            } elseif ($user->isDoktor()) {
            $pacijenti = Pacijent::with(['user', 'zdravstveniKarton'])
            ->whereHas('zdravstveniKarton', function($query) use ($user) {
            $query->where('user_id', $user->id); // ← koristi user_id ako je lekar
            })->get();

            return PacijentResource::collection($pacijenti);
         } else {
            return response()->json(['message' => 'Nedozvoljen pristup'], 403);
    }
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->isDoktor() && !$user->isAdmin()) {
            return response()->json(['message' => 'Samo doktori i admin mogu kreirati pacijente'], 403);
        }

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

        if ($user->isDoktor()) {
            $validated['user_id'] = $user->id;
        }

        $pacijent = Pacijent::create($validated);

        // Ako je lekar kreirao pacijenta, kreiraj i zdravstveni karton
        if ($user->isDoktor()) {
            ZdravstveniKarton::create([
                'pacijent_id' => $pacijent->id,
                'lekar_id' => $user->id
            ]);
        }

        return response()->json($pacijent, 201);
    }

    public function show(Pacijent $pacijent)
    {
        $user = auth()->user();
    
        if ($user->isAdmin()) {
            return new PacijentResource($pacijent);
        }
    
        if ($user->isDoktor()) {
            if (!$pacijent->zdravstveniKarton || $pacijent->zdravstveniKarton->user_id !== $user->id) {
                return response()->json(['message' => 'Nedozvoljen pristup'], 403);
            }
            return new PacijentResource($pacijent);
        }
    
        if ($user->isPacijent() && $pacijent->user_id === $user->id) {
            return new PacijentResource($pacijent);
        }
    
        return response()->json(['message' => 'Nedozvoljen pristup'], 403);
    }

    public function update(Request $request, Pacijent $pacijent)
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            // Admin može ažurirati bilo kog pacijenta
        } elseif ($user->isDoktor()) {
            // Lekar može ažurirati samo svoje pacijente
            if (!$pacijent->zdravstveniKarton || $pacijent->zdravstveniKarton->lekar_id !== $user->id) {
                return response()->json(['message' => 'Nedozvoljen pristup'], 403);
            }
        } else {
            return response()->json(['message' => 'Nedozvoljen pristup'], 403);
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
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            // Admin može obrisati bilo kog pacijenta
        } elseif ($user->isDoktor()) {
            // Lekar može obrisati samo svoje pacijente
            if (!$pacijent->zdravstveniKarton || $pacijent->zdravstveniKarton->lekar_id !== $user->id) {
                return response()->json(['message' => 'Nedozvoljen pristup'], 403);
            }
        } else {
            return response()->json(['message' => 'Nedozvoljen pristup'], 403);
        }

        $pacijent->delete();

        return response()->json([
            'message' => 'Pacijent uspešno obrisan'
        ], 200);
    }
}