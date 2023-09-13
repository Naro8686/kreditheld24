<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>{{__('Invoice').' N'.optional($proposal)->id}}</title>
    <style>
        body {
            margin: 5px 20px 5px;
            padding: 0;
            position: relative;
            font-size: 14px;
        }

        body * {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
<div style="min-height: 200px">
    <div style="float: left;display: inline-block">
        <h2>Provisionsabrechnung</h2>
        <p>für</p>
        <br>
        <p><b>Name,Vorname</b></p>
        <p>{{optional($proposal->user)->full_name}}</p>
        <p><b>Adresse</b></p>
        <p>{{optional($proposal->user)->street." ".optional($proposal->user)->house." ".optional($proposal->user)->postcode ." ".optional($proposal->user)->city}}</p>
        <p><b>St.Nummer</b></p>
        <p>{{optional($proposal->user)->tax_number}}</p>
    </div>
    <div style="float: right;display: inline-block">
        <h2>{{config('app.name', 'Kreditheld24')}}</h2>
        <p>Brockmannstr.204</p>
        <p>48163 Münster</p>
        <p>Tel. <a href="tel:025114914277">0251.149 142 77</a></p>
        <p>E-Mail. <a href="mailto:info@immofi-direkt.de">info@immofi-direkt.de</a></p>
        <p>Web: <a href="{{url('/')}}">www.kreditheld24.de</a></p>
    </div>
</div>
<div style="margin-top: 50px">
    <p style="margin: 10px 0">
        Sehr geehrter {{optional($proposal->user)->full_name}} ,
        die folgende Übersicht zeigt abgerechnete Provisionszahlung für Ihre
        Vermittlungstätigkeit.
    </p>
    <table style="margin: 0 auto" border="1">
        <thead>
        <tr>
            <th>Vorgang</th>
            <th>Kunde</th>
            <th>Nettoprovision</th>
            <th>{{__('Bonus')}}</th>
            <th>Gesamtbetrag</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{$proposal->number}}</td>
            <td>{{"$proposal->firstName $proposal->lastName"}}</td>
            <td>{{$proposal->commission. '%'}}</td>
            <td>{{($proposal->bonus ?? 0). '%'}}</td>
            <td>{{$proposal::CURRENCY.$proposal->payout_amount}}</td>
        </tr>
        </tbody>
    </table>
    <p style="margin: 10px 0">
        Die Provisionen sind wie folgt umsatzsteuerlich berücksichtigt
        Die Provisionen für die Vermittlung in dem Bereich Kredit sind umsatzsteuerfrei gemäß § 4 Nr. 8a UStG.
        Die Provisionen für die Vermittlung in den Bereichen Girokonto, Tagesgeld und Kreditkarte sind umsatzsteuerfrei
        gemäß § 4 Nr. 8d UStG.
        Die Provisionen für die Vermittlung in den Bereichen easy-secure, RKV-Bank, Kfz, Motorrad und Risikoleben sind
        umsatzsteuerfrei gemäß § 4 Nr. 11 UStG.
    </p>
    <p style="margin: 10px 0">
        Der ausgewiesene Provisionsbetrag wird in den nächsten drei bis fünf Werktagen an die bei uns hinterlegte
        Bankverbindung ({{optional($proposal->user)->full_name}} IBAN: {{optional($proposal->user)->card_number}})
        überwiesen.
        Bitte richten Sie Beanstandungen bezüglich dieser Abrechnung innerhalb von 6 Wochen nach Erhalt an uns.
        Andernfalls gilt die Abrechnung und der jeweilige Saldo als anerkannt.
    </p>
    <p style="margin: 10px 0">
        Wir bedanken uns herzlich für die gute Zusammenarbeit.
    </p>
</div>
</body>
</html>
