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
            if ($tempo_char)
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
        // $compteur = 1;
        $result = "";
        // TODO gestion d'erreur complete
        if ($str == null)
        return "$$$";
        for ($i = 0; $i < strlen($str); $i++) {
            $compteur = 0;
            // echo $i + $compteur." ";
            // echo (substr($str, $i + $compteur, 1))." ";
            // echo (ctype_digit(substr($str, $i + $compteur, 1)));
            while (ctype_digit(substr($str, $i + $compteur, 1))) {
                $compteur++;
                // echo (substr($str, $i + $compteur, 1));
                // echo "ici";
                // echo $compteur." ";
            }
            // $compteur--;
            // echo $compteur." ";
            // $compteur++;
            // echo $compteur." ";
            // $compteur--;
            // echo intval(substr($str, $i, $compteur + 1));
            $HM_to_concat = intval(substr($str, $i, $compteur));
            // echo $HM_to_concat;
            // return;
            $result = $result . str_repeat(substr($str, $i + $compteur, 1), $HM_to_concat);
            $i += $compteur;
            // echo "\n".$HM_to_concat."\n";
            // for ($HM_to_concat; $HM_to_concat > 0; $HM_to_concat--) {
                // echo $result;
                // $result = $result . substr($str, $i + 1, 1);
            // }
        }
        // echo $result;
        return $result;
    }

    function encode_advanced_rle($str) {
        if (!ctype_xdigit($str)) {
            echo "$$$";
            return "$$$";
        }
        $first_couple = "";
        $next_couple = "";
        $how_much = 0;

        for ($i = 0; $i < strlen($str); $i += 2) {
            $first_couple = substr($str, $i, 2);
            $next_couple = substr($str, $i + 2, 2);
            $how_much = 1;
            if ($first_couple == $next_couple) {
                $how_much++;
            } else {
                while($first_couple != $next_couple) {
                    
                }
            }
        }
    }

    encode_rle($str_example1);
    decode_rle($str_example2);
    encode_advanced_rle($str_example3);
?>