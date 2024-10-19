import { DataTable } from "@/Components/DataTable";

import { Datatable } from "@/types/datatable";
import { Filter } from "./Filter";
import { column } from "./Column";

export default function UserTable({ table }: { table: Datatable }) {
    return (
        <div>
            <Filter />
            <DataTable
                columns={column(table.meta.current_page)}
                data={table.data}
                paginator={table}
            />
        </div>
    );
}
