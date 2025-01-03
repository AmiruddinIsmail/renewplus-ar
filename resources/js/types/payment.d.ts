export type InvoicePayment = {
    invoice_id: number;
    payment_id: number;
    amount: string;
};

export type Payment = {
    id: number;
    customer_id: number;
    order_id: number;
    reference_no: string;
    paid_at: string;
    amount: string;
    unresolved: boolean;
    unresolved_amount: string;
    created_at: string;
    updated_at: string;
    pivot: InvoicePayment;
    order: Order;
    invoices: Invoice[];
};
