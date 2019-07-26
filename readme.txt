Le programme ne contient pas de valeurs test, donc il faut appeler les fonctions (elles ont le meme prototype que dans la consigne).

encode_rle("WWWWWWWWWWWWBWWWWWWWWWWWWBBBWWWWWWWWWWWWWWWWWWWWWWWWBWWWWWWWWWWWWWW") retourne "12W1B12W3B24W1B14W"
decode_rle("12W1B12W3B24W1B14W") retourne "WWWWWWWWWWWWBWWWWWWWWWWWWBBBWWWWWWWWWWWWWWWWWWWWWWWWBWWWWWWWWWWWWWW"
encode_advnced_rle(```BMP file traduit comme cela```"ffffffffffff0909ff4A6BC0D2ffffff0000000") retourne "06ff02090005ff4A6BC0D203ff0400"
encode(```file contenant```"06ff02090005ff4A6BC0D203ff0400") retourne "ffffffffffff0909ff4A6BC0D2ffffff0000000"