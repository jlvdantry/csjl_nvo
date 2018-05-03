<?php
class NumberConverter
{
/**
* Error codes.
*
* @var array
*/
private static $error_codes = array( 
101 => 'Error: Please insert a valid number.',
102 => 'Error: 2nd parameter is required. Ex. convert(21,R).',
103 => 'Error: Invalid 2nd parameter. Use R for Roman, W for Words and O for Ordinal numbers.',
104 => 'Error: Only integers from 1 to 3999 are convertible into Roman.',
105 => 'Error: Word converter accepts numbers between -',
106 => 'Error: Numeros negativos no puede convertirse a ordinarios.'
);
/**
* Covert provided number to requested type.
*
* @param string $number
* @param string $type
*
* @return string
*/
public function convert($number = '', $type = '')
{
if (strlen($type) > 1) {
    $type = $type[0];
}
$type = strtolower($type);
if (($error_code = $this->checkParamaters($number, $type)) > 0) {
            return self::$error_codes[$error_code];
        }
        switch ($type) {
            case 'w':
                return $this->convertToWord($number);
            case 'o':
                return $this->convertToWord($number);
            case 'p':
                return $this->convertToWord($number,$type);
            case 'n':
                return $this->convertToOrdinal($number);
            case 'r':
                return $this->convertToRoman($number);
        }
    }
    /**
     * Check provided paramaters.
     *
     * @param int    $number
     * @param string $type
     *
     * @return int
     */
    private function checkParamaters($number, $type)
    {
        if ($number === '' || !is_numeric($number)) {
            return 101;
        }
        if ($type === '') {
            return 102;
        }
        if (!in_array($type, array('w', 'o', 'n', 'r','p'))) {
            return 103;
        }
        if ($type == 'r' && (!is_int($number) || $number < 1 || $number > 3999)) {
            return 104;
        }
        if ($number > PHP_INT_MAX || $number < 0 - PHP_INT_MAX) {
            return 105;
        }
        if ($type == 'o' && $number < 0) {
            return 106;
        }
        return 0;
    }
    /**
     * Convert number to an ordinal (numerals and letter suffixes).
     *
     * @param int $number
     *
     * @return string
     */
    private function convertToNumberOrdinal($number)
    {
        if (!in_array(($number % 100), array(11, 12, 13))) {
            switch ($number % 10) {
                case 1:
                    return $number.'st';
                case 2:
                    return $number.'nd';
                case 3:
                    return $number.'rd';
            }
        }
        return $number.'th';
    }
    /**
     * Convert number to an ordinal (letters only).
     *
     * @param int $number
     *
     * @return string
     */
    private function convertToOrdinal($number,$type = '')
    {
        $under_ten = (int) substr($number, strlen($number) - 1);
        $over_ten = (int) ($number - $under_ten);
        $string = '';
        echo "under_ten=".$under_ten." over_ten=".$over_ten;
        if ($over_ten > 0) {
            $string = $this->convertToWord($over_ten);
        }
        switch ($under_ten) {
            case 1:
                $under_ten_string = ($type=='p' ? 'primer' : 'primero');
                break;
            case 2:
                $under_ten_string = 'segundo';
                break;
            case 3:
                $under_ten_string = 'tercer';
                break;
            case 4:
                $under_ten_string = 'cuarto';
                break;
            case 5:
                $under_ten_string = 'quinto';
                break;
            case 6:
                $under_ten_string = 'sexto';
                break;
            case 7:
                $under_ten_string = 'septimo';
                break;
            case 8:
                $under_ten_string = 'octavo';
                break;
            case 9:
                $under_ten_string = 'noveno';
                break;
        }
        if ($under_ten > 0) {
            $string .= ($string != '') ? ' ' : '';
            $string .= $under_ten_string;
        }
        return $string;
    }
    /**
     * Convert number to a roman numeral.
     *
     * @param int $number
     *
     * @return string
     */
    private function convertToRoman($number)
    {
        $roman_number = '';
        while ($number >= 1000) {
            $roman_number .= 'M';
            $number -= 1000;
        }
        while ($number >= 900) {
            $roman_number .= 'CM';
            $number -= 900;
        }
        while ($number >= 500) {
            $roman_number .= 'D';
            $number -= 500;
        }
        while ($number >= 400) {
            $roman_number .= 'CD';
            $number -= 400;
        }
        while ($number >= 100) {
            $roman_number .= 'C';
            $number -= 100;
        }
        while ($number >= 90) {
            $roman_number .= 'XC';
            $number -= 90;
        }
        while ($number >= 50) {
            $roman_number .= 'L';
            $number -= 50;
        }
        while ($number >= 40) {
            $roman_number .= 'XL';
            $number -= 40;
        }
        while ($number >= 10) {
            $roman_number .= 'X';
            $number -= 10;
        }
        while ($number >= 9) {
            $roman_number .= 'IX';
            $number -= 9;
        }
        while ($number >= 5) {
            $roman_number .= 'V';
            $number -= 5;
        }
        while ($number >= 4) {
            $roman_number .= 'IV';
            $number -= 4;
        }
        while ($number >= 1) {
            $roman_number .= 'I';
            $number -= 1;
        }
        return $roman_number;
    }
    /**
     * Convert number to a word.
     *
     * @param int $number
     *
     * @return string
     */
    private function convertToWord($number,$type = '')
    {
        ##$hyphen = '-';
        $hyphen = ' ';
        $conjunction = ' ';
        $separator = ', ';
        $negative = 'negative ';
        $decimal = ' point ';
        $dictionary = array( 
            0                   => '',
            1                   => ($type=='p' ? 'primer' : 'primero'),
            2                   => 'segundo',
            3                   => 'tercer',
            4                   => 'cuarto',
            5                   => 'quinto',
            6                   => 'sexto',
            7                   => 'septimo',
            8                   => 'octavo',
            9                   => 'noveno',
            10                  => 'decimo',
            11                  => 'undecimo',
            12                  => 'duodecimo',
            13                  => 'treceavo',
            14                  => 'cuatroceavo',
            15                  => 'quinceavo',
            16                  => 'seisceavo',
            17                  => 'sieteceavo',
            18                  => 'ochoceavo',
            19                  => 'noveceavo',
            20                  => 'vigesimo',
            30                  => 'trigesimo',
            40                  => 'cuadragesimo',
            50                  => 'quincugesimo',
            60                  => 'sexagesimo',
            70                  => 'septuagesimo',
            80                  => 'octogesimo',
            90                  => 'nonagesimo',
            100                 => 'centesimo',
            200                 => 'ducentesimo',
            300                 => 'tricentesimo',
            400                 => 'cuadrigentesimo',
            500                 => 'quigentesimo',
            600                 => 'sexcentesimo',
            700                 => 'septingentesimo',
            800                 => 'octingentesimo',
            900                 => 'noningentesimo',
            1000                => 'milesimo',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion',
        );
        if ($number < 0) {
            return $negative.$this->convertToWord(abs($number));
        }
        $string = $fraction = null;
        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }
        switch (true) {
            case $number < 11:
                //echo "vas ".$number;
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int) ($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen.$dictionary[$units];
                }
                break;
            case $number < 1000 :
                //echo "entro number".$number;
                $hundreds = ((int) ($number / 100)) * 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds];
                //echo "entro number".$number." hundreds=".$hundreds;
                if ($remainder) {
                    //echo " remainder=".$remainder." number=".$number." string=".$string;
                    $string .= " ".$this->convertToWord($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convertToWord($numBaseUnits).' '.$dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convertToWord($remainder);
                }
                break;
        }
        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }
        return htmlspecialchars($string);
    }
}
?>
