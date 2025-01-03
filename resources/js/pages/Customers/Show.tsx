import StatisticCard from "@/components/statistic-card";
import {
    BreadcrumbItem,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "@/components/ui/breadcrumb";
import { Separator } from "@/components/ui/separator";
import CustomerEdit from "@/features/customers/components/customer-edit";
import CustomerOrder from "@/features/customers/components/customer-order";
import DashboardLayout from "@/layouts/dashboard-layout";
import { Customer } from "@/types/customer";
import { Head, Link } from "@inertiajs/react";
import CustomerMenu from "@/features/customers/components/customer-menu";

export default function Page({ customer }: { customer: Customer }) {
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
                        <BreadcrumbPage>{customer.name}</BreadcrumbPage>
                    </BreadcrumbItem>
                    <BreadcrumbSeparator className="hidden md:block" />
                    <BreadcrumbItem>
                        <BreadcrumbPage>Summary</BreadcrumbPage>
                    </BreadcrumbItem>
                </BreadcrumbList>
            }
        >
            <Head title="Customers Detail" />

            <div className="flex flex-col gap-2">
                <CustomerMenu title={customer.uuid} id={customer.id} />

                <Separator className="my-2" />

                <div className="grid gap-6">
                    <div className="grid md:grid-cols-4 gap-4">
                        <StatisticCard />
                        <StatisticCard />
                        <StatisticCard />
                        <StatisticCard />
                    </div>
                    <div className="grid grid-cols-2 gap-4">
                        <CustomerEdit customer={customer} />
                        <CustomerOrder order={customer.order} />
                    </div>
                </div>
            </div>
        </DashboardLayout>
    );
}
