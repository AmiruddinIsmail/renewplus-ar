import {
    BreadcrumbItem,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "@/components/ui/breadcrumb";
import DashboardLayout from "@/layouts/dashboard-layout";
import { Deferred, Head, Link } from "@inertiajs/react";
import { Datatable } from "@/types/datatable";
import { DataTable } from "@/components/datatables/data-table";
import DataTableLoading from "@/components/datatables/datatable-loading";
import { customerColumns } from "@/features/customers/tables/customer-columns";
import { Customer } from "@/types/customer";
import CustomerFilter from "@/features/customers/tables/customer-filter";

export default function Page({ table }: { table: Datatable<Customer> }) {
    return (
        <DashboardLayout
            header={
                <BreadcrumbList>
                    <BreadcrumbItem className="hidden md:block">
                        <Link href={route("dashboard")}>Dashboard</Link>
                    </BreadcrumbItem>
                    <BreadcrumbSeparator className="hidden md:block" />
                    <BreadcrumbItem>
                        <BreadcrumbPage>Customers</BreadcrumbPage>
                    </BreadcrumbItem>
                </BreadcrumbList>
            }
        >
            <Head title="Customers" />
            <div className="flex flex-col gap-4">
                <CustomerFilter />
                <Deferred data="table" fallback={<DataTableLoading />}>
                    {table && (
                        <DataTable
                            columns={customerColumns(
                                table.meta.current_page,
                                table.meta.per_page,
                            )}
                            data={table.data}
                            paginator={table}
                        />
                    )}
                </Deferred>
            </div>
        </DashboardLayout>
    );
}
