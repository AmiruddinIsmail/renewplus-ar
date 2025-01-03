import {
    ColumnDef,
    flexRender,
    getCoreRowModel,
    getPaginationRowModel,
    SortingState,
    useReactTable,
} from "@tanstack/react-table";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "../ui/table";
import { router, usePage } from "@inertiajs/react";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "../ui/select";
import { getParameterByName } from "@/lib/utils";
import { DEFAULT_LIMIT, LIMIT_CONST } from "@/constants/constants";
import { Datatable } from "@/types";
import { useEffect, useState } from "react";
import { DataTablePaginator } from "./datatable-paginator";

interface DataTableProps<TData, TValue, Paginator> {
    columns: ColumnDef<TData, TValue>[];
    data: TData[];
    paginator: Datatable;
}

export function DataTable<TData, TValue>({
    columns,
    data,
    paginator,
}: DataTableProps<TData, TValue, Datatable>) {
    const [sorting, setSorting] = useState<SortingState>([]);

    const { url } = usePage();
    const limit = getParameterByName(LIMIT_CONST, url) ?? DEFAULT_LIMIT;

    const table = useReactTable({
        data,
        columns,
        getCoreRowModel: getCoreRowModel(),
        getPaginationRowModel: getPaginationRowModel(),
        manualPagination: true,
        manualSorting: true,
        state: {
            sorting,
        },
        onSortingChange: setSorting,
    });

    const onLimitChange = (value: string) => {
        router.visit(url, {
            only: ["table"],
            method: "get",
            data: {
                limit: value,
                page: 1,
            },
            preserveState: true,
            preserveScroll: true,
        });
    };

    useEffect(() => {
        if (sorting.length > 0) {
            const sorts = sorting.map(
                (sort) => `${sort.desc ? "-" : ""}${sort.id}`,
            );

            router.visit(url, {
                only: ["table"],
                method: "get",
                data: {
                    sort: sorts.join(","),
                },
                preserveState: true,
                preserveScroll: true,
            });
        }
    }, [sorting]);

    return (
        <>
            <div className="rounded-md border">
                <Table>
                    <TableHeader>
                        {table.getHeaderGroups().map((headerGroup) => (
                            <TableRow key={headerGroup.id}>
                                {headerGroup.headers.map((header) => {
                                    return (
                                        <TableHead key={header.id}>
                                            {header.isPlaceholder
                                                ? null
                                                : flexRender(
                                                      header.column.columnDef
                                                          .header,
                                                      header.getContext(),
                                                  )}
                                        </TableHead>
                                    );
                                })}
                            </TableRow>
                        ))}
                    </TableHeader>
                    <TableBody>
                        {table.getRowModel().rows?.length ? (
                            table.getRowModel().rows.map((row) => (
                                <TableRow
                                    key={row.id}
                                    data-state={
                                        row.getIsSelected() && "selected"
                                    }
                                >
                                    {row.getVisibleCells().map((cell) => (
                                        <TableCell key={cell.id}>
                                            {flexRender(
                                                cell.column.columnDef.cell,
                                                cell.getContext(),
                                            )}
                                        </TableCell>
                                    ))}
                                </TableRow>
                            ))
                        ) : (
                            <TableRow>
                                <TableCell
                                    colSpan={columns.length}
                                    className="h-24 text-center"
                                >
                                    No results.
                                </TableCell>
                            </TableRow>
                        )}
                    </TableBody>
                </Table>
            </div>
            <div className="md:flex items-center justify-between">
                <div className="flex items-center space-x-2">
                    <Select onValueChange={onLimitChange}>
                        <SelectTrigger className="w-16">
                            <SelectValue placeholder={limit} />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="10">10</SelectItem>
                            <SelectItem value="20">20</SelectItem>
                            <SelectItem value="50">50</SelectItem>
                        </SelectContent>
                    </Select>
                    <p className="text-sm">
                        Showing {paginator.meta.from} to {paginator.meta.to} of{" "}
                        {paginator.meta.total} results
                    </p>
                </div>
                <div className="flex items-center justify-end space-x-2 py-4">
                    <DataTablePaginator paginator={paginator} />

                    {/* {paginator.links.map((link, index) => {
                        if (link.url === null) {
                            return (
                                <Button
                                    key={index}
                                    variant="outline"
                                    size="xs"
                                    disabled={link.url === null}
                                    className={link.active ? "bg-gray-100" : ""}
                                >
                                    <span
                                        className="text-xs"
                                        dangerouslySetInnerHTML={{
                                            __html: link.label,
                                        }}
                                    ></span>
                                </Button>
                            );
                        }

                        return (
                            <Link
                                key={index}
                                href={link.url}
                                preserveScroll
                                preserveState
                            >
                                <Button
                                    key={index}
                                    variant="outline"
                                    size="xs"
                                    disabled={link.url === null}
                                    className={link.active ? "bg-gray-100" : ""}
                                >
                                    <span
                                        className="text-xs"
                                        dangerouslySetInnerHTML={{
                                            __html: link.label,
                                        }}
                                    ></span>
                                </Button>
                            </Link>
                        );
                    })} */}
                </div>
            </div>
        </>
    );
}
