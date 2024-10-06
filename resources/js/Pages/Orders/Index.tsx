import { DataTable } from "@/Components/DataTable";
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbList,
    BreadcrumbSeparator,
    BreadcrumbPage,
    BreadcrumbLink,
} from "@/Components/ui/breadcrumb";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Datatable } from "@/types";
import { Head, Link } from "@inertiajs/react";
import { orderTableColumns } from "./Table/OrderColumns";
import OrderFilter from "./Table/OrderFilter";
import { Button } from "@/Components/ui/button";
import { PlusIcon } from "lucide-react";

export default function OrdersIndex({ table }: { table: Datatable }) {
    return (
        <>
            <DashboardLayout>
                <Head title="Orders" />
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-lg font-semibold md:text-2xl">
                            Orders
                        </h1>
                        <Breadcrumb>
                            <BreadcrumbList>
                                <BreadcrumbItem>
                                    <Link href={route("dashboard")} as="button">
                                        <BreadcrumbLink>Home</BreadcrumbLink>
                                    </Link>
                                </BreadcrumbItem>
                                <BreadcrumbSeparator />
                                <BreadcrumbItem>
                                    <BreadcrumbPage>Orders</BreadcrumbPage>
                                </BreadcrumbItem>
                            </BreadcrumbList>
                        </Breadcrumb>
                    </div>
                    <Button asChild>
                        <Link href="/test" as="button">
                            <PlusIcon className="h-4 w-4" /> Create
                        </Link>
                    </Button>
                </div>

                <OrderFilter />
                <DataTable
                    columns={orderTableColumns}
                    data={table.data}
                    paginator={table}
                />
            </DashboardLayout>
        </>
    );
}
