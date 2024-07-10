<?php

use App\Models\Category;
use Illuminate\Support\Str;
use Rakibhstu\Banglanumber\NumberToBangla;

function getAllCategories()
{
    return Category::get(['id', 'name', 'icon'])->toArray();
}

function parentCategory()
{
    return Category::whereNull('parent_id')->get();
}

function numberToBangla($value, $word = false, $onlyWord = false)
{
    if ($value === null) {
        return $value;
    }

    $numto = new NumberToBangla();

    if ($onlyWord) {
        return $numto->bnWord($value).' টাকা';
    }

    $amount = $numto->bnCommaLakh($value);

    if ($word) {
        $amount .= ' ('.$numto->bnWord($value).') ';
    }

    return ' ৳ '.$amount;
}

function toCurrency($value = 0, $word = false)
{
    // todo: need to dynamic the currency sign
    return numberToBangla($value, $word);
    // return number_format($value, 2, '.', ',') . ' ৳';
}

function isManager()
{
    return auth()->user()->hasRole('manager');
}

function getSchemeName()
{
    return [
        'deposit',
        'expanse',
        'income',
    ];
}

function processValues($input, $str = '', $link = '')
{
    // Check if the input contains a comma
    if (Str::contains($input, ',')) {
        // If it contains a comma, return the count of values
        $values = explode(',', $input);
        $values = array_unique($values);

        $text = count($values) > 1 ? count($values).$str : $str;
        if ($link != '') {
            return '<a href="'.$link.'">'.$text.'</a>';
        }

        return $text;
    } else {
        // If it doesn't contain a comma, return the single value
        return $input;
    }
}

function dateFormat($date, $format = 'Y-m-d')
{
    return (new DateTime($date))->format($format);
}

function sumByStatus($modelClass, $status)
{
    return $modelClass::where('status', $status)->sum('amount');
}
