<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{__('Proposal').' N'.optional($proposal)->id}}</title>
    <style>
        body * {
            margin: 0;
            padding: 0;
            font-family: firefly, DejaVu Sans, sans-serif;
        }

        body {
            /*margin: 5px 20px 5px;*/
            padding: 0;
            position: relative;
        }

        table {
            width: 100%;
            margin-bottom: 25px !important;
        }

        table > thead > tr > th {
            text-transform: capitalize;
            background-color: #e6e7e8;
            border-top: 1px solid #909294;
            padding: 0 5px;
            font-weight: bold;
        }

        th, td {
            padding: 5px 0;
            vertical-align: top;
            text-align: left;
        }

        tbody > tr {
            line-height: 35px;
        }

        input {
            border: none;
        }

        input[type=text] {
            border-bottom: 1px dotted #000000;
            background-color: transparent;
            width: 300px;
        }

        label {
            position: relative;
            display: block;
        }


        label > i {
            position: absolute;
            width: 100%;
            font-size: 10px;
            left: 0px;
            top: 15px;
        }

        ul:not(.default) {
            padding: 0;
        }

        ul:not(.default) > li {
            list-style-type: none;
            line-height: 20px;
            /*margin-bottom: 20px;*/
        }

        p {
            line-height: 20px;
            font-size: 13px;
        }
    </style>
</head>
<body>
<div>
    <table>
        <thead>
        <tr>
            <th colspan="2">{{ __('Proposal') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <label>
                    <input type="text"
                           value="{!! empty($proposal->status)?"":__("status.{$proposal->status}") !!}">
                    <i>{{ __('Status')}}</i>
                </label>
            </td>
            <td>
                <label>
                    <input type="text"
                           value="{{$proposal->number}}">
                    <i>{{ __('Proposal number')}}</i>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                <label>
                    <input type="text"
                           value="{{$proposal->commission}}">
                    <i>{{ __('Commission')}}</i>
                </label>
            </td>
            <td>
                <label>
                    <input type="text"
                           value="{{$proposal->bonus}}">
                    <i>{{ __('Bonus')}}</i>
                </label>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label>
                    <input type="text"
                           value="{{optional($proposal->category)->parent->name ?? ''}}">
                    <i>{{ __('Category')}}</i>
                </label>
            </td>
        </tr>
        </tbody>
    </table>
    <table>
        <thead>
        <tr>
            <th colspan="2">{{ __('personal data') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <label>
                    <input type="text"
                           value="{{$proposal->firstName}}">
                    <i>{{ __('Name')}}</i>
                </label>
            </td>
            <td>
                <label>
                    <input type="text"
                           value="{{$proposal->lastName}}">
                    <i>{{ __('Surname')}}</i>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                <label>
                    <input type="text"
                           value="{{$proposal->phoneNumber}}">
                    <i>{{ __('Phone Number')}}</i>
                </label>
            </td>
            <td>
                <label>
                    <input type="text"
                           value="{{$proposal->email}}">
                    <i>{{ __('Email')}}</i>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                <label>
                    <input type="text"
                           value="{{$proposal->birthday?->format('d.m.Y')}}">
                    <i>{{ __('Birthday')}}</i>
                </label>
            </td>
            <td>
                <label>
                    <input type="text"
                           value="{{$proposal->birthplace}}">
                    <i>{{ __('Birthplace')}}</i>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                <label>
                    <input type="text" value="{{$proposal->familyStatus}}">
                    <i>{{__('Family status')}}</i>
                </label>
            </td>
            <td>
                <label><i>{{__('Gender')}}</i></label>
                <label style="margin-right: 20px;display: inline">
                    <input style="margin: -3px 5px -3px 0"
                           type="radio"
                           @if($proposal->gender === 'male') checked="checked" @endif >{{__('Male')}}
                </label>
                <label style=";display: inline">
                    <input style="margin: -3px 5px -3px 0"
                           type="radio"
                           @if($proposal->gender === 'female') checked="checked" @endif >{{__('Female')}}
                </label>
            </td>
        </tr>
        </tbody>
    </table>
    <table>
        <thead>
        <tr>
            <th colspan="2">{{ __('Spouse details') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <label>
                    <input type="text"
                           value="{{optional($proposal->spouse)['firstName']}}">
                    <i>{{ __('Name')}}</i>
                </label>
            </td>
            <td>
                <label>
                    <input type="text"
                           value="{{optional($proposal->spouse)['lastName']}}">
                    <i>{{ __('Surname')}}</i>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                <label>
                    <input type="text"
                           value="{{(optional($proposal->spouse)['birthday'] ? \Illuminate\Support\Carbon::parse($proposal->spouse['birthday'])->format('d.m.Y') : '')}}">
                    <i>{{ __('Birthday')}}</i>
                </label>
            </td>
            <td>
                <label>
                    <input type="text"
                           value="{{optional($proposal->spouse)['birthplace']}}">
                    <i>{{ __('Birthplace')}}</i>
                </label>
            </td>

        </tr>
        <tr>
            <td colspan="2">
                <label>
                    <input type="text"
                           value="{{$proposal->childrenCount}}">
                    <i>{{ __('Count children')}}</i>
                </label>
            </td>
        </tr>
        </tbody>
    </table>
    <table>
        <thead>
        <tr>
            <th colspan="2">{{ __('Life situation') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <label>
                    <input type="text"
                           value="{{$proposal->street}}">
                    <i>{{ __('Street')}}</i>
                </label>
            </td>
            <td>
                <label>
                    <input type="text"
                           value="{{$proposal->house}}">
                    <i>{{ __('House')}}</i>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                <label>
                    <input type="text"
                           value="{{$proposal->postcode}}">
                    <i>{{ __('Postcode')}}</i>
                </label>
            </td>
            <td>
                <label>
                    <input type="text"
                           value="{{$proposal->city}}">
                    <i>{{ __('City')}}</i>
                </label>
            </td>
        </tr>
        @if($proposal->residenceType)
            <tr>
                <td>
                    <label>
                        <input type="text"
                               value="{{trans("proposal.residenceTypes.{$proposal->residenceType}")}}">
                        <i>{{ __('residence Type')}}</i>
                    </label>
                </td>
                <td>
                    <label>
                        <input type="text"
                               value="{{$proposal::CURRENCY. $proposal->rentAmount}}">
                        <i>{{ __('Rent')}}</i>
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label>
                        <input type="text"
                               value="{{$proposal::CURRENCY. $proposal->communalAmount}}">
                        <i>{{ __('Communal Amount')}}</i>
                    </label>
                </td>
                <td>
                    <label>
                        <input type="text"
                               value="{{$proposal::CURRENCY. $proposal->communalExpenses }}">
                        <i>{{ __('Communal Expenses')}}</i>
                    </label>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <label>
                        <input type="text"
                               value="{{$proposal->residenceDate?->format('d.m.Y') }}">
                        <i>{{ __('residence Date')}}</i>
                    </label>
                </td>
            </tr>
        @endif
        </tbody>
    </table>
    <table>
        <thead>
        <tr>
            <th colspan="2">{{ __('Old Address') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <label>
                    <input type="text"
                           value="{{optional($proposal->oldAddress)['street']}}">
                    <i>{{ __('Street')}}</i>
                </label>
            </td>
            <td>
                <label>
                    <input type="text"
                           value="{{optional($proposal->oldAddress)['house']}}">
                    <i>{{ __('House')}}</i>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                <label>
                    <input type="text"
                           value="{{optional($proposal->oldAddress)['postcode']}}">
                    <i>{{ __('Postcode')}}</i>
                </label>
            </td>
            <td>
                <label>
                    <input type="text"
                           value="{{optional($proposal->oldAddress)['city']}}">
                    <i>{{ __('City')}}</i>
                </label>
            </td>
        </tr>
        </tbody>
    </table>
    <table>
        <thead>
        <tr>
            <th colspan="2">{{ __('Funding request') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <label>
                    <input type="text"
                           value="{{optional($proposal->category)->name}}">
                    <i>{{ __('Credit Type')}}</i>
                </label>
            </td>
            <td>
                <label>
                    <input type="text"
                           value="{{$proposal::CURRENCY.$proposal->creditAmount}}">
                    <i>{{ __('Desired loan amount ?')}}</i>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                <label>
                    <input type="text"
                           value="{{$proposal->deadline}}">
                    <i>{{ __('For what time (month) ?')}}</i>
                </label>
            </td>
            <td>
                <label>
                    <input type="text"
                           value="{{$proposal::CURRENCY.$proposal->monthlyPayment}}">
                    <i>{{ __('Desired amount of payment per month ?')}}</i>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                <label>
                    <input type="text"
                           value="{{count(($proposal->otherCredit ?? []))}}">
                    <i>{{ __('Number of existing loans')}}</i>
                </label>
                <ul>
                    @foreach(($proposal->otherCredit ?? []) as $credit)
                        <li>{{ __('Monthly Payment').': '.$proposal::CURRENCY.$credit['monthlyPayment'] }}</li>
                        <li>{{ __('Credit balance').': '.$proposal::CURRENCY.$credit['creditBalance'] }}</li>
                        <li>{{ __('Repay a credit ?').': '.__(Str::ucfirst($credit['repay'])) }}</li>
                        <li>{{ __('Bank number').': '.$credit['bankNumber'] }}</li>
                    @endforeach
                </ul>
            </td>
            <td>
                <div>
                    <label><i>{{ __('Insurance') }}</i></label>
                    <label style="display: inline">
                        <input style="margin: -5px 5px -5px 0"
                               type="checkbox"
                               @if(optional($proposal->insurance)['death']) checked="checked" @endif >{{__('Death')}}
                    </label>
                    <label style="display: inline">
                        <input style="margin: -5px 5px -5px 0"
                               type="checkbox"
                               @if(optional($proposal->insurance)['disease']) checked="checked" @endif >{{__('Disease')}}
                    </label>
                    <label style="display: inline">
                        <input style="margin: -5px 5px -5px 0"
                               type="checkbox"
                               @if(optional($proposal->insurance)['unemployment']) checked="checked" @endif >{{__('Unemployment')}}
                    </label>
                </div>
            </td>
        </tr>
        @if($proposal->applicantType)
            <tr>
                <td colspan="2">
                    <label>
                        <input type="text" autocomplete="off"
                               value="{{trans("proposal.applicantTypes.{$proposal->applicantType}")}}">
                        <i>{{ __('Type')}}</i>
                    </label>
                </td>
            </tr>
        @endif
        <tr>
            <td colspan="2">
                <fieldset style="margin-top: 50px;padding: 15px">
                    <legend style="color: black;margin-top: -23px">{{ __('Comment')}}</legend>
                    <p style="margin: 0;padding: 0;line-height: 15px">{{$proposal->creditComment}}</p>
                </fieldset>
            </td>
        </tr>
        </tbody>
    </table>
    <table>
        <thead>
        <tr>
            <th colspan="2">{{ __('Object Data') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <label>
                    <input type="text"
                           value="{{optional($proposal->objectData)['street']}}">
                    <i>{{ __('Street')}}</i>
                </label>
            </td>
            <td>
                <label>
                    <input type="text"
                           value="{{optional($proposal->objectData)['house']}}">
                    <i>{{ __('House')}}</i>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                <label>
                    <input type="text"
                           value="{{optional($proposal->objectData)['postcode']}}">
                    <i>{{ __('Postcode')}}</i>
                </label>
            </td>
            <td>
                <label>
                    <input type="text"
                           value="{{optional($proposal->objectData)['city']}}">
                    <i>{{ __('City')}}</i>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                <label>
                    <input type="text"
                           value="{{(optional($proposal->objectData)['objectType'] ? trans("proposal.objectTypes.".optional($proposal->objectData)['objectType']) : '')}}">
                    <i>{{ __('Object Type')}}</i>
                </label>
            </td>
            <td>
                <label>
                    <input type="text"
                           value="{{optional($proposal->objectData)['yearConstruction']}}">
                    <i>{{ __('Year of construction')}}</i>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                <label>
                    <input type="text"
                           value="{{optional($proposal->objectData)['yearRepair']}}">
                    <i>{{ __('Year of repair')}}</i>
                </label>
            </td>
            <td>
                <label>
                    <input type="text"
                           value="{{optional($proposal->objectData)['plotSize']}}">
                    <i>{!! __('Plot size').' (m<sup>2</sup>)' !!}</i>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                <label>
                    <input type="text"
                           value="{{optional($proposal->objectData)['livingSpace']}}">
                    <i>{!! __('Living space').' (m<sup>2</sup>)' !!}</i>
                </label>
            </td>
            <td>
                <label>
                    <input type="text"
                           value="{{$proposal::CURRENCY.optional($proposal->objectData)['buildPrice']}}">
                    <i>{{__('Purchase or build price')}}</i>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                <label>
                    <input type="text"
                           value="{{$proposal::CURRENCY.optional($proposal->objectData)['accumulation']}}">
                    <i>{{__('Own accumulation')}}</i>
                </label>
            </td>
            <td>
                <label>
                    <input type="text"
                           value="{{optional($proposal->objectData)['brokerageFees']}}">
                    <i>{{__('Brokerage fees').'(%)'}}</i>
                </label>
            </td>
        </tr>
        </tbody>
    </table>
    <table>
        <thead>
        <tr>
            <th colspan="2">{{ __('Erklärungen der Darlehensnehmer') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <p>
                    Hiermit beauftrage/n ich/wir die im Darlehensvermittlungsvertrag genannte
                    Firma mit der Vermittlung eines Darlehens zur Immobilienfinanzierung
                    sowie damit in Zusammenhang stehender Finanzdienstleistungen. Ich/Wir
                    bevollmächtige/n die im Darlehensvermittlungsvertrag genannte Firma alle
                    hierfür erforderliche Unterlagen (Darlehensantrag, Objekt- und Bonitätsunterlagen
                    etc.) an einen zur Finanzierung vorgesehenen Darlehensgeber
                    weiterzuleiten, Konditionsangebote bei dem Darlehensgeber einzuholen
                    und sämtlichen mit der Finanzierung zusammenhängenden Schriftverkehr
                    für mich entgegenzunehmen.
                </p>
                <p>
                    Der Darlehensgeber wird vor Herauslage des Darlehens bei der SCHUFA
                    Holding AG eine Auskunft einholen. Soweit nach Herauslage des Darlehens
                    solche Daten aus anderen Vertragsverhältnissen bei der SCHUFA anfallen,
                    kann der Darlehensgeber hierüber ebenfalls Auskünfte erhalten.
                </p>
            </td>
            <td>
                <p>
                    Hiermit bevollmächtige/n ich/wir die im Darlehensvermittlungsvertrag genannte
                    Firma ein Darlehensvertragsangebot des finanzierenden Darlehensgebers
                    zur Weiterleitung an mich entgegenzunehmen. Mir/Uns ist bekannt,
                    dass eine verbindliche Darlehenszusage nur von einem Darlehensgeber
                    selbst gegeben werden kann und dass Darlehenszusagen von Darlehensgebern
                    jederzeit widerrufen werden können, insbesondere wenn sich
                    Abweichungen zu den von mir gemachten Angaben herausstellen.
                </p>
                <p>
                    Insoweit befreie/n ich/wir den Darlehensgeber zugleich vom Bankgeheimnis.
                    Die SCHUFA speichert und nutzt die erhaltenen Daten. Die Nutzung
                    umfasst auch die Errechnung eines Wahrscheinlichkeitswertes auf
                    Grundlage des SCHUFA-Datenbestandes zur Beurteilung des Kreditrisikos
                    (Score). Die erhaltenen Daten übermittelt sie an ihre Vertragspartner im
                    Europäischen Wirtschaftsraum und der Schweiz, um diesen Informationen
                    zur Beurteilung der Kreditwürdigkeit von natürlichen Personen zu
                    geben.
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    Ich/Wir willige/n ein, dass der Darlehensgeber, der SCHUFA Holding AG,
                    Kormoranweg 5, 65201 Wiesbaden, Daten über die Beantragung, die
                    Aufnahme (Darlehensnehmer, ggf. auch Darlehensbetrag, Laufzeit, Ratenbeginn)
                    dieses grundpfandrechtlich gesicherten Darlehens sowie dessen
                    Rückzahlung übermittelt. Unabhängig davon wird der Darlehensgeber
                    der SCHUFA auch Daten über seine gegen mich/uns bestehenden fälligen
                    Forderungen übermitteln. Dies ist nach dem Bundesdatenschutzgesetz
                    (§28a Absatz 1 Satz 1) zulässig, wenn ich/wir die geschuldete Leistung trotz
                    Fälligkeit nicht erbracht habe/n, die Übermittlung zur Wahrung berechtigter
                    Interessen des Darlehensgebers oder Dritter erforderlich ist und
                </p>
                <ul class="default">
                    <li>
                        <p>
                            die Forderung vollstreckbar ist oder ich/wir die Forderung ausdrücklich
                            anerkannt habe/n oder
                        </p>
                    </li>
                    <li>
                        <p>
                            ich/wir nach Eintritt der Fälligkeit der Forderung mindestens zweimal
                            schriftlich gemahnt worden bin/sind, der Darlehensgeber mich/uns
                            rechtzeitig, jedoch frühestens bei der ersten Mahnung, über die bevorstehende
                            Übermittlung nach mindestens vier Wochen unterrichtet
                            hat und ich/wir die Forderung nicht bestritten habe/n oder
                        </p>
                    </li>
                    <li>
                        <p>
                            das der Forderung zugrunde liegende Vertragsverhältnis aufgrund
                            von Zahlungsrückständen durch den Darlehensgeber fristlos
                            gekündigt werden kann und der Darlehensgeber mich/uns über die
                            bevorstehende Übermittlung unterrichtet hat.
                        </p>
                    </li>
                </ul>
                <p>
                    Darüber hinaus wird der Darlehensgeber der SCHUFA auch Daten über
                    sonstiges nichtvertragsgemäßes Verhalten (z.B. betrügerisches Verhalten)
                    übermitteln. Diese Meldungen dürfen nach dem Bundesdatenschutzgesetz
                    (§28 Absatz 2) nur erfolgen, soweit dies zur Wahrung berechtigter Interessen
                    des Darlehensgebers oder Dritter erforderlich ist und kein Grund zu
                    der Annahme besteht, dass das schutzwürdige Interesse des Betroffenen
                    an dem Ausschluss der Übermittlung überwiegt.
                </p>
            </td>
            <td>
                <p>
                    Vertragspartner der SCHUFA sind Unternehmen, die aufgrund von
                    Leistungen oder Lieferung finanzielle Ausfallrisiken tragen (insbesondere
                    Kreditinstitute sowie Kreditkarten- und Leasinggesellschaften, aber auch
                    etwa Vermietungs-, Handels-, Telekommunikations-, Energieversorgungs-,
                    Versicherungs- und Inkassounternehmen).
                </p>
                <p>
                    Die SCHUFA stellt personenbezogene Daten nur zur Verfügung, wenn
                    ein berechtigtes Interesse hieran im Einzelfall glaubhaft dargelegt wurde
                    und die Übermittlung nach Abwägung aller Interessen zulässig ist. Daher
                    kann der Umfang der jeweils zur Verfügung gestellten Daten nach Art der
                    Vertragspartner unterschiedlich sein. Darüber hinaus nutzt die SCHUFA die
                    Daten zur Prüfung der Identität und des Alters von Personen auf Anfrage
                    ihrer Vertragspartner, die beispielsweise Dienstleistungen im Internet
                    anbieten. Ich kann/Wir können Auskunft bei der SCHUFA über die mich/uns
                    betreffenden gespeicherten Daten erhalten. Weitere Informationen über
                    das SCHUFA-Auskunfts- und Score-Verfahren sind unter www.meineschufa.
                    de abrufbar. Die Adresse der SCHUFA lautet: SCHUFA Holding AG, Privatkunden
                    ServiceCenter, Postfach 103441, 50474 Köln.
                </p>
                <p>
                    Als Freiberufler, Selbstständiger oder geschäftsführender Gesellschafter
                    willige/n ich/wir ein, dass der Darlehensgeber der Creditreform Frankfurt
                    Emil Vogt KG, Börsenplatz 7-11, 60313 Frankfurt am Main (im Folgenden
                    „Creditreform“), meine/unsere Daten (Name/n und Wohnanschrift) übermittelt,
                    um Bonitätsinformationen zur Prüfung meines/unseres Baufinanzierungsantrags
                    zu erhalten, die vom Darlehensgeber gespeichert werden.
                </p>
                <p>
                    Für die Übermittlung meiner/unserer Daten zwecks Bonitätsprüfung an die
                    Creditreform befreie ich/befreien wir den Darlehensgeber vom Bankgeheimnis.
                </p>
                <p>
                    Ich kann/Wir können Auskunft bei der Creditreform über die
                    mich/uns betreffenden gespeicherten Daten erhalten. Weitere Informationen
                    über das Creditreform-Auskunftsverfahren enthält die Homepage der
                    Creditreform unter {{url('/')}}.
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p>
                    Ich versichere/ wir versichern, dass gegen mich/uns bisher keine Zwangsmaßnahmen (z.B.
                    Gehaltspfändung, Zwangsversteigerung,Insolvenzverfahren)
                    eingeleitet wurden. Ich/Wir bin/sind meinen/unseren Zahlungsverpflichtungen in der Vergangenheit
                    immer ordnungsgemäß nachgekommen.
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p>
                    Ich handle/Wir handeln im eigenen wirtschaftlichen Interesse und nicht auf fremde Veranlassung
                    (insbesondere nicht als Treuhänder).
                    Ich/Wir versichere/versichern, alle vorstehenden Angaben nach bestem Wissen, vollständig und
                    wahrheitsgemäß gemacht zu haben. Falsche Angaben
                    können gegebenenfalls zu einer Vertragsaufhebung führen.
                </p>
            </td>
        </tr>
        </tbody>
    </table>
    <table>
        <thead>
        <tr>
            <th>Datenschutzrechtliche Einwilligungserklärung</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <p>
                    Ausschließlich zum Zwecke der Ermittlung von Darlehensangeboten und der Marktforschung willige/n
                    ich/wir ein, dass meine/unsere personenbezogenen
                    Daten durch den Anbieter oder einen vom Anbieter eingeschalteten Dienstleister erhoben werden, damit
                    sie dann unter Nutzung der elektronischen
                    Handelsplattform EUROPACE an potentielle Darlehensgeber übermittelt, verarbeitet und genutzt werden
                    können. Dabei sollen die personenbezogenen
                    Daten zum Zwecke der Marktforschung in anonymisierter Form genutzt und übermittelt werden. Diese
                    Einwilligung ist freiwillig und wird sofort wirksam.
                    Sie kann von mir/uns jederzeit schriftlich widerrufen werden.
                </p>
                <b>Einwilligung zur Datenübermittlung</b>
                <p>Ich bin/Wir sind damit einverstanden, dass der Darlehensgeber, der zuständige Vertriebspartner bzw.
                    die Vermittlungsagentur folgende Daten erheben
                    und sich gegenseitig übermitteln, sofern diese nicht bereits bekannt sind:</p>
                <ul class="default">
                    <li><p>sämtliche Antragsdaten</p></li>
                    <li>
                        <p>
                            Baufinanzierung: Produktart, Abschluss des Vertrages, erfüllte/noch zu erfüllende
                            Auszahlungsvoraussetzungen, Valutierung, Finanzierungsobjekt,
                            Saldo, Verzinsung, Laufzeit, Bearbeitungsstatus, Prolongations-Konditionen, inkl. Restschuld
                        </p>
                    </li>
                </ul>
                <p>In diesem Rahmen entbinde/n ich/wir den Darlehensgeber zugleich vom Bankgeheimnis. Mir/Uns ist
                    bekannt,
                    dass die Übermittlung der Informationen
                    vom Darlehensgeber an den Vertriebspartner bzw. die Vermittlungsagentur über eine sichere
                    Verbindung im
                    Internet, per Brief, Fax oder Telefon erfolgt
                    und der Begleitung des Vertragsverhältnisses durch den Vertriebspartner auch im Falle einer späteren
                    Prolongation sowie Prüfzwecken dient.</p>
                <b>Ermächtigung zur Einholung einer Bankauskunft</b>
                <p>
                    Ich/Wir willige(n) ein, dass meine/unsere Bank(en) dem finanzierenden Darlehensgeber auf dessen
                    Verlangen umfassend Auskunft über meine/unsere
                    wirtschaftlichen Verhältnisse erteilt/erteilen. Insoweit befreie ich/wir meine/unsere Bank(en)
                    zugleich
                    vom Bankgeheimnis.
                </p>
                <b>Grundbuchauskunft</b>
                <p>
                    Ich/Wir stimme(n) einer eventuellen Abfrage des automatisierten Grundbuch-Abrufverfahrens gemäß §133
                    GBO
                    zu. Diese Zustimmung umfasst auch eventuell
                    zu diesem Zweck eingeschaltete Dienstleistungsunternehmen (u.a. on-geo GmbH München, on-geo GmbH
                    Erfurt).
                </p>
                <b>Widerruf</b>
                <p>
                    Die vorstehenden Einwilligungserklärungen sind freiwillig und werden sofort wirksam. Ich kann/Wir
                    können
                    sie jederzeit für die Zukunft gegenüber dem
                    Darlehensgeber widerrufen.
                </p>
            </td>
        </tr>
        </tbody>
    </table>
    <table>
        <thead>
        <tr>
            <th>{{__('Date')}}/{{now()->format('d.m.Y')}}</th>
            <th>{{__('City')}}/___________________</th>
            <th>{{__('Sign')}}/___________________</th>
        </tr>
        </thead>
    </table>
</div>
</body>
</html>
