<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Absences – Export</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 9px; color: #1e293b; background: #fff; }
        .header { background: linear-gradient(135deg, #7c3aed, #a855f7); color: white; padding: 18px 24px; margin-bottom: 18px; }
        .header h1 { font-size: 18px; font-weight: 700; }
        .header p  { font-size: 10px; opacity: 0.85; margin-top: 3px; }
        .meta { display: flex; justify-content: space-between; margin: 0 24px 12px; font-size: 9px; color: #64748b; }
        table { width: calc(100% - 48px); margin: 0 24px; border-collapse: collapse; }
        thead th {
            background: #7c3aed; color: #fff; padding: 7px 8px;
            text-align: left; font-size: 9px; font-weight: 700;
        }
        tbody tr:nth-child(even) { background: #faf5ff; }
        tbody td { padding: 6px 8px; border-bottom: 1px solid #e2e8f0; }
        .alert-high   { color: #b91c1c; font-weight: 700; }
        .alert-medium { color: #b45309; font-weight: 600; }
        .alert-ok     { color: #15803d; }
        .bar-bg { background: #e2e8f0; border-radius: 4px; height: 6px; width: 60px; display: inline-block; vertical-align: middle; }
        .bar-fill { background: #7c3aed; border-radius: 4px; height: 6px; display: block; }
        .footer { margin: 16px 24px 0; font-size: 8px; color: #94a3b8; text-align: right; border-top: 1px solid #e2e8f0; padding-top: 8px; }
        .stats-box { display: flex; justify-content: space-around; margin: 0 24px 16px; }
        .stat { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 14px; text-align: center; }
        .stat .val { font-size: 16px; font-weight: 700; color: #7c3aed; }
        .stat .lbl { font-size: 8px; color: #64748b; margin-top: 2px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Liste des Absences – Export</h1>
        <p>Généré le {{ now()->format('d/m/Y à H:i') }} • Université de Précision et de Formation</p>
    </div>

    @php
        $totalAbs = $etudiants->sum('total_absences');
        $totalJust = $etudiants->sum('absences_justifiees');
        $totalNonJust = $etudiants->sum('absences_non_justifiees');
    @endphp

    <div class="stats-box">
        <div class="stat">
            <div class="val">{{ $etudiants->count() }}</div>
            <div class="lbl">Étudiants</div>
        </div>
        <div class="stat">
            <div class="val">{{ $totalAbs }}</div>
            <div class="lbl">Total Absences</div>
        </div>
        <div class="stat">
            <div class="val" style="color:#15803d">{{ $totalJust }}</div>
            <div class="lbl">Justifiées</div>
        </div>
        <div class="stat">
            <div class="val" style="color:#b91c1c">{{ $totalNonJust }}</div>
            <div class="lbl">Non Justifiées</div>
        </div>
    </div>

    <div class="meta">
        <span>Total : <strong>{{ $etudiants->count() }}</strong> étudiants</span>
        <span>Année universitaire : <strong>2025-2026</strong></span>
    </div>

    <table>
        <thead>
            <tr>
                <th>Matricule</th>
                <th>Nom Étudiant</th>
                <th>Filière</th>
                <th>Groupe</th>
                <th>Total</th>
                <th>Justifiées</th>
                <th>Non Just.</th>
                <th>Taux Absence</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($etudiants as $e)
            @php
                $total   = $e->total_absences ?? 0;
                $just    = $e->absences_justifiees ?? 0;
                $nonjust = $e->absences_non_justifiees ?? 0;
                $pct     = $total > 0 ? min(100, round($nonjust / max($total, 1) * 100)) : 0;
                $cls = $nonjust >= 10 ? 'alert-high' : ($nonjust >= 5 ? 'alert-medium' : 'alert-ok');
                $statut = $nonjust >= 10 ? '⚠ Critique' : ($nonjust >= 5 ? '! Attention' : '✓ OK');
            @endphp
            <tr>
                <td><strong>{{ $e->matricule }}</strong></td>
                <td>{{ $e->user->name }}</td>
                <td>{{ $e->filiere->code ?? '—' }}</td>
                <td>{{ $e->groupe->nom  ?? '—' }}</td>
                <td>{{ $total }}</td>
                <td style="color:#15803d">{{ $just }}</td>
                <td style="color:#b91c1c"><strong>{{ $nonjust }}</strong></td>
                <td>
                    <span class="bar-bg"><span class="bar-fill" style="width:{{ $pct }}%"></span></span>
                    <span style="margin-left:4px">{{ $pct }}%</span>
                </td>
                <td class="{{ $cls }}">{{ $statut }}</td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align:center; padding:16px; color:#94a3b8;">Aucun étudiant à afficher.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Document généré automatiquement par UPF Portail • {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
