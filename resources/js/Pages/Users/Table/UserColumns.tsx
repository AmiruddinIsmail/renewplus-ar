import DataTableIndex from "@/Components/DataTableIndex";
import DataTableSortIcon from "@/Components/DataTableSortIcon";
import { Badge } from "@/Components/ui/badge";
import { Button } from "@/Components/ui/button";
import { User } from "@/types";
import { ColumnDef } from "@tanstack/react-table";

export const userColumns = (page: number): ColumnDef<User>[] => {
    return [
        {
            header: "No",
            cell: ({ row, table }) => {
                return DataTableIndex(page, table, row);
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
            accessorKey: "created_at",
            header: "Created At",
        },
    ];
};
