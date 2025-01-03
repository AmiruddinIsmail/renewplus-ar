import DataTableIndex from "@/components/datatables/datatable-index";
import DataTableSortIcon from "@/components/datatables/datatable-sorticon";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Invoice } from "@/types/invoice";
import { Link } from "@inertiajs/react";
import { ColumnDef } from "@tanstack/react-table";

export const invoiceColumns = (
    page: number,
    perPageCount: number = 10,
): ColumnDef<Invoice>[] => {
    return [
        {
            header: "No",
            cell: ({ row, table }) => {
                return DataTableIndex(page, table, row, perPageCount);
            },
        },
        {
            accessorKey: "reference_no",
            header: ({ column }) => {
                return (
                    <>
                        <div className="flex items-center gap-0">
                            Invoice No
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
                        href={route("invoices.show", row.original.id)}
                        className=" text-primary hover:underline"
                    >
                        {row.getValue("reference_no")}
                    </Link>
                );
            },
        },
        {
            accessorKey: "issue_at",
            header: ({ column }) => {
                return (
                    <>
                        <div className="flex items-center gap-0">
                            Issue Date
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
            accessorKey: "customer.name",
            header: ({ column }) => {
                return (
                    <>
                        <div className="flex items-center gap-0">
                            Customer Name
                        </div>
                    </>
                );
            },
        },
        {
            accessorKey: "order.reference_no",
            header: ({ column }) => {
                return (
                    <>
                        <div className="flex items-center gap-0">Order No</div>
                    </>
                );
            },
        },
        {
            accessorKey: "amount",
            header: () => {
                return (
                    <>
                        <div className="flex items-center justify-end gap-0">
                            Total Amount (RM)
                        </div>
                    </>
                );
            },
            cell: ({ row }) => {
                return (
                    <div className="flex justify-end">
                        {row.original.amount}
                    </div>
                );
            },
        },
        {
            accessorKey: "status",
            header: ({ column }) => {
                return (
                    <>
                        <div className="flex items-center gap-0 justify-end">
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
                    <div className="flex justify-end">
                        <Badge variant="secondary">
                            {row.getValue("status")}
                        </Badge>
                    </div>
                );
            },
        },
        {
            accessorKey: "unresolved_amount",
            header: ({ column }) => {
                return (
                    <>
                        <div className="flex justify-end">
                            Unresolved Amount
                        </div>
                    </>
                );
            },
            cell: ({ row }) => {
                return (
                    <div className="flex justify-end">
                        {row.getValue("unresolved_amount")}
                    </div>
                );
            },
        },
    ];
};
