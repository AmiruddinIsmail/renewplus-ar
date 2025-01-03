export type Datatable<T> = {
    data: T[];
    links: DatatableLink;
    meta: Meta;
};

export type DatatableLink = {
    first: string;
    last: string;
    next: string | null;
    prev: string | null;
};

export type Meta = {
    current_page: number;
    from: number;
    last_page: number;
    path: string;
    per_page: number;
    to: number;
    total: number;
    links: MetaLink[];
};

export type MetaLink = {
    active: boolean;
    label: string;
    url: string | null;
};
