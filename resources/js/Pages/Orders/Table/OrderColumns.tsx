import DataTableSortIcon from "@/Components/DataTableSortIcon";
import { Badge } from "@/Components/ui/badge";
import { Button } from "@/Components/ui/button";
import { ORDER_STATUS } from "@/constants/constants";
import { Order } from "@/types";
import { ColumnDef } from "@tanstack/react-table";
import { EllipsisVertical } from "lucide-react";

export const orderTableColumns: ColumnDef<Order>[] = [
    {
        accessorKey: "number",
        header: ({ column }) => {
            return (
                <>
                    <div className="flex items-center gap-0">
                        Order ID
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
                </>
            );
        },
    },
    {
        accessorKey: "name",
        header: ({ column }) => {
            return (
                <>
                    <div className="flex items-center gap-0">
                        Product Name
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
                </>
            );
        },
    },
    {
        accessorKey: "user.name",
        header: "Customer Name",
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
                                    column.getIsSorted() === "asc"
                                );
                            }}
                        >
                            <DataTableSortIcon sorted={column.getIsSorted()} />
                        </Button>
                    </div>
                </>
            );
        },
        cell: ({ row }) => {
            switch (row.getValue("status")) {
                case ORDER_STATUS.pending:
                    return <Badge variant="secondary">Pending</Badge>;
                case ORDER_STATUS.processing:
                    return <Badge variant="warning">Processing</Badge>;
                case ORDER_STATUS.completed:
                    return <Badge variant="green">Completed</Badge>;
            }
            return <Badge variant="default">{row.getValue("status")}</Badge>;
        },
    },
    {
        accessorKey: "created_at",
        header: ({ column }) => {
            return (
                <>
                    <div className="flex items-center gap-0">
                        Created At
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
                </>
            );
        },
    },

    {
        accessorKey: "actions",
        header: "",
        cell: ({ row }) => {
            return (
                <div className="flex justify-between">
                    <div></div>
                    <Button variant="link" size="xs">
                        <EllipsisVertical className="h-4 w-4" />
                    </Button>
                </div>
            );
        },
    },
];
