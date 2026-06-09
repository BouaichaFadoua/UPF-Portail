<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Attestation de Travail</title>
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
        <div style="font-size: 12px; margin-top: 5px;">Ressources Humaines</div>
    </div>

    <div class="title">Attestation de Travail</div>

    <div class="content">
        <p>Le Président de l'Université atteste par la présente que :</p>
        
        <p style="margin-left: 20px;">
            <strong>M./Mme :</strong> {{ $user->name }}<br>
            <strong>Grade / Fonction :</strong> {{ $professeur->grade ?? 'Enseignant' }}<br>
            <strong>Spécialité :</strong> {{ $professeur->specialite ?? 'N/A' }}
        </p>

        <p>Exerce ses fonctions au sein de notre établissement en qualité de professeur permanent/vacataire pour l'année en cours.</p>
        
        <p>Cette attestation est délivrée à l'intéressé(e) sur sa demande, pour servir et valoir ce que de droit.</p>
    </div>

    <div class="signature">
        Fait à Fès, le {{ $date }}<br>
        Le Service des Ressources Humaines
    </div>

    <div class="footer">
        UPF Portal - Document officiel généré automatiquement le {{ $date }}.<br>
        Réf: DEM-{{ $demande->id }}
    </div>
</body>
</html>
