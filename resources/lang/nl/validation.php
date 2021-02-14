<?php

return [
    'required' => ':attribute is verplicht!',
    'unique' => ':attribute bestaat reeds!',
    'email' => ':attribute moet een geldig email adres zijn!',
    'exists' => ':attribute niet gevonden!',
    'date' => ':attribute is geen geldige datum!',
    'numeric' => ':attribute is niet numeriek!',
    'date_format' => ':attribute is geen geldig tijdstip!',
    'between' => [
        'numeric' => ':attribute moet liggen tussen :min en :max!',
    ],
    'gte' => [
        'numeric' => ':attribute moet groter of gelijk aan :value zijn!',
    ],
    'gt' => [
        'numeric' => ':attribute moet groter dan :value zijn!',
    ],
    'boolean' => ':attribute moet 1 of true (voor ja) of 0 of false (voor nee) zijn!',
    'datum_in_kalender_jaar' => 'Datum niet in kalenderjaar!',
    'nummer_uniek_per_wedstrijd' => 'Nummer bestaat reeds voor wedstrijd!',
    'nummer_uniek_per_reeks' => 'Nummer bestaat reeds voor reeks!',
    'attributes' => [
        'jaar' => 'Jaar',
        'email' => 'Email',
        'password' => 'Wachtwoord',
        'omschrijving' => 'Omschrijving',
        'kalender_id' => 'Kalender_id',
        'datum' => 'Datum',
        'nummer' => 'Nummer',
        'aanvang' => 'Aanvang',
        'wedstrijdtype_id' => 'Wedstrijdtype_id',
        'wedstrijd_id' => 'Wedstrijd_id',
        'duur' => 'Duur',
        'gewicht_zak' => 'Gewicht zak',
        'reeks_id' => 'Reeks_id',
        'plaats_id' => 'Plaats_id',
        'gewicht' => 'Gewicht',
        'is_geldig' => 'Geldigheid',
    ],
];
