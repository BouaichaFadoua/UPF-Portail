<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ordre de Mission</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 14px; line-height: 1.6; color: #333; }
        .header { text-align: center; margin-bottom: 50px; border-bottom: 2px solid #1e293b; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #1e293b; }
        .title { text-align: center; font-size: 20px; text-transform: uppercase; margin: 40px 0; font-weight: bold; text-decoration: underline; }
        .content { margin: 0 40px; text-align: justify; }
        .footer { position: fixed; bottom: 30px; left: 0; right: 0; text-align: center; font-size: 11px; color: #64748b; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        .signature { margin-top: 80px; text-align: right; margin-right: 40px; font-weight: bold; }
        .mission-details { margin-top: 30px; background-color: #f8fafc; padding: 20px; border: 1px solid #e2e8f0; }
        .mission-details table { width: 100%; }
        .mission-details td { padding: 8px 0; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">UPF Portal - Université</div>
        <div style="font-size: 12px; margin-top: 5px;">Ressources Humaines & Affaires Académiques</div>
    </div>

    <div class="title">Ordre de Mission</div>

    <div class="content">
        <p>Le Doyen de la Faculté / Le Président de l'Université autorise par la présente :</p>
        
        <p style="margin-left: 20px;">
            <strong>M./Mme :</strong> {{ $user->name }}<br>
            <strong>Grade / Fonction :</strong> {{ $professeur->grade ?? 'Enseignant' }}
        </p>

        <p>À se rendre en mission officielle selon les modalités suivantes :</p>

        <div class="mission-details">
            <table>
                <tr>
                    <td style="width: 30%;"><strong>Destination :</strong></td>
                    <td>{{ $demande->destination ?? 'Non spécifiée' }}</td>
                </tr>
                <tr>
                    <td><strong>Motif de la mission :</strong></td>
                    <td>{{ $demande->motif_mission ?? 'Mission institutionnelle' }}</td>
                </tr>
                <tr>
                    <td><strong>Date de départ :</strong></td>
                    <td>{{ $demande->date_depart ? \Carbon\Carbon::parse($demande->date_depart)->format('d/m/Y') : 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Date de retour :</strong></td>
                    <td>{{ $demande->date_retour ? \Carbon\Carbon::parse($demande->date_retour)->format('d/m/Y') : 'N/A' }}</td>
                </tr>
            </table>
        </div>
        
        <p style="margin-top: 30px;">Les frais de déplacement et de séjour seront pris en charge conformément à la réglementation en vigueur, sous réserve de la présentation des justificatifs.</p>
    </div>

    <div class="signature">
        Fait à Fès, le {{ $date }}<br>
        Le Doyen / Le Président
    </div>

    <div class="footer">
        UPF Portal - Document officiel généré automatiquement le {{ $date }}.<br>
        Réf: DEM-{{ $demande->id }}
    </div>
</body>
</html>
