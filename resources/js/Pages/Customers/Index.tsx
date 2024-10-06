import { DataTable } from "@/Components/DataTable";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Datatable } from "@/types";
import { Head, Link } from "@inertiajs/react";

import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "@/Components/ui/breadcrumb";
import { Button } from "@/Components/ui/button";
import { PlusIcon } from "lucide-react";
import CustomerFilter from "./Table/CustomerFilter";
import { customerColumns } from "./Table/CustomerColumns";

export default function CustomerIndex({ table }: { table: Datatable }) {
    return (
        <>
            <DashboardLayout>
                <Head title="Users" />
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-lg font-semibold md:text-2xl">
                            Customers
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
                                    <BreadcrumbPage>Customers</BreadcrumbPage>
                                </BreadcrumbItem>
                            </BreadcrumbList>
                        </Breadcrumb>
                    </div>
                    <Button asChild>
                        <Link href={route("users.create")} as="button">
                            <PlusIcon className="h-4 w-4" /> Create
                        </Link>
                    </Button>
                </div>
                <CustomerFilter />
                <DataTable
                    columns={customerColumns(table.meta.current_page)}
                    data={table.data}
                    paginator={table}
                />
            </DashboardLayout>
        </>
    );
}
