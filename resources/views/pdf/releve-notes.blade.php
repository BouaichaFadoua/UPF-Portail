<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Relevé de Notes</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 14px; line-height: 1.6; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #1e293b; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #1e293b; }
        .title { text-align: center; font-size: 20px; text-transform: uppercase; margin: 20px 0; font-weight: bold; text-decoration: underline; }
        .info-table { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
        .info-table td { padding: 5px; }
        .notes-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .notes-table th, .notes-table td { border: 1px solid #cbd5e1; padding: 10px; text-align: center; }
        .notes-table th { background-color: #f8fafc; font-weight: bold; }
        .notes-table td.left { text-align: left; }
        .footer { position: fixed; bottom: 30px; left: 0; right: 0; text-align: center; font-size: 11px; color: #64748b; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        .signature { margin-top: 50px; text-align: right; margin-right: 40px; font-weight: bold; }
        .moyenne-generale { text-align: right; font-size: 16px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">UPF Portal - Université</div>
        <div style="font-size: 12px; margin-top: 5px;">Service des Examens et Notes</div>
    </div>

    <div class="title">Relevé de Notes</div>

    <table class="info-table">
        <tr>
            <td><strong>Nom & Prénom :</strong> {{ $user->name }}</td>
            <td><strong>Année Universitaire :</strong> {{ $etudiant->annee_universitaire ?? '2024-2025' }}</td>
        </tr>
        <tr>
            <td><strong>Matricule :</strong> {{ $etudiant->matricule ?? 'N/A' }}</td>
            <td><strong>Filière :</strong> {{ $etudiant->filiere->nom ?? 'N/A' }}</td>
        </tr>
    </table>

    <table class="notes-table">
        <thead>
            <tr>
                <th class="left">Module</th>
                <th>CC1 (20%)</th>
                <th>CC2 (20%)</th>
                <th>Examen (60%)</th>
                <th>Note Finale</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notes as $note)
            <tr>
                <td class="left">{{ $note->module->nom ?? 'Inconnu' }}</td>
                <td>{{ $note->cc1 !== null ? number_format($note->cc1, 2) : '-' }}</td>
                <td>{{ $note->cc2 !== null ? number_format($note->cc2, 2) : '-' }}</td>
                <td>{{ $note->examen !== null ? number_format($note->examen, 2) : '-' }}</td>
                <td><strong>{{ $note->note_finale !== null ? number_format($note->note_finale, 2) : '-' }}</strong></td>
            </tr>
            @endforeach
            @if($notes->isEmpty())
            <tr>
                <td colspan="5" style="text-align: center; padding: 20px;">Aucune note enregistrée pour cette année.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="moyenne-generale">
        Moyenne Générale : {{ $moyenne ? number_format($moyenne, 2) . ' / 20' : 'N/A' }}
    </div>

    <div class="signature">
        Fait à Fès, le {{ $date }}<br>
        Le Responsable de la Scolarité
    </div>

    <div class="footer">
        UPF Portal - Document officiel généré automatiquement le {{ $date }}.<br>
        Réf: DEM-{{ $demande->id }}
    </div>
</body>
</html>
