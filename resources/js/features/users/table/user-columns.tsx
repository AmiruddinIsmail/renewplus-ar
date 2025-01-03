import DataTableIndex from "@/components/datatables/datatable-index";
import DataTableSortIcon from "@/components/datatables/datatable-sorticon";
import { Button } from "@/components/ui/button";
import { User } from "@/types/user";
import { ColumnDef } from "@tanstack/react-table";

export const userColumns = (
    page: number,
    perPageCount: number = 10,
): ColumnDef<User>[] => {
    return [
        {
            header: "No",
            cell: ({ row, table }) => {
                return DataTableIndex(page, table, row, perPageCount);
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
            header: ({ column }) => {
                return (
                    <>
                        <div className="flex items-center gap-0">
                            Created At
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
    ];
};
