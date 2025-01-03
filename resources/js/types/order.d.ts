export type Order = {
    id: number;
    customer_id: number;
    reference_no: string;
    tenure: number;
    subscription_amount: string;
    contract_at: string;
    payment_gateway: string;
    payment_reference: string;
    active: boolean;
    completed_at: string;
    created_at: string;
};
