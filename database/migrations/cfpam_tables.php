<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table de configuration des paiements
        Schema::create('config_paiement', function (Blueprint $table) {
            $table->id();
            $table->decimal('montant_x', 10, 2)->default(150); // Première tranche
            $table->decimal('montant_y', 10, 2)->default(200); // Deuxième tranche
            $table->decimal('montant_z', 10, 2)->default(100); // Bonus niveau 3
            $table->integer('delai_paiement_y')->default(60); // Délai en jours
            $table->timestamps();
        });

        // Sessions1 d'examen
        Schema::create('sessions1', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->date('date_examen');
            $table->enum('statut', ['ouvert', 'ferme'])->default('ouvert');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Centres d'examen
        Schema::create('centres_examen', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('adresse');
            $table->string('ville');
            $table->string('telephone')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });

        // Jours de pratique
        Schema::create('jours_pratique', function (Blueprint $table) {
            $table->id();
            $table->enum('jour', ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche']);
            $table->time('heure');
            $table->string('zone');
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });

        // Table users (étendue)
        Schema::create('auto_ecole_users', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('telephone')->unique();
            $table->string('password');
            $table->enum('type_permis', ['permis_a', 'permis_b']);
            $table->string('code_parrainage')->unique();
            $table->foreignId('parrain_id')->nullable()->constrained('auto_ecole_users')->onDelete('set null');
            $table->integer('niveau_parrainage')->default(0);
            $table->foreignId('session_id')->nullable()->constrained('sessions1')->onDelete('set null');
            $table->foreignId('centre_examen_id')->nullable()->constrained('centres_examen')->onDelete('set null');
            
            // Validation et paiements
            $table->boolean('validated')->default(false);
            $table->datetime('paiement_x_date')->nullable();
            $table->datetime('paiement_y_date')->nullable();
            $table->boolean('paiement_x_effectue')->default(false);
            $table->boolean('paiement_y_effectue')->default(false);
            $table->boolean('dispense_y')->default(false);
            $table->string('code_caisse')->unique()->nullable();
            $table->boolean('cours_verrouilles')->default(true);
            
            $table->datetime('date_inscription');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            
            // Index
            $table->index('code_parrainage');
            $table->index('parrain_id');
            $table->index('niveau_parrainage');
        });

        // Jours pratique de l'utilisateur
        Schema::create('user_jours_pratique', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('auto_ecole_users')->onDelete('cascade');
            $table->foreignId('jour_pratique_id')->constrained('jours_pratique')->onDelete('cascade');
            $table->timestamps();
        });

        // Modules de cours
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->enum('type', ['theorique', 'pratique']);
            $table->integer('ordre');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Chapitres
        Schema::create('chapitres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->string('nom');
            $table->integer('ordre');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Leçons
        Schema::create('lecons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapitre_id')->constrained()->onDelete('cascade');
            $table->string('titre');
            $table->text('contenu_texte');
            $table->string('image_url')->nullable();
            $table->string('video_url')->nullable();
            $table->integer('ordre');
            $table->integer('duree_minutes')->default(10);
            $table->timestamps();
        });

        // Quiz
        Schema::create('quiz', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapitre_id')->constrained()->onDelete('cascade');
            $table->text('question');
            $table->enum('type', ['qcm', 'vrai_faux']);
            $table->integer('ordre');
            $table->timestamps();
        });

        Schema::create('quiz_options', function (Blueprint $table) {
    $table->id();
    $table->foreignId('quiz_id')->constrained('quiz')->onDelete('cascade');
    $table->text('option_texte');
    $table->boolean('est_correcte')->default(false);
    $table->integer('ordre');
    $table->timestamps();
});


        // Progression des leçons
        Schema::create('progression_lecons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('auto_ecole_users')->onDelete('cascade');
            $table->foreignId('lecon_id')->constrained('lecons')->onDelete('cascade');
            $table->boolean('completee')->default(false);
            $table->datetime('date_completion')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'lecon_id']);
        });

        // Résultats des quiz
        Schema::create('resultats_quiz', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('auto_ecole_users')->onDelete('cascade');
            $table->foreignId('chapitre_id')->constrained()->onDelete('cascade');
            $table->decimal('score', 5, 2);
            $table->boolean('reussi')->default(false);
            $table->datetime('date_tentative');
            $table->boolean('peut_retenter')->default(true);
            $table->datetime('date_prochaine_tentative')->nullable();
            $table->timestamps();
        });

        // Paiements
Schema::create('auto_ecole_paiements', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('auto_ecole_users')->onDelete('cascade');
    $table->decimal('montant', 10, 2);
    $table->enum('type_paiement', ['en_ligne', 'code_caisse']);
    $table->enum('tranche', ['x', 'y', 'complet']); // Ajout de 'complet'
    $table->string('transaction_id')->unique()->nullable();
    $table->enum('statut', ['en_attente', 'valide', 'echoue'])->default('en_attente');
    $table->string('methode_paiement')->nullable(); // orange_money, mtn_money, card
    $table->text('notes')->nullable();
    $table->datetime('date_paiement');
    $table->timestamps();
    
    $table->index(['user_id', 'tranche']);
});

        // Filleuls (pour tracking du parrainage)
        Schema::create('filleuls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parrain_id')->constrained('auto_ecole_users')->onDelete('cascade');
            $table->foreignId('filleul_id')->constrained('auto_ecole_users')->onDelete('cascade');
            $table->datetime('date_parrainage');
            $table->timestamps();
            
            $table->unique(['parrain_id', 'filleul_id']);
        });

        // Codes caisse
        Schema::create('codes_caisse', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('user_id')->nullable()->constrained('auto_ecole_users')->onDelete('set null');
            $table->decimal('montant', 10, 2);
            $table->enum('tranche', ['x', 'y', 'complet']);
            $table->boolean('utilise')->default(false);
            $table->datetime('date_utilisation')->nullable();
            $table->datetime('date_expiration')->nullable();
            $table->foreignId('genere_par')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('codes_caisse');
        Schema::dropIfExists('filleuls');
        Schema::dropIfExists('auto_ecole_paiements');
        Schema::dropIfExists('resultats_quiz');
        Schema::dropIfExists('progression_lecons');
        Schema::dropIfExists('quiz_options');
        Schema::dropIfExists('quiz');
        Schema::dropIfExists('lecons');
        Schema::dropIfExists('chapitres');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('user_jours_pratique');
        Schema::dropIfExists('auto_ecole_users');
        Schema::dropIfExists('jours_pratique');
        Schema::dropIfExists('centres_examen');
        Schema::dropIfExists('sessions1');
        Schema::dropIfExists('config_paiement');
    }
};
