<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConfigPaiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfigPaiementController extends Controller
{
    public function edit()
    {
        $config = ConfigPaiement::firstOrCreate([]);
        
        return view('admin.auto-ecole.config-paiement.edit', compact('config'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'montant_x' => 'required|numeric|min:0',
            'montant_y' => 'required|numeric|min:0',
            'montant_z' => 'required|numeric|min:0',
            'delai_paiement_y' => 'required|integer|min:1|max:365'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $config = ConfigPaiement::firstOrCreate([]);
        $config->update($request->all());

        return redirect()->route('admin.auto-ecole.config-paiement.edit')
            ->with('success', 'Configuration mise à jour avec succès');
    }
}
