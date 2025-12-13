INSERT INTO config_paiement (montant_x, montant_y, montant_z, delai_paiement_y, created_at, updated_at)
VALUES (150.00, 200.00, 100.00, 60, NOW(), NOW());

INSERT INTO sessions1 (nom, date_debut, date_fin, date_examen, statut, description, created_at, updated_at) VALUES
('Session Janvier-Février 2026', '2026-01-10', '2026-02-20', '2026-02-25', 'ouvert', 'Session ordinaire permis de conduire - toutes catégories', NOW(), NOW()),
('Session Mars-Avril 2026',      '2026-03-01', '2026-04-15', '2026-04-20', 'ouvert', 'Session spéciale + rattrapage', NOW(), NOW()),
('Session Juin-Juillet 2026',    '2026-06-05', '2026-07-25', '2026-07-30', 'ouvert', 'Session vacances pour étudiants', NOW(), NOW()),
('Session Octobre 2026',         '2026-10-01', '2026-11-15', '2026-11-20', 'ferme',  'Session de rattrapage 2026', NOW(), NOW());


INSERT INTO centres_examen (nom, adresse, ville, telephone, actif, created_at, updated_at) VALUES
('Centre d’’Examen du Permis de Conduire Mvan',        'Carrefour Mvan, face Stade Omnisports',         'Yaoundé',    '699 87 65 43', 1, NOW(), NOW()),
('Auto-École CAPIEM - Centre Examen Bonapriso',       'Rue Koloko, près du Rond-point Dakar',         'Douala',     '699 12 34 56', 1, NOW(), NOW()),
('Centre d’’Examen de Garoua - Lagdo',                 'Route de Lagdo, derrière la Délégation',       'Garoua',     '677 45 23 11', 1, NOW(), NOW()),
('Auto-École Saint Michel - Centre Examen',           'Carrefour Saint Michel, près du marché',       'Bafoussam',  '677 89 01 23', 1, NOW(), NOW()),
('Centre d’’Examen de Bamenda - Up Station',           'Up Station, près de la Délégation Régionale',  'Bamenda',    '677 34 56 78', 1, NOW(), NOW());

INSERT INTO jours_pratique (jour, heure, zone, actif, created_at, updated_at) VALUES
('lundi',    '08:00:00', 'Yaoundé - Mvan / Ngoa-Ekelle',   1, NOW(), NOW()),
('mercredi', '08:00:00', 'Douala - Bonapriso / Akwa',      1, NOW(), NOW()),
('vendredi', '14:00:00', 'Yaoundé - Mvan / Ngoa-Ekelle',   1, NOW(), NOW()),
('samedi',   '07:30:00', 'Douala - Bonapriso / Deido',     1, NOW(), NOW()),
('mardi',    '14:00:00', 'Garoua - Lagdo',                 1, NOW(), NOW()),
('jeudi',    '08:00:00', 'Bamenda - Up Station',           1, NOW(), NOW());

