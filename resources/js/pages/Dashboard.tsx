import {
    BreadcrumbItem,
    BreadcrumbList,
    BreadcrumbPage,
} from "@/components/ui/breadcrumb";
import CustomerChart from "@/features/dashboard/components/customer-chart";
import CustomerStatistic from "@/features/dashboard/components/customer-statistic";
import DeviceChart from "@/features/dashboard/components/device-chart";
import InvoiceUnresolved from "@/features/dashboard/components/invoice-unresolved";
import DashboardLayout from "@/layouts/dashboard-layout";
import { Deferred, Head } from "@inertiajs/react";

type Props = {
    posts: string[];
};

export default function Page({ posts }: Props) {
    return (
        <DashboardLayout
            header={
                <BreadcrumbList>
                    <BreadcrumbItem className="hidden md:block">
                        <BreadcrumbPage>Dashboard</BreadcrumbPage>
                    </BreadcrumbItem>
                </BreadcrumbList>
            }
        >
            <Head title="Dashboard" />
            <div className="flex flex-col gap-4">
                <div className="grid grid-cols-4 gap-4">
                    <CustomerStatistic />
                    <CustomerChart />
                    <InvoiceUnresolved />
                    <CustomerChart />
                </div>
                <div>
                    <DeviceChart />
                </div>
                <div>
                    <Deferred data="posts" fallback={<div>Loading...</div>}>
                        <div className=" text-xl">
                            {posts &&
                                posts.map((post) => (
                                    <div key={post}>{post}</div>
                                ))}
                        </div>
                    </Deferred>
                </div>
            </div>
        </DashboardLayout>
    );
}
