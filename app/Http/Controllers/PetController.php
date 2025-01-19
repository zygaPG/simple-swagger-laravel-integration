<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PetController extends Controller
{
    public function getPet($id)
    {
        $response = Http::get("https://petstore.swagger.io/v2/pet/{$id}");

        if ($response->ok()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Pet not found'], 404);
    }

    public function addPet(Request $request)
    {
        $response = Http::post('https://petstore.swagger.io/v2/pet', $request->all());

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Failed to add pet'], 400);
    }

    public function updatePet(Request $request, $id)
    {
        $response = Http::put("https://petstore.swagger.io/v2/pet", $request->all());

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Failed to update pet'], 400);
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
