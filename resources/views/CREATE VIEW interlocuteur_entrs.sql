CREATE VIEW interlocuteur_entrs
(id, titre, nom, tel, email, fonction, id_entreprise, created_at, updated_at, created_by, intitule, nom_entreprise, nom_prenoms)
AS SELECT interlocuteurs.id, interlocuteurs.titre, interlocuteurs.nom, interlocuteurs.tel, interlocuteurs.email, interlocuteurs.fonction, 
interlocuteurs.id_entreprise, interlocuteurs.created_at, interlocuteurs.updated_at, interlocuteurs.created_by, professions.intitule, entreprises.nom_entreprise,
utilisateurs.nom_prenoms
FROM interlocuteurs interlocuteurs, entreprises entreprises, professions professions, utilisateurs utilisateurs
WHERE interlocuteurs.id_entreprise = entreprises.id
AND interlocuteurs.fonction = professions.id
AND interlocuteurs.created_by = utilisateurs.id