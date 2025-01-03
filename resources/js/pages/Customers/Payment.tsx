import { DataTable } from "@/components/datatables/data-table";
import DataTableLoading from "@/components/datatables/datatable-loading";
import {
    BreadcrumbItem,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "@/components/ui/breadcrumb";
import { Separator } from "@/components/ui/separator";
import CustomerMenu from "@/features/customers/components/customer-menu";
import { customerPaymentsColumns } from "@/features/customers/tables/payments-columns";
import DashboardLayout from "@/layouts/dashboard-layout";
import { Customer } from "@/types/customer";
import { Datatable } from "@/types/datatable";
import { Payment } from "@/types/payment";
import { Deferred, Head, Link } from "@inertiajs/react";

type PropsType = {
    customer: Customer;
    table: Datatable<Payment>;
};

export default function Page({ customer, table }: PropsType) {
    return (
        <DashboardLayout
            header={
                <BreadcrumbList>
                    <BreadcrumbItem className="hidden md:block">
                        <Link href={route("dashboard")}>Dashboard</Link>
                    </BreadcrumbItem>
                    <BreadcrumbSeparator className="hidden md:block" />
                    <BreadcrumbItem className="hidden md:block">
                        <Link href={route("customers.index")}>Customers</Link>
                    </BreadcrumbItem>
                    <BreadcrumbSeparator className="hidden md:block" />
                    <BreadcrumbItem>
                        <Link href={route("customers.show", customer.id)}>
                            {customer.name}
                        </Link>
                    </BreadcrumbItem>
                    <BreadcrumbSeparator className="hidden md:block" />
                    <BreadcrumbItem>
                        <BreadcrumbPage>Payments</BreadcrumbPage>
                    </BreadcrumbItem>
                </BreadcrumbList>
            }
        >
            <Head title="Customers Detail" />
            <div className="flex flex-col gap-2">
                <CustomerMenu
                    title={customer.uuid}
                    id={customer.id}
                    activeName="Payments"
                />

                <Separator className="my-2" />

                <div className="grid gap-6">
                    <Deferred data="table" fallback={<DataTableLoading />}>
                        {table && (
                            <DataTable
                                columns={customerPaymentsColumns(
                                    table.meta.current_page,
                                    table.meta.per_page,
                                )}
                                data={table.data}
                                paginator={table}
                            />
                        )}
                    </Deferred>
                </div>
            </div>
        </DashboardLayout>
    );
}
