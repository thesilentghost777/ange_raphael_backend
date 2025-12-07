<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreInscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'age' => 'required|integer|min:15|max:100',
            'numero_telephone' => 'required|string|max:20|unique:etudiants,numero_telephone',
            'email' => 'nullable|email|max:255',
            'localisation' => 'required|string|max:255',
            'entite' => 'required|in:centre_formation,auto_ecole,cooperative',
            'code_parrain' => 'nullable|exists:etudiants,id',
        ];

        // Règles spécifiques Centre de Formation
        if ($this->input('entite') === 'centre_formation') {
            $rules['filiere'] = 'required|in:secretariat,audiovisuel,beaute,digital,gestion,comptabilite,commerce,informatique';
            $rules['sous_filiere'] = 'required|string';
            $rules['diplome'] = 'required|in:aqp_3mois,cqp_6mois,dqp_12mois,bts_24mois';
            $rules['niveau_scolaire'] = 'required|string|max:100';
        }

        // Règles spécifiques Auto-école
        if ($this->input('entite') === 'auto_ecole') {
            $rules['type_permis'] = 'required|in:permis_a,permis_b';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est obligatoire',
            'prenoms.required' => 'Les prénoms sont obligatoires',
            'age.required' => 'L\'âge est obligatoire',
            'age.min' => 'L\'âge minimum est de 15 ans',
            'numero_telephone.required' => 'Le numéro de téléphone est obligatoire',
            'numero_telephone.unique' => 'Ce numéro de téléphone est déjà enregistré',
            'entite.required' => 'Veuillez sélectionner une entité',
            'filiere.required' => 'La filière est obligatoire pour le centre de formation',
            'diplome.required' => 'Le type de diplôme est obligatoire',
            'type_permis.required' => 'Le type de permis est obligatoire pour l\'auto-école',
        ];
    }
}
