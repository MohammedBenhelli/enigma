<?php
function cipherMask(string $text, string $key): string
{
    $text = strtolower($text);
    $key = strtolower($key);
    $ret = "";
    if (strlen($text) > strlen($key))
        return "<span style='color: red'>Veuillez avoir une cle au moins aussi grande que le texte!</span>";
    else
        for ($i = 0; $i < strlen($text); $i++)
            $ret .= chr((((ord($text[$i]) - 97) + (ord($key[$i]) - 97)) % 26) + 97);
    return "<span>$ret</span>";
}

function decryptMask(string $text, string $key): string
{
    $text = strtolower($text);
    $key = strtolower($key);
    $ret = "";
    if (strlen($text) > strlen($key))
        return "<span style='color: red'>Veuillez avoir une cle au moins aussi grande que le texte!</span>";
    else
        for ($i = 0; $i < strlen($text); $i++)
            if (((ord($text[$i]) - 97) - (ord($key[$i]) - 97)) < 0)
                $ret .= chr((((ord($text[$i]) - 97) - (ord($key[$i]) - 97))) + 26 + 97);
            else $ret .= chr((((ord($text[$i]) - 97) - (ord($key[$i]) - 97))) + 97);
    return "<span>$ret</span>";
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Enigma</title>
</head>
<body>
<div class="caesar">
    <label>
        Code Caesar
        <select name="" id="caesar-select">
            <?php
            for ($i = 1; $i <= 26; $i++)
                echo "<option value='$i'>$i</option>"
            ?>
        </select>
        <input type="text" id="caesar-input">
    </label>
    <div id="caesar-result"></div>
</div>
<div class="vignere">
    <label for="vignere-key">
        Cle
        <input id="vignere-key">
    </label>
    <label>
        Texte
        <input type="text" id="vignere-input">
    </label>
    <div id="vigniere-result"></div>
    <button id="vigniere-decrypt">Dechiffrer</button>
    <div id="decrypt-result"></div>
</div>
<form action="enigma.php" class="mask" method="post">
    <label>
        Cle
        <input name="key" type="text">
    </label>
    <label>
        Texte
        <input name="text" type="text">
    </label>
    <button type="submit">Chiffrer</button>

    <?php
    if (isset($_POST['text']) && isset($_POST['key']))
        echo cipherMask($_POST['text'], $_POST['key']);
    ?>
</form>
<form action="enigma.php" class="mask" method="get">
    <label>
        Cle
        <input name="key" type="text">
    </label>
    <label>
        Texte
        <input name="text" type="text">
    </label>
    <button type="submit">Dechiffrer</button>

    <?php
    if (isset($_GET['text']) && isset($_GET['key']))
        echo decryptMask($_GET['text'], $_GET['key']);
    ?>
</form>
<script>
    const caesar = () => {
        let number = 1;
        const getDecalage = charCode => {
            if (charCode >= 65 && charCode <= 90)
                for (let i = 0; i < number; i++)
                    if (charCode === 90)
                        charCode = 65
                    else charCode += 1
            else if (charCode >= 97 && charCode <= 122)
                for (let i = 0; i < number; i++)
                    if (charCode === 122)
                        charCode = 97
                    else charCode += 1
            return charCode
        }
        const encrypt = string => string.split('').map(char => String.fromCharCode(getDecalage(char.charCodeAt(0)))).join('');
        document.getElementById('caesar-select').addEventListener('change', e => number = parseInt(e.target.value));
        document.getElementById('caesar-input').addEventListener('keyup', e => document.getElementById('caesar-result').innerText = encrypt(e.target.value));
    }
    caesar();

    const vigniere = () => {
        let key = '';
        const encrypt = string => {
            const ret = [];
            let keyCpy = key;
            if (key === '')
                return 'Entrez une cle!';
            while (keyCpy.length < string.length)
                keyCpy += keyCpy;
            for (let i = 0; i < string.length; i++)
                // if (string[i].charCodeAt(0) >= 65 && string[i].charCodeAt(0) <= 90)
                //     ret.push(String.fromCharCode(((string[i].charCodeAt(0) - 65 + keyCpy[i].charCodeAt(0) - 97) % 26) + 65));
                if (string[i].charCodeAt(0) >= 97 && string[i].charCodeAt(0) <= 122)
                    ret.push(String.fromCharCode(((string[i].charCodeAt(0) - 97 + keyCpy[i].charCodeAt(0) - 97) % 26) + 97));
                else ret.push(string[i])
            return ret.join('')
        }
        const decrypt = string => {
            const ret = [];
            let keyCpy = key;
            if (key === '')
                return 'Entrez une cle!';
            while (keyCpy.length < string.length)
                keyCpy += keyCpy;
            for (let i = 0; i < string.length; i++)
                // if (string[i].charCodeAt(0) >= 65 && string[i].charCodeAt(0) <= 90)
                //     ret.push(String.fromCharCode(((string[i].charCodeAt(0) - keyCpy[i].charCodeAt(0) + 26) % 26) + 65));
                if (string[i].charCodeAt(0) >= 97 && string[i].charCodeAt(0) <= 122)
                    ret.push(String.fromCharCode(((string[i].charCodeAt(0) - keyCpy[i].charCodeAt(0) + 26) % 26) + 97));
                else ret.push(string[i])
            return ret.join('')
        }
        document.getElementById('vignere-key').addEventListener('change', e => key = e.target.value.toLowerCase());
        document.getElementById('vignere-input').addEventListener('keyup', e => document.getElementById('vigniere-result').innerText = encrypt(e.target.value.toLowerCase()));
        document.getElementById('vigniere-decrypt').addEventListener('click', e => document.getElementById('decrypt-result').innerText = decrypt(document.getElementById('vigniere-result').innerText))
    }
    vigniere();
</script>
</body>
</html>
