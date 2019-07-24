<?php
    $str_example1 = "WWWWWWWWWWWWBWWWWWWWWWWWWBBBWWWWWWWWWWWWWWWWWWWWWWWWBWWWWWWWWWWWWWW";
    $str_example2 = "12W1B12W3B24W1B14W";
    $str_example3 = "ffff ffff ffff 0909 ff4A 6BC0 D2ff ffff 0000 0000";
    $str_example4 = "06 ff 02 09 00 05 ff 4A 6B C0 D2 03 ff 04 00";

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
        // TODO gestion d'erreur complete
        if (!ctype_xdigit($str)) {
            echo "$$$";
            return "$$$";
        }
        $first_couple = "";
        $next_couple = "";
        $how_much = 0;
        $unique_patterns = "";
        $result = "";

        for ($i = 0; $i < strlen($str); $i += 2) {
            $first_couple = substr($str, $i, 2);
            $next_couple = substr($str, $i + 2, 2);
            $how_much = 1;
            if ($first_couple == $next_couple) { // case same pattern
                while ($first_couple == $next_couple) {
                    $how_much++;
                    $i += 2;
                    $first_couple = substr($str, $i, 2);
                    $next_couple = substr($str, $i + 2, 2);
                }
                if ($how_much < 10)
                    $how_much = "0" . $how_much;
                $result = $result . $how_much . " " . $first_couple. " ";
            } else { // case unique pattern
                while($first_couple != $next_couple) {
                    $how_much++;
                    $unique_patterns = $unique_patterns . " " . $first_couple;
                    $i += 2;
                    $first_couple = substr($str, $i, 2);
                    $next_couple = substr($str, $i + 2, 2);
                }
                $how_much--;
                if ($how_much < 10)
                    $how_much = "0" . $how_much;
                $result = $result . "00 " . $how_much . $unique_patterns . " ";
                $i -= 2;
            }
        }
        echo $result;
        return $result;
    }

    function decode_advanced_rle($str) {
        echo "string is :" . $str . "\n";
        $str = str_replace(" ", "", $str);
        // TODO gestion d'erreur complete
        if (!ctype_alnum($str)) {
            echo "$$$";
            return "$$$";
        }
        $first_couple = "";
        $next_couple = "";
        $how_much = 0;
        $result = "";

        for ($i = 0; $i < strlen($str); $i += 4) {
            $first_couple = substr($str, $i, 2);
            $next_couple = substr($str, $i + 2, 2);
            // echo $next_couple;
            // echo "\n";
            if (!ctype_digit($first_couple)) { // gestion err : pas un nombre
                // echo $first_couple;
                // echo "\n";
                // echo gettype($first_couple);
                echo "Houston, first_couple n'est pas un nombre\n";
                return -1;
            } else if ($first_couple == "00") { // character de controle
                for($j = 0; $j < $next_couple; $j++) {
                    $result = $result . substr($str, $i + $j, 2);
                    echo " attempt :" . $result . "\n";
                }
                echo "j'ai juste pas encore gerer le char de controle hehe\n";
                return -1;
            } else { // un nombre qui n'est pas 00
                $result = $result . str_repeat($next_couple, $first_couple);
                echo " resultat :" . $result . "\n";
            }
        }


        echo $result;
        return $result;
    }

    function read_mbp_to_hex($path) {
        $string = file_get_contents($path);
        $output = "";
        for ($i = 0; $i < strlen($string); $i++) {
            $output .= dechex(ord(substr($string, $i, 1)));
        }
        echo $output;
        return 0;
    }

    // encode_rle($str_example1);
    // decode_rle($str_example2);
    // encode_advanced_rle($str_example3);
    read_mbp_to_hex("./src/Super-Champignon.bmp");
    // decode_advanced_rle($str_example4);
    // read_mbp_to_hex("./src/Super-Champignon.bmp");
?>