import { DataTable } from "@/Components/DataTable";
import { Filter } from "./Filter";
import { column } from "./Column";
import { Datatable } from "@/types/datatable";

export default function CustomerTable({ table }: { table: Datatable }) {
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
