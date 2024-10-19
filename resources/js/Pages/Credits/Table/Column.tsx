import DataTableIndex from "@/Components/DataTableIndex";
import DataTableSortIcon from "@/Components/DataTableSortIcon";
import { Badge } from "@/Components/ui/badge";
import { Button } from "@/Components/ui/button";
import { User } from "@/types";
import { ColumnDef } from "@tanstack/react-table";

export const column = (page: number): ColumnDef<User>[] => {
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
            header: "amount",
        },
        {
            accessorKey: "unresolved",
            header: "Unresolved",
            cell: ({ row }) => {
                switch (row.getValue("unresolved")) {
                    case 1:
                        return <Badge variant="destructive">Unresolved</Badge>;
                    case 0:
                        return <Badge variant="green">Resolved</Badge>;
                    default:
                        return (
                            <Badge variant="secondary">
                                {row.getValue("unresolved") as string}
                            </Badge>
                        );
                }
            },
        },
        {
            accessorKey: "created_at",
            header: "Created At",
        },
    ];
};
