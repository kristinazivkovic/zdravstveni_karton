<?php

namespace App\Http\Controllers;

use App\Http\Resources\ZdravstveniKartonResource;
use App\Models\ZdravstveniKarton;
use App\Models\Pacijent;
use Illuminate\Http\Request;

class ZdravstveniKartonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Prikaz svih zdravstvenih kartona
     */
    public function index(Request $request)
    {
        $user = auth()->user();
    
        $query = ZdravstveniKarton::with(['pacijent', 'lekar']);
    
        // Filter po jmbg pacijenta
        if ($request->filled('jmbg')) {
            $jmbg = $request->jmbg;
            $query->whereHas('pacijent', function ($q) use ($jmbg) {
                $q->where('jmbg', 'like', "%$jmbg%");
            });
        }
    
        // Filter po lekar_id
        if ($request->filled('lekar_id')) {
            $query->where('lekar_id', $request->lekar_id);
        }
    
        // Filter po imenu lekara
        if ($request->filled('lekar_ime')) {
            $ime = $request->lekar_ime;
            $query->whereHas('lekar', function ($q) use ($ime) {
                $q->where('name', 'like', "%$ime%");
            });
        }
    
        if ($user->isAdmin()) {
            $kartoni = $query->get();
        } elseif ($user->isDoktor()) {
            $kartoni = $query->where('lekar_id', $user->id)->get();
        } else {
            return response()->json(['message' => 'Nedozvoljen pristup'], 403);
        }
    
        return ZdravstveniKartonResource::collection($kartoni);
    }
    

    /**
     * Kreiranje novog zdravstvenog kartona
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->isDoktor() && !$user->isAdmin()) {
            return response()->json(['message' => 'Samo doktori i admin mogu kreirati kartone'], 403);
        }

        $validated = $request->validate([
            'pacijent_id' => 'required|exists:pacijenti,id',
            'visina' => 'required|numeric|min:0',
            'tezina' => 'required|numeric|min:0',
            'krvni_pritisak' => 'required|string',
            'dijagnoza' => 'required|string',
            'tretman' => 'required|string',
        ]);

        if (ZdravstveniKarton::where('pacijent_id', $validated['pacijent_id'])->exists()) {
            return response()->json(['message' => 'Karton za ovog pacijenta već postoji'], 409);
        }

        // Postavi lekar_id samo ako kreira doktor
        if ($user->isDoktor()) {
            $validated['lekar_id'] = $user->id;
        }

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
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            // Admin može videti sve kartone
        } elseif ($user->isDoktor()) {
            // Lekar može videti samo svoje kartone
            if ($karton->lekar_id !== $user->id) {
                return response()->json(['message' => 'Nemate pristup ovom kartonu'], 403);
            }
        } elseif ($user->isPacijent()) {
            // Pacijent može videti samo svoj karton
            $pacijent = Pacijent::where('user_id', $user->id)->first();
            if (!$pacijent || $karton->pacijent_id !== $pacijent->id) {
                return response()->json(['message' => 'Nemate pristup ovom kartonu'], 403);
            }
        } else {
            return response()->json(['message' => 'Nedozvoljen pristup'], 403);
        }

        return new ZdravstveniKartonResource($karton->load(['pacijent', 'lekar']));
    }

    /**
     * Ažuriranje zdravstvenog kartona
     */
    public function update(Request $request, ZdravstveniKarton $karton)
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            // Admin može ažurirati sve kartone
        } elseif ($user->isDoktor()) {
            // Lekar može ažurirati samo svoje kartone
            if ($karton->lekar_id !== $user->id) {
                return response()->json(['message' => 'Nemate pravo da ažurirate ovaj karton'], 403);
            }
        } else {
            return response()->json(['message' => 'Nedozvoljen pristup'], 403);
        }

        $validated = $request->validate([
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
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            // Admin može brisati sve kartone
        } elseif ($user->isDoktor()) {
            // Lekar može brisati samo svoje kartone
            if ($karton->lekar_id !== $user->id) {
                return response()->json(['message' => 'Nemate pravo da obrišete ovaj karton'], 403);
            }
        } else {
            return response()->json(['message' => 'Nedozvoljen pristup'], 403);
        }

        $karton->delete();

        return response()->json([
            'message' => 'Zdravstveni karton uspešno obrisan'
        ], 200);
    }

    public function promeniLekara(Request $request, ZdravstveniKarton $karton)
    {
        $user = auth()->user();
        
        // Provera da li je korisnik pacijent i da li karton pripada njemu
        if ($user->isPacijent()) {
            $pacijent = Pacijent::where('user_id', $user->id)->first();
            if (!$pacijent || $karton->pacijent_id !== $pacijent->id) {
                return response()->json(['message' => 'Nemate pristup ovom kartonu'], 403);
            }
        } else {
            return response()->json(['message' => 'Samo pacijent može promeniti lekara'], 403);
        }

        $validated = $request->validate([
            'lekar_id' => [
                'required',
                'exists:users,id',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('role', 'doktor'); // Provera da je korisnik lekar
                })
            ]
        ]);

        // Provera da li novi lekar postoji
        $noviLekar = User::where('id', $validated['lekar_id'])
                        ->where('role', 'doktor')
                        ->first();

        if (!$noviLekar) {
            return response()->json(['message' => 'Izabrani lekar ne postoji'], 404);
        }

        // Ažuriranje kartona sa novim lekarom
        $karton->update(['lekar_id' => $validated['lekar_id']]);

        return response()->json([
            'message' => 'Lekar uspešno promenjen',
            'novi_lekar' => $noviLekar->only(['id', 'ime', 'prezime', 'email'])
        ]);
    }

    public function listaLekara()
    {
        $lekari = User::where('role', 'doktor')
                ->select('id', 'ime', 'prezime', 'email', 'specijalizacija')
                ->get();
    
        return response()->json($lekari);
    }

    public function exportCsv()
    {
        $kartoni = ZdravstveniKarton::with(['pacijent', 'lekar'])->get();
    
        $csvHeader = ['ID', 'Pacijent', 'Lekar', 'Visina', 'Tezina', 'Dijagnoza'];
        $callback = function () use ($kartoni, $csvHeader) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $csvHeader);
    
            foreach ($kartoni as $karton) {
                fputcsv($file, [
                    $karton->id,
                    $karton->pacijent->ime . ' ' . $karton->pacijent->prezime,
                    $karton->lekar->name,
                    $karton->visina,
                    $karton->tezina,
                    $karton->dijagnoza,
                ]);
            }
    
            fclose($file);
        };
    
        return response()->stream($callback, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"zdravstveni_kartoni.csv\"",
        ]);
    }

    return response()->stream($callback, 200, [
        "Content-Type" => "text/csv",
        "Content-Disposition" => "attachment; filename=\"zdravstveni_kartoni.csv\"",
    ]);
}

}