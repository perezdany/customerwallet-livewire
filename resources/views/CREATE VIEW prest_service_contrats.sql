CREATE VIEW prest_service_contrats
(service_id, contrat_id, id, titre_contrat, montant, debut_contrat, fin_contrat, id_entreprise, created_at, updated_at, path, proforma_file,
bon_commande, reconduction, avenant, etat, id_contrat_parent, id_type_prestationt, 
 nom_entreprise, libele_service)
AS SELECT prestation_services.service_id, prestation_services.contrat_id, contrats.id, contrats.titre_contrat, contrats.montant, contrats.debut_contrat, contrats.fin_contrat, contrats.id_entreprise, 
contrats.created_at, contrats.updated_at, contrats.path, contrats.proforma_file, contrats.bon_commande, contrats.reconduction, contrats.avenant, contrats.etat, contrats.id_contrat_parent, contrats.id_type_prestation, entreprises.nom_entreprise, services.libele_service

FROM services services, contrats contrats, entreprises entreprises, prestation_services prestation_services, typeprestations
WHERE prestation_services.contrat_id = contrats.id AND contrats.id_entreprise = entreprises.id AND contrats.id_type_prestation = typeprestations.id
AND prestation_services.service_id = services.id AND prestation_services.contrat_id = contrats.id