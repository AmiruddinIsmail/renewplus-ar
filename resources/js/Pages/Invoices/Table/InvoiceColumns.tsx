import DataTableIndex from "@/Components/DataTableIndex";
import DataTableSortIcon from "@/Components/DataTableSortIcon";
import { Badge } from "@/Components/ui/badge";
import { Button } from "@/Components/ui/button";
import { Invoice } from "@/types";
import { ColumnDef } from "@tanstack/react-table";

export const invoiceColumns = (page: number): ColumnDef<Invoice>[] => {
    return [
        {
            header: "No",
            cell: ({ row, table }) => {
                return DataTableIndex(page, table, row);
            },
        },
        {
            accessorKey: "reference_no",
            header: ({ column }) => {
                return (
                    <>
                        <div className="flex items-center gap-0">
                            Reference No
                            <Button
                                variant="ghost"
                                onClick={() => {
                                    return column.toggleSorting(
                                        column.getIsSorted() === "asc"
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
            header: "Customer Name",
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
                                        column.getIsSorted() === "asc"
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
            accessorKey: "due_at",
            header: ({ column }) => {
                return (
                    <>
                        <div className="flex items-center gap-0">
                            Due Date
                            <Button
                                variant="ghost"
                                onClick={() => {
                                    return column.toggleSorting(
                                        column.getIsSorted() === "asc"
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
            accessorKey: "subscription_fee",
            header: ({ column }) => {
                return (
                    <div className="flex items-center justify-end">
                        Invoice Amount
                        <Button
                            variant="ghost"
                            onClick={() => {
                                return column.toggleSorting(
                                    column.getIsSorted() === "asc"
                                );
                            }}
                        >
                            <DataTableSortIcon sorted={column.getIsSorted()} />
                        </Button>
                    </div>
                );
            },
            cell: ({ row }) => {
                const invoice = row.original as Invoice;
                return (
                    <div className="flex justify-end">
                        {parseFloat(invoice.subscription_fee) +
                            parseFloat(invoice.charge_fee)}
                    </div>
                );
            },
        },
        {
            accessorKey: "paid_amount",
            header: ({ column }) => {
                return (
                    <div className="flex items-center justify-end">
                        Paid Amount
                        <Button
                            variant="ghost"
                            onClick={() => {
                                return column.toggleSorting(
                                    column.getIsSorted() === "asc"
                                );
                            }}
                        >
                            <DataTableSortIcon sorted={column.getIsSorted()} />
                        </Button>
                    </div>
                );
            },
            cell: ({ row }) => {
                return (
                    <div className="flex justify-end">
                        {row.getValue("paid_amount")}
                    </div>
                );
            },
        },
        {
            accessorKey: "status",
            header: "Status",
            cell: ({ row }) => {
                switch (row.getValue("status")) {
                    case "partial":
                        return <Badge variant="warning">Partialy Paid</Badge>;
                    case "overdue":
                        return <Badge variant="destructive">Overdue</Badge>;
                    case "paid":
                        return <Badge variant="default">Paid</Badge>;
                    default:
                        return (
                            <Badge variant="secondary">
                                {row.getValue("status") as string}
                            </Badge>
                        );
                }
            },
        },
        {
            accessorKey: "unresolved_amount",
            header: ({ column }) => {
                return (
                    <div className="flex items-center justify-end">
                        Balance Amount
                        <Button
                            variant="ghost"
                            onClick={() => {
                                return column.toggleSorting(
                                    column.getIsSorted() === "asc"
                                );
                            }}
                        >
                            <DataTableSortIcon sorted={column.getIsSorted()} />
                        </Button>
                    </div>
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
