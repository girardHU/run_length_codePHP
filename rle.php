<?php
    $str_example1 = "WWWWWWWWWWWWBWWWWWWWWWWWWBBBWWWWWWWWWWWWWWWWWWWWWWWWBWWWWWWWWWWWWWW";
    $str_example2 = "12W1B12W3B24W1B14W";
    $str_example3 = "ffff ffff ffff 0909 ff4A 6BC0 D2ff ffff 0000 0000";
    $str_example4 = "06 ff 02 09 00 05 ff 4A 6B C0 D2 03 ff 04 00";

    function encode_rle($str) {
        $tempo_char = 0;
        $tempo_nb = 1;
        $result = "";
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

    function encode_advanced_rle($path_to_encode, $result_path) {
        $str = read_mbp_to_hex($path_to_encode);
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
            $unique_patterns = "";

            if ($first_couple == $next_couple) { // case same pattern
                while ($first_couple == $next_couple) {
                    $how_much++;
                    $i += 2;
                    $first_couple = substr($str, $i, 2);
                    $next_couple = substr($str, $i + 2, 2);
                }
                if ($how_much < 10)
                    $how_much = "0" . $how_much;
                $result .= $how_much . $first_couple;

            } else { // case unique pattern
                while($first_couple != $next_couple) {
                    $how_much++;
                    $unique_patterns .= $first_couple;
                    $i += 2;
                    $first_couple = substr($str, $i, 2);
                    $next_couple = substr($str, $i + 2, 2);
                }
                $how_much--;
                if ($how_much < 10)
                    $how_much = "0" . $how_much;
                $result .= "00" . $how_much . $unique_patterns;
                $i -= 2;
            }
        }
        create_and_write($result_path, $result);
        return 0;
    }

    function decode_advanced_rle($path_to_decode, $result_path) {
        // TODO gestion d'erreur complete
        $first_couple = "";
        $next_couple = "";
        $how_much = 0;
        $result = "";
        $compteur = 0;
        $handle = fopen($path_to_decode, "r");
        $str = fread($handle, filesize($path_to_decode));
        if (!ctype_alnum($str)) {
            echo "$$$";
            return "$$$";
        }

        for ($i = 0; $i < strlen($str); $i += 4) {
            $first_couple = substr($str, $i, 2);
            $next_couple = substr($str, $i + 2, 2);
            if (!ctype_digit($first_couple)) { // gestion err : pas un nombre
                echo $first_couple;
                echo "Houston, first_couple n'est pas un nombre\n";
                echo $result;
                return -1;
            } else if ($first_couple == "00") { // character de controle
                $compteur = 0;
                for($j = 0; $j < $next_couple; $j++) {
                    $result .= substr($str, $i + $j + 4 + $compteur, 2);
                    echo " attempt :" . $result . "\n";
                    $compteur += 1;
                }
                $i += $next_couple * 2;
            } else { // un nombre qui n'est pas 00
                $result .= str_repeat($next_couple, $first_couple);
            }
        }


    create_mbp_from_hex($result_path, $result);
    return 0;
    }

    function read_mbp_to_hex($path) {
        $string = file_get_contents($path);
        $output = "";
        for ($i = 0; $i < strlen($string); $i++) {
            $tempo_hex = strtoupper(dechex(ord(substr($string, $i, 1))));
            if (strlen($tempo_hex) == 1)
                $tempo_hex = "0" . $tempo_hex;
            $output .= $tempo_hex;
        }
        return $output;
    }

    function create_mbp_from_hex($path, $hex_str) {
        $str_chepaqoa = "";
        for ($i = 0; $i < strlen($hex_str); $i += 2) {
            $str_chepaqoa .= chr(hexdec(substr($hex_str, $i, 2)));
        }
        $image_ressource = imagecreatefromstring($str_chepaqoa);
        imagebmp($image_ressource, $path, NULL);
    }

    function create_and_write($file_name, $str_to_write) {
        $file = fopen($file_name, "w") or die("could not create the file");
        fwrite($file, $str_to_write);
        fclose($file);
    }

    function recup($path) {
        $handle = fopen($path, "r");
        $str = fread($handle, filesize($path));
        create_mbp_from_hex($path, $str);
        fclose($handle);
    }

    // encode_rle($str_example1);
    // decode_rle($str_example2);
    encode_advanced_rle("./src/toto.bmp", "./src/test");
    decode_advanced_rle("./src/test", "./src/test_decoded.bmp");
    // recup("./src/Super-Champignon.bmp");
    // create_mbp_from_hex('./src/SC_decode.bmp', read_mbp_to_hex("./src/Super-Champignon.bmp"));
    // read_mbp_to_hex("./src/Super-Champignon.bmp");
?>