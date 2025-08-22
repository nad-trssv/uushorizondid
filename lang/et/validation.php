<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Valideerimise keelefail
    |--------------------------------------------------------------------------
    |
    | Järgnevad keelefaili read sisaldavad vaikimisi veateateid, mida
    | kasutatakse valideerimisklassis. Mõned reeglid omavad mitut versiooni,
    | näiteks suuruse reeglid. Võite neid teateid siin kohandada.
    |
    */

    'accepted' => ':attribute väli peab olema aktsepteeritud.',
    'accepted_if' => ':attribute väli peab olema aktsepteeritud, kui :other on :value.',
    'active_url' => ':attribute väli peab olema kehtiv URL.',
    'after' => ':attribute väli peab olema kuupäev pärast :date.',
    'after_or_equal' => ':attribute väli peab olema kuupäev, mis on pärast või võrdne :date.',
    'alpha' => ':attribute väli võib sisaldada ainult tähti.',
    'alpha_dash' => ':attribute väli võib sisaldada ainult tähti, numbreid, kriipse ja alakriipse.',
    'alpha_num' => ':attribute väli võib sisaldada ainult tähti ja numbreid.',
    'any_of' => ':attribute väli on vigane.',
    'array' => ':attribute väli peab olema massiiv.',
    'ascii' => ':attribute väli võib sisaldada ainult ühebaidiseid tähemärke ja sümboleid.',
    'before' => ':attribute väli peab olema kuupäev enne :date.',
    'before_or_equal' => ':attribute väli peab olema kuupäev, mis on enne või võrdne :date.',
    'between' => [
        'array' => ':attribute väli peab sisaldama :min kuni :max elementi.',
        'file' => ':attribute väli peab olema :min kuni :max kilobaiti.',
        'numeric' => ':attribute väli peab olema vahemikus :min kuni :max.',
        'string' => ':attribute väli peab olema :min kuni :max tähemärki.',
    ],
    'boolean' => ':attribute väli peab olema tõene või väär.',
    'can' => ':attribute väli sisaldab lubamatut väärtust.',
    'confirmed' => ':attribute väli kinnitus ei ühti.',
    'contains' => ':attribute väli puudub nõutud väärtus.',
    'current_password' => 'Parool on vale.',
    'date' => ':attribute väli peab olema kehtiv kuupäev.',
    'date_equals' => ':attribute väli peab olema kuupäev, mis on võrdne :date.',
    'date_format' => ':attribute väli peab vastama formaadile :format.',
    'decimal' => ':attribute väli peab sisaldama :decimal kümnendkohta.',
    'declined' => ':attribute väli peab olema tagasi lükatud.',
    'declined_if' => ':attribute väli peab olema tagasi lükatud, kui :other on :value.',
    'different' => ':attribute ja :other peavad olema erinevad.',
    'digits' => ':attribute väli peab olema :digits numbrit.',
    'digits_between' => ':attribute väli peab olema vahemikus :min kuni :max numbrit.',
    'dimensions' => ':attribute väli sisaldab vigaseid pildimõõtmeid.',
    'distinct' => ':attribute väli sisaldab duplikaatväärtust.',
    'doesnt_end_with' => ':attribute väli ei tohi lõppeda ühega järgmistest: :values.',
    'doesnt_start_with' => ':attribute väli ei tohi alata ühega järgmistest: :values.',
    'email' => ':attribute väli peab olema kehtiv e-posti aadress.',
    'ends_with' => ':attribute väli peab lõppema ühega järgmistest: :values.',
    'enum' => 'Valitud :attribute on vigane.',
    'exists' => 'Valitud :attribute on vigane.',
    'extensions' => ':attribute väli peab olema üks järgmistest laienditest: :values.',
    'file' => ':attribute väli peab olema fail.',
    'filled' => ':attribute väli peab sisaldama väärtust.',
    'gt' => [
        'array' => ':attribute väli peab sisaldama rohkem kui :value elementi.',
        'file' => ':attribute väli peab olema suurem kui :value kilobaiti.',
        'numeric' => ':attribute väli peab olema suurem kui :value.',
        'string' => ':attribute väli peab olema suurem kui :value tähemärki.',
    ],
    'gte' => [
        'array' => ':attribute väli peab sisaldama vähemalt :value elementi.',
        'file' => ':attribute väli peab olema vähemalt :value kilobaiti.',
        'numeric' => ':attribute väli peab olema vähemalt :value.',
        'string' => ':attribute väli peab olema vähemalt :value tähemärki.',
    ],
    'hex_color' => ':attribute väli peab olema kehtiv heksavärv.',
    'image' => ':attribute väli peab olema pilt.',
    'in' => 'Valitud :attribute on vigane.',
    'in_array' => ':attribute väli peab eksisteerima :other sees.',
    'integer' => ':attribute väli peab olema täisarv.',
    'ip' => ':attribute väli peab olema kehtiv IP-aadress.',
    'ipv4' => ':attribute väli peab olema kehtiv IPv4-aadress.',
    'ipv6' => ':attribute väli peab olema kehtiv IPv6-aadress.',
    'json' => ':attribute väli peab olema kehtiv JSON-string.',
    'list' => ':attribute väli peab olema loend.',
    'lowercase' => ':attribute väli peab olema väiketähtedega.',
    'lt' => [
        'array' => ':attribute väli peab sisaldama vähem kui :value elementi.',
        'file' => ':attribute väli peab olema väiksem kui :value kilobaiti.',
        'numeric' => ':attribute väli peab olema väiksem kui :value.',
        'string' => ':attribute väli peab olema väiksem kui :value tähemärki.',
    ],
    'lte' => [
        'array' => ':attribute väli ei tohi sisaldada rohkem kui :value elementi.',
        'file' => ':attribute väli peab olema väiksem või võrdne :value kilobaiti.',
        'numeric' => ':attribute väli peab olema väiksem või võrdne :value.',
        'string' => ':attribute väli peab olema väiksem või võrdne :value tähemärki.',
    ],
    'mac_address' => ':attribute väli peab olema kehtiv MAC-aadress.',
    'max' => [
        'array' => ':attribute väli ei tohi sisaldada rohkem kui :max elementi.',
        'file' => ':attribute väli ei tohi olla suurem kui :max kilobaiti.',
        'numeric' => ':attribute väli ei tohi olla suurem kui :max.',
        'string' => ':attribute väli ei tohi olla suurem kui :max tähemärki.',
    ],
    'max_digits' => ':attribute väli ei tohi sisaldada rohkem kui :max numbrit.',
    'mimes' => ':attribute väli peab olema fail tüübiga: :values.',
    'mimetypes' => ':attribute väli peab olema fail tüübiga: :values.',
    'min' => [
        'array' => ':attribute väli peab sisaldama vähemalt :min elementi.',
        'file' => ':attribute väli peab olema vähemalt :min kilobaiti.',
        'numeric' => ':attribute väli peab olema vähemalt :min.',
        'string' => ':attribute väli peab olema vähemalt :min tähemärki.',
    ],
    'min_digits' => ':attribute väli peab sisaldama vähemalt :min numbrit.',
    'missing' => ':attribute väli peab puuduma.',
    'missing_if' => ':attribute väli peab puuduma, kui :other on :value.',
    'missing_unless' => ':attribute väli peab puuduma, kui :other ei ole :value.',
    'missing_with' => ':attribute väli peab puuduma, kui :values on olemas.',
    'missing_with_all' => ':attribute väli peab puuduma, kui :values on olemas.',
    'multiple_of' => ':attribute väli peab olema :value kordne.',
    'not_in' => 'Valitud :attribute on vigane.',
    'not_regex' => ':attribute väli formaat on vigane.',
    'numeric' => ':attribute väli peab olema number.',
    'password' => [
        'letters' => ':attribute väli peab sisaldama vähemalt ühte tähte.',
        'mixed' => ':attribute väli peab sisaldama vähemalt ühte suurt ja ühte väikest tähte.',
        'numbers' => ':attribute väli peab sisaldama vähemalt ühte numbrit.',
        'symbols' => ':attribute väli peab sisaldama vähemalt ühte sümbolit.',
        'uncompromised' => 'Antud :attribute on andmelekkes ilmunud. Palun valige teine :attribute.',
    ],
    'present' => ':attribute väli peab olema olemas.',
    'present_if' => ':attribute väli peab olema olemas, kui :other on :value.',
    'present_unless' => ':attribute väli peab olema olemas, kui :other ei ole :value.',
    'present_with' => ':attribute väli peab olema olemas, kui :values on olemas.',
    'present_with_all' => ':attribute väli peab olema olemas, kui :values on olemas.',
    'prohibited' => ':attribute väli on keelatud.',
    'prohibited_if' => ':attribute väli on keelatud, kui :other on :value.',
    'prohibited_if_accepted' => ':attribute väli on keelatud, kui :other on aktsepteeritud.',
    'prohibited_if_declined' => ':attribute väli on keelatud, kui :other on tagasi lükatud.',
    'prohibited_unless' => ':attribute väli on keelatud, kui :other ei ole :values sees.',
    'prohibits' => ':attribute väli keelab :other olemasolu.',
    'regex' => ':attribute väli formaat on vigane.',
    'required' => ':attribute väli on kohustuslik.',
    'required_array_keys' => ':attribute väli peab sisaldama järgmisi võtmeid: :values.',
    'required_if' => ':attribute väli on kohustuslik, kui :other on :value.',
    'required_if_accepted' => ':attribute väli on kohustuslik, kui :other on aktsepteeritud.',
    'required_if_declined' => ':attribute väli on kohustuslik, kui :other on tagasi lükatud.',
    'required_unless' => ':attribute väli on kohustuslik, kui :other ei ole :values sees.',
    'required_with' => ':attribute väli on kohustuslik, kui :values on olemas.',
    'required_with_all' => ':attribute väli on kohustuslik, kui :values on olemas.',
    'required_without' => ':attribute väli on kohustuslik, kui :values ei ole olemas.',
    'required_without_all' => ':attribute väli on kohustuslik, kui ükski :values ei ole olemas.',
    'same' => ':attribute väli peab ühtima :other-ga.',
    'size' => [
        'array' => ':attribute väli peab sisaldama :size elementi.',
        'file' => ':attribute väli peab olema :size kilobaiti.',
        'numeric' => ':attribute väli peab olema :size.',
        'string' => ':attribute väli peab olema :size tähemärki.',
    ],
    'starts_with' => ':attribute väli peab algama ühega järgmistest: :values.',
    'string' => ':attribute väli peab olema string.',
    'timezone' => ':attribute väli peab olema kehtiv ajavöönd.',
    'unique' => ':attribute on juba kasutusel.',
    'uploaded' => ':attribute üleslaadimine ebaõnnestus.',
    'uppercase' => ':attribute väli peab olema suurtähtedega.',
    'url' => ':attribute väli peab olema kehtiv URL.',
    'ulid' => ':attribute väli peab olema kehtiv ULID.',
    'uuid' => ':attribute väli peab olema kehtiv UUID.',

    /*
    |--------------------------------------------------------------------------
    | Kohandatud valideerimise keelefaili read
    |--------------------------------------------------------------------------
    |
    | Siin saate määrata kohandatud valideerimissõnumeid, kasutades
    | konventsiooni "attribute.rule". See võimaldab kiiresti määrata
    | konkreetse valideerimissõnumi antud atribuudi reegli jaoks.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'kohandatud-sõnum',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Kohandatud valideerimise atribuudid
    |--------------------------------------------------------------------------
    |
    | Järgnevaid keelefaili ridu kasutatakse meie atribuudi kohatäite
    | asendamiseks millegi loetavamaga, näiteks "E-posti aadress" asemel
    | "email". See aitab muuta meie sõnumid väljendusrikkamaks.
    |
    */

    'attributes' => [],

];
