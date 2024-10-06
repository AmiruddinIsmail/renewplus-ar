import { Datatable } from "@/types";
import {
    Pagination,
    PaginationContent,
    PaginationItem,
    PaginationNext,
    PaginationPrevious,
} from "./ui/pagination";
import { Link } from "@inertiajs/react";

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
                    >
                        <PaginationNext />
                    </Link>
                </PaginationItem>
            </PaginationContent>
        </Pagination>
    );
}
