<?php

function traslateDigitsToEN( $str )
{
    $number_dic = [
        '0' => [ "&#1776;", "۰", "٠" ],
        '1' => [ "&#1777;", "۱", "١" ],
        '2' => [ "&#1778;", "۲", "٢" ],
        '3' => [ "&#1779;", "۳", "٣" ],
        '4' => [ "&#1780;", "۴", "٤" ],
        '5' => [ "&#1781;", "۵", "٥" ],
        '6' => [ "&#1782;", "۶", "٦" ],
        '7' => [ "&#1783;", "۷", "٧" ],
        '8' => [ "&#1784;", "۸", "٨" ],
        '9' => [ "&#1785;", "۹", "٩" ],
    ];

    foreach( $number_dic as $number => $dic )
        $str = str_replace( $dic, $number, $str );

    return $str;
}

function sanitizeDashSeparatedInts( $val )
{
    $arr = explode( '-', $val );
    $csv = preg_replace(array('/[^\d,]/', '/(?<=,),+/', '/^,+/', '/,+$/'), '', implode( ',' , $arr ) );

    if ( strlen($csv) < 1 )
    {
        return false ;
    }

    return  str_replace( ',','-', $csv ) ;
}

function sanitizeStripTags( $val )
{
    return strip_tags( $val );
}

function sanitizeJdate( $val )
{
    $val = traslateDigitsToEN( $val );
    
    if ( preg_match( '/^[1-4]\d{3}\/((0[1-6]\/((3[0-1])|([1-2][0-9])|(0[1-9])))|((1[0-2]|(0[7-9]))\/(30|([1-2][0-9])|(0[1-9]))))$/', $val ) )
    {
        $jdate = explode( '/', $val );
        $jdate = array_map('intval' , $jdate );

        if ( count( $jdate ) != 3 )
        {
            return false ;
        }

        return array_combine( [ 'jyear', 'jmonth', 'jday' ], $jdate ) ;
    }

    return false ;
}

function sanitizeJtime($val)
{
    $val = traslateDigitsToEN( $val );

    if ( preg_match( '/(?:[01]\d|2[0-3]):(?:[0-5]\d):(?:[0-5]\d)/', $val ) )
    {
        $jtime = explode(':', $val);
        $jtime = array_map('intval', $jtime);

        if ( count($jtime) != 3 )
        {
            return false ;
        }

        return array_combine(['jhour', 'jminute', 'jsecond'], $jtime);
    }

    return false ;
}

function sanitizeDigits($str)
{
    return preg_replace( "/[^0-9]/", "", traslateDigitsToEN($str) );
}

function sanitizeAlphaNumeric($str)
{
    return preg_replace("/[^a-zA-Z0-9]/", "", traslateDigitsToEN($str));
}
?>