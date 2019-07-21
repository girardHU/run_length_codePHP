<?php
    $str_example1 = "WWWWWWWWWWWWBWWWWWWWWWWWWBBBWWWWWWWWWWWWWWWWWWWWWWWWBWWWWWWWWWWWWWW";
    $str_example2 = "12W1B12W3B24W1B14W";
    $str_example3 = "ffff ffff ffff 0909 ff4A 6BC0 D2ff ffff 0000 0000";

    function encode_rle($str) {
        $tempo_char = 0;
        $tempo_nb = 1;
        $result = "";
        // TODO gestion d'erreur complete
        if ($str == null || !ctype_alpha($str))
            return "$$$";

        for ($i = 0; $i < strlen($str); $i++) {
            $tempo_char = substr($str, $i, 1);
            if (substr($str, $i + 1, 1) != $tempo_char) {
                $result = $result . $tempo_nb . $tempo_char;
                $tempo_nb = 1;
            } else {
                $tempo_nb++;
            }
        }
        return $result;
    }

    function decode_rle($str) {
        $result = "";
        // TODO gestion d'erreur complete (if 2 alpha follow each other)
        if ($str == null || !ctype_alnum($str))
        return "$$$";
        for ($i = 0; $i < strlen($str); $i++) {
            $compteur = 0;
            while (ctype_digit(substr($str, $i + $compteur, 1))) {
                $compteur++;
            }
            $HM_to_concat = intval(substr($str, $i, $compteur));
            $result = $result . str_repeat(substr($str, $i + $compteur, 1), $HM_to_concat);
            $i += $compteur;
        }
        return $result;
    }

    function encode_advanced_rle($str) {
        $str = str_replace(" ", "", $str);
        if (!ctype_xdigit($str)) {
            echo "$$$";
            return "$$$";
        }
        $first_couple = "";
        $next_couple = "";
        $how_much = 0;
        $result = "";

        for ($i = 0; $i < strlen($str); $i += 2) {
            $first_couple = substr($str, $i, 2);
            $next_couple = substr($str, $i + 2, 2);
            $how_much = 1;
            if ($first_couple == $next_couple) { // case 01
                while ($first_couple == $next_couple) {
                    $how_much++;
                    $i += 2;
                    $first_couple = substr($str, $i, 2);
                    $next_couple = substr($str, $i + 2, 2);
                }
                $result = $result . $how_much . " " . $first_couple. " ";
            } else { // case 02
                while($first_couple != $next_couple) {
                    
                }
            }
        }
        echo $result;
    }

    // function read_mbp_to_hex($path) {
    //     fopen($path);
    // }

    // encode_rle($str_example1);
    // decode_rle($str_example2);
    encode_advanced_rle($str_example3);
    // read_mbp_to_hex("./src/Super-Champignon.bmp");
?>