CREATE VIEW contrat_entr_services
(id, titre_contrat, montant, debut_contrat, fin_contrat, id_entreprise, created_at, updated_at, path, proforma_file,
bon_commande, reconduction, avenant, etat, id_contrat_parent, id_type_prestationt, 
 nom_entreprise)
AS SELECT contrats.id, contrats.titre_contrat, contrats.montant, contrats.debut_contrat, contrats.fin_contrat, contrats.id_entreprise, 
contrats.created_at, contrats.updated_at, contrats.path, contrats.proforma_file, contrats.bon_commande, contrats.reconduction, contrats.avenant, contrats.etat, contrats.id_contrat_parent, contrats.id_type_prestation, entreprises.nom_entreprise

FROM contrats contrats, entreprises entreprises, typeprestations typeprestations
WHERE contrats.id_entreprise = entreprises.id AND contrats.id_type_prestation = typeprestations.id