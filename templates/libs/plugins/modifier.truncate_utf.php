<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty truncate modifier plugin
 *
 * Type:     modifier<br>
 * Name:     truncate<br>
 * Purpose:  Truncate a string to a certain length if necessary,
 *           optionally splitting in the middle of a word, and
 *           appending the $etc string or inserting $etc into the middle.
 * @link http://smarty.php.net/manual/en/language.modifier.truncate.php
 *          truncate (Smarty online manual)
 * @author   web <monte at ohrt dot com>
 * @param string
 * @param integer
 * @param string
 * @param boolean
 * @param boolean
 * @return string
 */
function smarty_modifier_truncate_utf($string, $length = 80, $etc = '...',
                                  $break_words = false, $middle = false)
{
    if ($length == 0)
        return '';

    $result = '';
    $string = html_entity_decode(trim($string), ENT_QUOTES, 'utf-8');

    for ( $i = 0, $j = 0; $i < strlen($string); $i++ )
    {
        if( $j >= $length )
        {
            for ( $x = 0, $y = 0; $x < strlen($etc); $x++ )
            {
                $number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0');
                if( $number )
                {
                    $x += $number - 1;
                    $y++;
                }
                else
                {
                    $y += 0.5;
                }
            }
            $length -= $y;
            break;
        }

        $number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0');
        if( $number )
        {
            $i += $number - 1;
            $j++;
        }
        else
        {
            $j += 0.5;
        }
    }

    for ( $i = 0; $i < strlen($string) && $length > 0; $i++ )
    {
        $number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0');
        if( $number > 0 )
        {
            if( $length < 1.0 )
            {
                break;
            }
            $result .= substr($string, $i, $number);
            $length -= 1.0;
            $i += $number - 1;
        }
        else
        {
            $result .= substr($string, $i, 1);
            $length -= 0.5;
        }
    }

    $result = htmlentities($result, ENT_QUOTES, 'utf-8');
    if( $i < strlen($string) )
    {
        $result .= $etc;
    }
    return $result;
}
?>
