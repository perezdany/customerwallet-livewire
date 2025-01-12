CREATE VIEW facture_entr_clts
(id, numero_facture, date_reglement, date_emission, montant_facture, id_contrat, reglee,
annulee, created_at, updated_at, created_by, file_path, titre_contrat, montant, debut_contrat, fin_contrat, id_entreprise, nom_entreprise)
AS SELECT factures.id, factures.numero_facture, factures.date_reglement, factures.date_emission, factures.montant_facture, factures.id_contrat, factures.reglee,
factures.annulee, factures.created_at, factures.updated_at, factures.created_by, factures.file_path,
contrats.titre_contrat, contrats.montant, contrats.debut_contrat, contrats.fin_contrat, contrats.id_entreprise, entreprises.nom_entreprise
FROM factures factures, contrats contrats, entreprises entreprises
WHERE factures.id_contrat = contrats.id
AND contrats.id_entreprise = entreprises.id