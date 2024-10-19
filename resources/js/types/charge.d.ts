export type Charge = {
    id: number;
    customer_id: number;
    reference_no: string;
    type: string;
    amount: number;
    charged_at: string;
    unresolved: boolean;
    invoice_id: number;
    created_at: string;
    updated_at: string;
};
