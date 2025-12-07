<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Chapitre;
use App\Models\Lecon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CoursController extends Controller
{
    // ============ MODULES ============
   public function indexModules()
{
    $modules = Module::withCount(['chapitres', 'lecons'])
        ->orderBy('ordre')
        ->get();
    
    return view('admin.auto-ecole.cours.modules.index', compact('modules'));
}

    public function createModule()
    {
        return view('admin.auto-ecole.cours.modules.create');
    }

    public function storeModule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ordre' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $module = Module::create($request->all());

        return redirect()->route('admin.auto-ecole.cours.modules.index')
            ->with('success', 'Module créé avec succès');
    }

    public function editModule($id)
    {
        $module = Module::findOrFail($id);
        return view('admin.auto-ecole.cours.modules.edit', compact('module'));
    }

    public function updateModule(Request $request, $id)
    {
        $module = Module::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ordre' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $module->update($request->all());

        return redirect()->route('admin.auto-ecole.cours.modules.index')
            ->with('success', 'Module mis à jour avec succès');
    }

    public function destroyModule($id)
    {
        $module = Module::findOrFail($id);
        
        if ($module->chapitres()->count() > 0) {
            return redirect()->back()->with('error', 'Impossible de supprimer un module contenant des chapitres');
        }

        $module->delete();

        return redirect()->route('admin.auto-ecole.cours.modules.index')
            ->with('success', 'Module supprimé avec succès');
    }

    // ============ CHAPITRES ============
    
    public function indexChapitres($moduleId)
    {
        $module = Module::with(['chapitres' => function($q) {
            $q->withCount('lecons')->orderBy('ordre');
        }])->findOrFail($moduleId);

        return view('admin.auto-ecole.cours.chapitres.index', compact('module'));
    }

    public function createChapitre($moduleId)
    {
        $module = Module::findOrFail($moduleId);
        return view('admin.auto-ecole.cours.chapitres.create', compact('module'));
    }

    public function storeChapitre(Request $request, $moduleId)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ordre' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $chapitre = Chapitre::create([
            'module_id' => $moduleId,
            'nom' => $request->nom,
            'description' => $request->description,
            'ordre' => $request->ordre
        ]);

        return redirect()->route('admin.auto-ecole.cours.chapitres.index', $moduleId)
            ->with('success', 'Chapitre créé avec succès');
    }

    public function editChapitre($moduleId, $id)
    {
        $module = Module::findOrFail($moduleId);
        $chapitre = Chapitre::findOrFail($id);
        return view('admin.auto-ecole.cours.chapitres.edit', compact('module', 'chapitre'));
    }

    public function updateChapitre(Request $request, $moduleId, $id)
    {
        $chapitre = Chapitre::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ordre' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $chapitre->update($request->all());

        return redirect()->route('admin.auto-ecole.cours.chapitres.index', $moduleId)
            ->with('success', 'Chapitre mis à jour avec succès');
    }

    public function destroyChapitre($moduleId, $id)
    {
        $chapitre = Chapitre::findOrFail($id);
        
        if ($chapitre->lecons()->count() > 0) {
            return redirect()->back()->with('error', 'Impossible de supprimer un chapitre contenant des leçons');
        }

        $chapitre->delete();

        return redirect()->route('admin.auto-ecole.cours.chapitres.index', $moduleId)
            ->with('success', 'Chapitre supprimé avec succès');
    }

    // ============ LEÇONS ============
    
    public function indexLecons($moduleId, $chapitreId)
    {
        $module = Module::findOrFail($moduleId);
        $chapitre = Chapitre::with(['lecons' => function($q) {
            $q->orderBy('ordre');
        }])->findOrFail($chapitreId);

        return view('admin.auto-ecole.cours.lecons.index', compact('module', 'chapitre'));
    }

    public function createLecon($moduleId, $chapitreId)
    {
        $module = Module::findOrFail($moduleId);
        $chapitre = Chapitre::findOrFail($chapitreId);
        return view('admin.auto-ecole.cours.lecons.create', compact('module', 'chapitre'));
    }

    public function storeLecon(Request $request, $moduleId, $chapitreId)
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'required|string|max:255',
            'contenu_texte' => 'required|string',
            'ordre' => 'required|integer|min:1',
            'duree_minutes' => 'nullable|integer|min:1',
            'image' => 'nullable|image|max:5120',
            'video_url' => 'nullable|url'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->except(['image']);

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('lecons', 'public');
        }

        $lecon = Lecon::create([
            'chapitre_id' => $chapitreId,
            ...$data
        ]);

        return redirect()->route('admin.auto-ecole.cours.lecons.index', [$moduleId, $chapitreId])
            ->with('success', 'Leçon créée avec succès');
    }

    public function editLecon($moduleId, $chapitreId, $id)
    {
        $module = Module::findOrFail($moduleId);
        $chapitre = Chapitre::findOrFail($chapitreId);
        $lecon = Lecon::findOrFail($id);
        return view('admin.auto-ecole.cours.lecons.edit', compact('module', 'chapitre', 'lecon'));
    }

    public function updateLecon(Request $request, $moduleId, $chapitreId, $id)
    {
        $lecon = Lecon::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'titre' => 'required|string|max:255',
            'contenu_texte' => 'required|string',
            'ordre' => 'required|integer|min:1',
            'duree_minutes' => 'nullable|integer|min:1',
            'image' => 'nullable|image|max:5120',
            'video_url' => 'nullable|url'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->except(['image']);

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($lecon->image_url) {
                Storage::disk('public')->delete($lecon->image_url);
            }
            $data['image_url'] = $request->file('image')->store('lecons', 'public');
        }

        $lecon->update($data);

        return redirect()->route('admin.auto-ecole.cours.lecons.index', [$moduleId, $chapitreId])
            ->with('success', 'Leçon mise à jour avec succès');
    }

    public function destroyLecon($moduleId, $chapitreId, $id)
    {
        $lecon = Lecon::findOrFail($id);

        // Supprimer l'image associée
        if ($lecon->image_url) {
            Storage::disk('public')->delete($lecon->image_url);
        }

        $lecon->delete();

        return redirect()->route('admin.auto-ecole.cours.lecons.index', [$moduleId, $chapitreId])
            ->with('success', 'Leçon supprimée avec succès');
    }
}
