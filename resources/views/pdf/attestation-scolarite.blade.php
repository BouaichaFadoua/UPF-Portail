<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Attestation de Scolarité</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 14px; line-height: 1.6; color: #333; }
        .header { text-align: center; margin-bottom: 50px; border-bottom: 2px solid #1e293b; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #1e293b; }
        .title { text-align: center; font-size: 20px; text-transform: uppercase; margin: 40px 0; font-weight: bold; text-decoration: underline; }
        .content { margin: 0 40px; text-align: justify; }
        .footer { position: fixed; bottom: 30px; left: 0; right: 0; text-align: center; font-size: 11px; color: #64748b; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        .signature { margin-top: 80px; text-align: right; margin-right: 40px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">UPF Portal - Université</div>
        <div style="font-size: 12px; margin-top: 5px;">Département de la Scolarité</div>
    </div>

    <div class="title">Attestation de Scolarité</div>

    <div class="content">
        <p>Le Doyen de la Faculté / Directeur de l'établissement atteste par la présente que :</p>
        
        <p style="margin-left: 20px;">
            <strong>L'étudiant(e) :</strong> {{ $user->name }}<br>
            <strong>Numéro de matricule :</strong> {{ $etudiant->matricule ?? 'N/A' }}<br>
            <strong>Date et lieu de naissance :</strong> {{ $etudiant->date_naissance ? $etudiant->date_naissance->format('d/m/Y') : 'N/A' }} à {{ $etudiant->lieu_naissance ?? 'N/A' }}
        </p>

        <p>Est régulièrement inscrit(e) dans notre établissement pour l'année universitaire <strong>{{ $etudiant->annee_universitaire ?? '2025-2026' }}</strong>.</p>
        
        <p><strong>Filière :</strong> {{ $etudiant->filiere->nom ?? 'N/A' }}</p>
        
        <p>Cette attestation est délivrée à l'intéressé(e) pour servir et valoir ce que de droit.</p>
    </div>

    <div class="signature">
        Fait à Fès, le {{ $date }}<br>
        Le Doyen / Le Directeur
    </div>

    <div class="footer">
        UPF Portal - Document officiel généré automatiquement le {{ $date }}.<br>
        Réf: DEM-{{ $demande->id }}
    </div>
</body>
</html>
