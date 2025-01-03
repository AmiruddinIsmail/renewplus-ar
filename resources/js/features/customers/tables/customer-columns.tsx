import DataTableIndex from "@/components/datatables/datatable-index";
import DataTableSortIcon from "@/components/datatables/datatable-sorticon";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Customer } from "@/types";
import { Link } from "@inertiajs/react";
import { ColumnDef } from "@tanstack/react-table";

export const customerColumns = (
    page: number,
    perPageCount: number = 10,
): ColumnDef<Customer>[] => {
    return [
        {
            header: "No",
            cell: ({ row, table }) => {
                return DataTableIndex(page, table, row, perPageCount);
            },
        },
        {
            accessorKey: "uuid",
            header: ({ column }) => {
                return (
                    <>
                        <div className="flex items-center gap-0">
                            ID/NRIC
                            <Button
                                variant="ghost"
                                onClick={() => {
                                    return column.toggleSorting(
                                        column.getIsSorted() === "asc",
                                    );
                                }}
                            >
                                <DataTableSortIcon
                                    sorted={column.getIsSorted()}
                                />
                            </Button>
                        </div>
                    </>
                );
            },
            cell: ({ row }) => {
                return (
                    <Link
                        href={route("customers.show", row.original.id)}
                        className=" text-primary hover:underline"
                    >
                        {row.getValue("uuid")}
                    </Link>
                );
            },
        },
        {
            accessorKey: "name",
            header: ({ column }) => {
                return (
                    <>
                        <div className="flex items-center gap-0">
                            Name
                            <Button
                                variant="ghost"
                                onClick={() => {
                                    return column.toggleSorting(
                                        column.getIsSorted() === "asc",
                                    );
                                }}
                            >
                                <DataTableSortIcon
                                    sorted={column.getIsSorted()}
                                />
                            </Button>
                        </div>
                    </>
                );
            },
        },

        {
            accessorKey: "email",
            header: ({ column }) => {
                return (
                    <>
                        <div className="flex items-center gap-0">
                            Email
                            <Button
                                variant="ghost"
                                onClick={() => {
                                    return column.toggleSorting(
                                        column.getIsSorted() === "asc",
                                    );
                                }}
                            >
                                <DataTableSortIcon
                                    sorted={column.getIsSorted()}
                                />
                            </Button>
                        </div>
                    </>
                );
            },
        },
        {
            accessorKey: "status",
            header: ({ column }) => {
                return (
                    <>
                        <div className="flex items-center gap-0">
                            Status
                            <Button
                                variant="ghost"
                                onClick={() => {
                                    return column.toggleSorting(
                                        column.getIsSorted() === "asc",
                                    );
                                }}
                            >
                                <DataTableSortIcon
                                    sorted={column.getIsSorted()}
                                />
                            </Button>
                        </div>
                    </>
                );
            },
            cell: ({ row }) => {
                return (
                    <div className="flex">
                        <Badge variant="secondary">
                            {row.getValue("status")}
                        </Badge>
                    </div>
                );
            },
        },
        {
            accessorKey: "order.tenure",
            header: "Tenure (Months)",
        },
        {
            accessorKey: "unresolved_invoices_amount",
            header: ({ column }) => {
                return (
                    <>
                        <div className="flex items-center justify-end gap-0">
                            Unresolved Amount (RM)
                        </div>
                    </>
                );
            },
            cell: ({ row }) => {
                return (
                    <div className="flex justify-end">
                        {row.getValue("unresolved_invoices_amount")}
                    </div>
                );
            },
        },
    ];
};
