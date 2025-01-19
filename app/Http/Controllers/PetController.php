<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    
    public function getPets()
    {
        try {
            $response = Http::get('https://petstore.swagger.io/v2/pet/findByStatus?status=available');
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['error' => 'Failed to fetch pets from external API'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch pets: ' . $e->getMessage()], 500);
        }
    }

  
    public function addPet(Request $request)
    {
        try {
            $petData = json_decode($request->input('petData'), true);
            
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('pets', 'public');
                $petData['photoUrls'] = [asset('storage/' . $path)];
            }

            $response = Http::post('https://petstore.swagger.io/v2/pet', $petData);
            
            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['error' => 'Failed to add pet'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add pet: ' . $e->getMessage()], 500);
        }
    }

   
    public function updatePet(Request $request, $id)
    {
        try {
            $petData = json_decode($request->input('petData'), true);
            
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('pets', 'public');
                $petData['photoUrls'] = [asset('storage/' . $path)];
            }

            $response = Http::put("https://petstore.swagger.io/v2/pet", $petData);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['error' => 'Failed to update pet'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update pet: ' . $e->getMessage()], 500);
        }
    }

   
    public function deletePet($id)
    {
        $response = Http::delete("https://petstore.swagger.io/v2/pet/{$id}");

        if ($response->noContent()) {
            return response()->json(['message' => 'Pet deleted successfully']);
        }

        return response()->json(['error' => 'Failed to delete pet'], 400);
    }
}
