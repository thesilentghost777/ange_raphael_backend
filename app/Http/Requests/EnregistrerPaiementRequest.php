<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnregistrerPaiementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'etudiant_id' => 'required|exists:etudiants,id',
            'montant' => 'required|numeric|min:1',
            'date_paiement' => 'required|date',
            'mode_paiement' => 'required|in:especes,orange_money,mtn_money,virement,cheque',
            'type_frais' => 'required|in:admission,matiere_oeuvre,scolarite,examen,stage_soutenance,toge_location,toge_achat,autre',
            'notes' => 'nullable|string|max:1000'
        ];
    }

    public function messages(): array
    {
        return [
            'etudiant_id.required' => 'L\'étudiant est obligatoire',
            'etudiant_id.exists' => 'Cet étudiant n\'existe pas',
            'montant.required' => 'Le montant est obligatoire',
            'montant.min' => 'Le montant doit être supérieur à 0',
            'date_paiement.required' => 'La date de paiement est obligatoire',
            'mode_paiement.required' => 'Le mode de paiement est obligatoire',
            'type_frais.required' => 'Le type de frais est obligatoire',
        ];
    }
}
