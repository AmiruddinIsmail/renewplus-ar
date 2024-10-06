export type Invoice = {
    id: number;
    customer_id: number;
    reference_no: string;
    issue_at: string;
    due_at: string;
    subscription_fee: number;
    charge_fee: number;
    paid_amount: number;
    unresolved: boolean;
    unresolved_amount: number;
    created_at: string;
    updated_at: string;
};
