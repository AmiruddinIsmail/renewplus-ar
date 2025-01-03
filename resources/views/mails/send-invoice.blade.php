<x-mail::message>
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td>
                <img
                    src="{{ asset("images/compasia-logo.png") }}"
                    style="height: 40px"
                />
            </td>
            <td align="right">
                Invoice No: {{ $invoice->reference_no }}
                <br />
                Date: {{ $invoice->issue_at }}
            </td>
        </tr>
    </table>
    <br />
    Dear {{ $customer->name }},

    <x-mail::table>
        | Description | Amount(RM)| | :--------------------- | ------: | |
        Monthly Fixed 12/23 |
        {{ $invoice->convertToHumanReadable($invoice->subscription_amount) }} |
        @if ($invoice->charge_amount > 0)
                | Late Charges 12/23 |
                {{ $invoice->convertToHumanReadable($invoice->charge_amount) }}
                |
        @endif

        @if ($invoice->credit_paid > 0)
                | Credit Applied |
                {{ $invoice->convertToHumanReadable($invoice->credit_paid) }} |
        @endif

        @if ($invoice->over_paid > 0)
                | Overpayment |
                {{ $invoice->convertToHumanReadable($invoice->over_paid) }} |
        @endif

        | **Total Amount (RM)** |
        {{ $invoice->convertToHumanReadable($invoice->unresolved_amount) }} |
    </x-mail::table>

    If you have any questions or concerns regarding this invoice, please don't
    hesitate to contact us. Thank you again for your business! Regards,
    {{ config("app.name") }} Team
    {{ config("app.company_email") }}
</x-mail::message>
