<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MoneyController extends Controller
{
    public function convert(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:0', 'max:999999.99'],
        ]);

        $amount = (float) $request->query('amount');

        $dollars = (int) floor($amount);
        $cents   = (int) round(($amount - $dollars) * 100);

        $dollarWords = $this->numberToWords($dollars);
        $centWords   = $this->numberToWords($cents);

        $result = trim(
            ($dollarWords === 'zero' ? 'zero' : $dollarWords) .
            ' dollar' . ($dollars == 1 ? '' : 's') .
            ' and ' .
            ($cents === 0 ? 'zero' : $centWords) .
            ' cent' . ($cents == 1 ? '' : 's')
        );

        return response()->json([
            'amount'        => $amount,
            'dollars'       => $dollars,
            'cents'         => $cents,
            'amount_in_words' => $result,
        ]);
    }

    /**
     * Convert integer 0–999999 to English words.
     */
    private function numberToWords(int $number): string
    {
        if ($number === 0) {
            return 'zero';
        }

        if ($number < 0 || $number > 999999) {
            return (string) $number;
        }

        $ones = [
            '', 'one', 'two', 'three', 'four', 'five', 'six',
            'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve',
            'thirteen', 'fourteen', 'fifteen', 'sixteen',
            'seventeen', 'eighteen', 'nineteen'
        ];

        $tens = [
            '', '', 'twenty', 'thirty', 'forty', 'fifty',
            'sixty', 'seventy', 'eighty', 'ninety'
        ];

        $words = [];

        $thousands = intdiv($number, 1000);
        $remainder = $number % 1000;

        if ($thousands > 0) {
            $words[] = $this->threeDigitToWords($thousands, $ones, $tens) . ' thousand';
        }

        if ($remainder > 0) {
            $words[] = $this->threeDigitToWords($remainder, $ones, $tens);
        }

        return trim(implode(' ', $words));
    }

    private function threeDigitToWords(int $number, array $ones, array $tens): string
    {
        $parts = [];

        $hundreds = intdiv($number, 100);
        $remainder = $number % 100;

        if ($hundreds > 0) {
            $parts[] = $ones[$hundreds] . ' hundred';
        }

        if ($remainder > 0) {
            if ($remainder < 20) {
                $parts[] = $ones[$remainder];
            } else {
                $t = intdiv($remainder, 10);
                $u = $remainder % 10;

                if ($u > 0) {
                    $parts[] = $tens[$t] . '-' . $ones[$u];
                } else {
                    $parts[] = $tens[$t];
                }
            }
        }

        return implode(' ', $parts);
    }
}
