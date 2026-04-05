<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bilan d'Activité</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { width: 100px; margin-bottom: 10px; }
        .title { font-size: 24px; font-weight: bold; color: #333; }
        .subtitle { font-size: 14px; color: #666; margin-top: 5px; }
        .info-section { margin-bottom: 30px; width: 100%; }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 5px; }
        .stats-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .stats-table th, .stats-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .stats-table th { background-color: #f2f2f2; }
        .total-row { font-weight: bold; background-color: #e8f5e9; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 12px; color: #999; padding: 10px; border-top: 1px solid #eee; }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('assets/img/logo.png') }}" class="logo" alt="Logo">
        <div class="title">Bilan d'Activité</div>
        <div class="subtitle">WiLink Tickets - Plateforme de Vente</div>
    </div>

    <table class="info-table">
        <tr>
            <td>
                <strong>Période :</strong><br>
                Bilan Global (Historique complet)
            </td>
            <td style="text-align: right;">
                <strong>Revendeur :</strong><br>
                {{ $user->nom }} {{ $user->prenom }}<br>
                {{ $user->email }}<br>
                {{ $user->phone }}
            </td>
        </tr>
    </table>

    <table class="stats-table">
        <thead>
            <tr>
                <th>Désignation</th>
                <th style="text-align: right;">Montant</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Chiffre d'Affaires (Ventes Tickets)</td>
                <td style="text-align: right;">{{ number_format($stats['chiffre_affaires'], 0, ',', ' ') }} FCFA</td>
            </tr>
            <tr>
                <td>Commission Plateforme (10%)</td>
                <td style="text-align: right; color: #dc3545;">- {{ number_format($stats['commission'], 0, ',', ' ') }} FCFA</td>
            </tr>
            <tr class="total-row">
                <td>Gain Net sur la période</td>
                <td style="text-align: right;">{{ number_format($stats['net_percu'], 0, ',', ' ') }} FCFA</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 30px;">
        <h3>Récapitulatif Global</h3>
        <table class="stats-table">
            <tr>
                <td>Total Retraits Validés (Période)</td>
                <td style="text-align: right;">{{ number_format($stats['total_retraits'], 0, ',', ' ') }} FCFA</td>
            </tr>
            <tr>
                <td><strong>Solde Disponible (Net à retirer)</strong></td>
                <td style="text-align: right;"><strong>{{ number_format($stats['solde_net_disponible'], 0, ',', ' ') }} FCFA</strong></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Document généré le {{ date('d/m/Y à H:i') }} par WiLink Tickets.
    </div>

</body>
</html>
