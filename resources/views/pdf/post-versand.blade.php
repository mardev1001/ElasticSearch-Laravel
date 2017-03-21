<!DOCTYPE html>
<html lang="de">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@yield("title",'Post Versand')</title>
    <link rel="shortcut icon" href="/img/favicon.png">
    <style type="text/css">
        body, p, h1, h2, h3, h4, h5 {
            font-family: 'Helvetica', 'Arial', sans-serif !important;
        }

        p {
            font-size: 14px;
            margin-bottom: 25px;
        }

        .header,
        .footer {
            width: 100%;
            position: fixed;
        }

        .header {
            top: -15px;
            margin-bottom: 50px;
        }

        .div-pusher {
            width: 50%;
            padding-left: 30%;
        }

        .header .div-pusher {
            width: 60%;
            padding-left: 30%;
        }

        .header .image-div {
            width: 40%;
            float: right !important;
            padding-left: 50px;
            height: auto;
        }

        .header .image-div img {
            margin-left: 0px;
            width: 100%;
            height: auto;
            display: block;
        }

        table {
            width: 100%;
            border: 1px solid #d6d6d6;
        }

        table td, table th {
            text-align: center;
            border: 1px solid #d3d3d3;
            padding: 5px;
        }


    </style>
</head>

<body>

<div id="content">

    <h2>Liste aller Post Versand Personen </h2>


    <div class="table-container">
        <table class="table">
            <thead>
            <tr>
                <th class="text-center valign">{{ trans('documentForm.mandants') }}</th>
                <th class="text-center valign">{{ trans('documentForm.name') }}</th>
                <th class="text-center valign">{{ trans('documentForm.adress') }}</th>
            </tr>
            </thead>
            <tbody>
                @if(count($mandants))
                    @foreach($mandants as $mandant)
                        @if(count($mandant->users))
                            @foreach($mandant->users as $user)
                                @if($users->contains($user))
                                    <tr>
                                        <td class="text-center valign">
                                            {{ $mandant->number ." ". $mandant->kurzname }}
                                        </td>
                                        <td class="text-center valign">
                                            {{ $user->title ." ". $user->first_name ." ". $user->last_name }}
                                        </td>
                                        <td class="text-center valigns">
                                            {{ $mandant->strasse .' ' }}
                                            {{ $mandant->hausnummer .' ' }}
                                            {{ $mandant->plz .' ' }}
                                            {{ $mandant->ort .' ' }}
                                            {{ $mandant->bundesland .' ' }}
                                            {{ $mandant->adreszusatz .' ' }}
                                            {{ $mandant->strasse }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td class="valign" colspan="3">Keine Daten vorhanden.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    
</div>
<div style="clear:both; margin-bottom: 30px;"></div>

</body>

</html>