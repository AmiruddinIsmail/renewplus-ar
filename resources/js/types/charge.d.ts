export type Charge = {
    id: number;
    reference_no: string;
    charged_at: string;
    type: string;
    amount: number;
    unresolved: boolean;
    created_at: string;

    customer_id: number;
    order_id: number;
    invoice_id: number;
};
