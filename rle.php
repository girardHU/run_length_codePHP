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
        // TODO check if number to print is greater than 99 ```TESTED```
        // TODO check if an alpha is here without number ```TESTED```
        // TODO check if the strings doesnt end with an alpha ```TESTED```
        if ($str == null || !ctype_alnum($str))
        return "$$$";
        for ($i = 0; $i < strlen($str); $i++) {
            $compteur = 0;
            if (ctype_alpha(substr($str, $i + $compteur, 1)))
                return "$$$";
            while (ctype_digit(substr($str, $i + $compteur, 1))) {
                $compteur++;
            }
            $HM_to_concat = intval(substr($str, $i, $compteur));
            $result = $result . str_repeat(substr($str, $i + $compteur, 1), $HM_to_concat);
            $i += $compteur;
        }
        if (ctype_digit(substr($str, -1, 1)))
            return "$$$";
        return $result;
    }

    function encode_advanced_rle($path_to_encode, $result_path) {
        $str = read_mbp_to_hex($path_to_encode);
        // TODO gestion d'erreur complete
        if (!ctype_xdigit($str) || filetype($path_to_encode) !== "file" || exif_imagetype($path_to_encode) != IMAGETYPE_BMP) {
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
        return $result_path;
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
        if (!ctype_alnum($str))
            return "$$$";

        for ($i = 0; $i < strlen($str); $i += 4) {
            $first_couple = substr($str, $i, 2);
            $next_couple = substr($str, $i + 2, 2);
            if (!ctype_digit($first_couple)) // gestion err : pas un nombre
                return "$$$";
            if ($first_couple == "00") { // character de controle
                $compteur = 0;
                for($j = 0; $j < $next_couple; $j++) {
                    $result .= substr($str, $i + $j + 4 + $compteur, 2);
                    $compteur += 1;
                }
                $i += $next_couple * 2;
            } else // un nombre qui n'est pas 00
                $result .= str_repeat($next_couple, $first_couple);
        }

        create_mbp_from_hex($result_path, $result);
        return $result_path;
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
        $str = "";
        for ($i = 0; $i < strlen($hex_str); $i += 2) {
            $str .= chr(hexdec(substr($hex_str, $i, 2)));
        }
        $image_ressource = imagecreatefromstring($str);
        imagebmp($image_ressource, $path, NULL);
    }

    function create_and_write($file_name, $str_to_write) {
        $file = fopen($file_name, "w") or die("could not create the file");
        fwrite($file, $str_to_write);
        fclose($file);
    }

    // echo encode_rle($str_example1);
    // echo decode_rle($str_example2);
    // echo encode_advanced_rle("./src/image.bmp", "./src/image_compiled");
    // echo decode_advanced_rle("./src/image_compiled", "./src/test_decoded.bmp");
?>