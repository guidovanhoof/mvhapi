<?php

namespace App\Helpers;

const STORE_MODE = false;
const UPDATE_MODE = true;

function verwijderAccenten($tekst)
{
    return
        str_replace(
            [
                "á", "é", "ý", "ú", "í", "ó",
                "à", "è", "ù", "ì", "ò",
                "â", "ê", "û", "î", "ô",
                "^", "´", "`",
                "¨", "ä", "ë", "ÿ", "ü", "ï", "ö",
                "~", "ã", "õ", "ñ",
                "ç",
            ],
            [
                "a", "e", "y", "u", "i", "o",
                "a", "e", "u", "i", "o",
                "a", "e", "u", "i", "o",
                "", "", "",
                "", "a", "e", "y", "u", "i", "o",
                "", "a", "o", "n",
                "c",
            ],
            $tekst
        );
}
