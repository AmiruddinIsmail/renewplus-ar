import DataTableIndex from "@/components/datatables/datatable-index";
import DataTableSortIcon from "@/components/datatables/datatable-sorticon";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/components/ui/dialog";
import InvoiceDetail from "@/features/invoices/shared/invoice-detail";
import { Invoice } from "@/types/invoice";
import { ColumnDef } from "@tanstack/react-table";

export const customerInvoicesColumns = (
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
                    <div>
                        <Dialog>
                            <DialogTrigger asChild>
                                <Button variant="link">
                                    {row.getValue("reference_no")}
                                </Button>
                            </DialogTrigger>
                            <DialogContent className=" max-w-screen-xl max-h-[600px] overflow-y-scroll">
                                <DialogHeader>
                                    <DialogTitle>Invoice Detail</DialogTitle>
                                </DialogHeader>
                                <div className="grid gap-4 py-4">
                                    <InvoiceDetail invoice={row.original} />
                                </div>
                            </DialogContent>
                        </Dialog>
                    </div>
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
            accessorKey: "amount",
            header: ({ column }) => {
                return (
                    <>
                        <div className="flex items-center gap-0">
                            Total Amount (RM)
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
                return <div>{row.original.amount}</div>;
            },
        },
        {
            accessorKey: "paid_amount",
            header: ({ column }) => {
                return (
                    <>
                        <div className="flex items-center gap-0">
                            Paid Amount
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
            accessorKey: "credit_paid",
            header: ({ column }) => {
                return (
                    <>
                        <div className="flex items-center gap-0">
                            Credit Applied
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
                    <div className="flex justify-center">
                        <Badge variant="secondary" className="align-right">
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
