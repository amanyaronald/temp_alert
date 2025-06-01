@props([
    'compaire' => false,
    'isBool' => false,
    'isIcon' => false,
    'isCurrency' => false,
    'currency_type' => 'UGX',
    'currencyLocale' => 'before', #en = BEFORE | #de = AFTER
])

<p>
    @php
        #HANDLE BEFORE & AFTER CURRENCY
        $currencyLocale = strtolower($currencyLocale) == 'before' ? 'en' : 'de';

        $formatCurrency = function ($value) use ($isCurrency, $currency_type, $currencyLocale) {
            if (!$isCurrency) {
                return $value ?? '--';
            }
            return Number::currency($value, in: $currency_type, locale: $currencyLocale);
        };
    @endphp

    @if ($compaire['is_update'])
        <span class="text-{{ $compaire['is_compairable'] ? 'primary' : '' }}">
            {{ $formatCurrency($compaire['old']) }}
        </span>
        <br>
        @if ($compaire['is_compairable'])
            <span class="text-danger">
                {{ $isBool ? bool_output($compaire['new']) : $formatCurrency($compaire['new']) }}
            </span>
        @endif
    @else
        <span>
            {{ $isBool ? bool_output($compaire['old']) : $formatCurrency($compaire['old']) }}
        </span>
    @endif

    @if ($isIcon)
        <span>
            <i class="{{ $compaire['old'] ?? '--' }} h3"></i>
        </span>
    @endif
</p>
