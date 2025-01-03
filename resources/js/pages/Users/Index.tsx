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
import { User } from "@/types/user";
import UserFilter from "@/features/users/table/user-filter";
import { userColumns } from "@/features/users/table/user-columns";

export default function Page({ table }: { table: Datatable<User> }) {
    return (
        <DashboardLayout
            header={
                <BreadcrumbList>
                    <BreadcrumbItem className="hidden md:block">
                        <Link href={route("dashboard")}>Dashboard</Link>
                    </BreadcrumbItem>
                    <BreadcrumbSeparator className="hidden md:block" />
                    <BreadcrumbItem>
                        <BreadcrumbPage>Users</BreadcrumbPage>
                    </BreadcrumbItem>
                </BreadcrumbList>
            }
        >
            <Head title="Customers" />
            <div className="flex flex-col gap-4">
                <UserFilter />
                <Deferred data="table" fallback={<DataTableLoading />}>
                    {table && (
                        <DataTable
                            columns={userColumns(
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
