import { Datatable } from "@/types";

import { Link } from "@inertiajs/react";
import {
    Pagination,
    PaginationContent,
    PaginationItem,
    PaginationNext,
    PaginationPrevious,
} from "../ui/pagination";

export function DataTablePaginator({ paginator }: { paginator: Datatable }) {
    return (
        <Pagination>
            <PaginationContent>
                <PaginationItem>
                    <Link
                        as="button"
                        disabled={!paginator.links.prev}
                        href={paginator.links.prev}
                        preserveScroll
                        preserveState
                        only={["table"]}
                    >
                        <PaginationPrevious />
                    </Link>
                </PaginationItem>
                <PaginationItem>
                    <Link
                        as="button"
                        disabled={!paginator.links.next}
                        href={paginator.links.next}
                        preserveScroll
                        preserveState
                        only={["table"]}
                    >
                        <PaginationNext />
                    </Link>
                </PaginationItem>
            </PaginationContent>
        </Pagination>
    );
}
