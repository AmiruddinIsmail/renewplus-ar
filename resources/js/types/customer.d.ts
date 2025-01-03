export type Customer = {
    id: number;
    uuid: string;
    name: string;
    email: string;
    phone: string;
    status: string;
    created_at: string;
    updated_at: string;
    unresolved_invoices_amount: string;
    order?: Order;
};
