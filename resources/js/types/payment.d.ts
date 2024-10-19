export type InvoicePayment = {
    invoice_id: number;
    payment_id: number;
    amount: number;
    created_at: string;
    updated_at: string;
};

export type Payment = {
    id: number;
    customer_id: number;
    reference_no: string;
    paid_at: string;
    amount: number;
    unresolved: boolean;
    unresolved_amount: number;
    created_at: string;
    updated_at: string;

    pivot: InvoicePayment;
};
