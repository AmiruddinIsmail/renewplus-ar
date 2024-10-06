import { Config } from "ziggy-js";

export type MenuItem = {
    label: string;
    route: string;
    url: string;
    badge: number | null;
    icon: string;
};

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>
> = T & {
    auth: {
        user: User;
    };
    ziggy: Config & { location: string };
    menu: MenuItem[];
};

export {
    MenuItem,
    PageProps,
    Datatable,
    Meta,
    User,
    Customer,
    Order,
    Address,
    Invoice,
};
