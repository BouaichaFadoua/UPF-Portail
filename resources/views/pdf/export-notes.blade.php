<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Relevé de Notes – Export</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 9px; color: #1e293b; background: #fff; }
        .header { background: linear-gradient(135deg, #0c4a6e, #0284c7); color: white; padding: 18px 24px; margin-bottom: 18px; }
        .header h1 { font-size: 18px; font-weight: 700; letter-spacing: 0.5px; }
        .header p  { font-size: 10px; opacity: 0.85; margin-top: 3px; }
        .meta { display: flex; justify-content: space-between; margin: 0 24px 12px; font-size: 9px; color: #64748b; }
        table { width: calc(100% - 48px); margin: 0 24px; border-collapse: collapse; }
        thead th {
            background: #0284c7; color: #fff; padding: 7px 8px;
            text-align: left; font-size: 9px; font-weight: 700; letter-spacing: 0.3px;
        }
        tbody tr:nth-child(even) { background: #f0f9ff; }
        tbody tr:hover { background: #e0f2fe; }
        tbody td { padding: 6px 8px; border-bottom: 1px solid #e2e8f0; }
        .mention-excellent { color: #15803d; font-weight: 700; }
        .mention-bien      { color: #0369a1; font-weight: 600; }
        .mention-passable  { color: #b45309; }
        .mention-echec     { color: #b91c1c; }
        .footer { margin: 16px 24px 0; font-size: 8px; color: #94a3b8; text-align: right; border-top: 1px solid #e2e8f0; padding-top: 8px; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 8px; font-weight: 700; }
        .badge-cours { background: #dbeafe; color: #1d4ed8; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 Relevé de Notes – Export</h1>
        <p>Généré le {{ now()->format('d/m/Y à H:i') }} • Université de Précision et de Formation</p>
    </div>

    <div class="meta">
        <span>Total : <strong>{{ $notes->count() }}</strong> enregistrements</span>
        <span>Année universitaire : <strong>2025-2026</strong></span>
    </div>

    <table>
        <thead>
            <tr>
                <th>Matricule</th>
                <th>Nom Étudiant</th>
                <th>Filière</th>
                <th>Groupe</th>
                <th>Module</th>
                <th>CC1</th>
                <th>CC2</th>
                <th>Examen</th>
                <th>Note Finale</th>
                <th>Mention</th>
            </tr>
        </thead>
        <tbody>
            @forelse($notes as $note)
            @php
                $nf = $note->note_finale;
                $mention = '—';
                $cls     = '';
                if ($nf !== null) {
                    if ($nf >= 16)      { $mention = 'Excellent'; $cls = 'mention-excellent'; }
                    elseif ($nf >= 14)  { $mention = 'Très Bien'; $cls = 'mention-bien'; }
                    elseif ($nf >= 12)  { $mention = 'Bien';      $cls = 'mention-bien'; }
                    elseif ($nf >= 10)  { $mention = 'Passable';  $cls = 'mention-passable'; }
                    else                { $mention = 'Échec';     $cls = 'mention-echec'; }
                }
            @endphp
            <tr>
                <td><strong>{{ $note->etudiant->matricule }}</strong></td>
                <td>{{ $note->etudiant->user->name }}</td>
                <td>{{ $note->etudiant->filiere->code ?? '—' }}</td>
                <td>{{ $note->etudiant->groupe->nom ?? '—' }}</td>
                <td>{{ $note->module->nom }}</td>
                <td>{{ $note->cc1 ?? '—' }}</td>
                <td>{{ $note->cc2 ?? '—' }}</td>
                <td>{{ $note->examen ?? '—' }}</td>
                <td><strong>{{ $nf !== null ? number_format($nf, 2) : '—' }}</strong></td>
                <td class="{{ $cls }}">{{ $mention }}</td>
            </tr>
            @empty
            <tr><td colspan="10" style="text-align:center; padding:16px; color:#94a3b8;">Aucune note à afficher.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Document généré automatiquement par UPF Portail • {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
