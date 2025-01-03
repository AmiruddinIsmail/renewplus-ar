import {
    BreadcrumbItem,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "@/components/ui/breadcrumb";
import DashboardLayout from "@/layouts/dashboard-layout";
import { Head, Link } from "@inertiajs/react";
import InvoiceDetail from "@/features/invoices/shared/invoice-detail";
import { Invoice } from "@/types/invoice";

type Props = {
    invoice: Invoice;
};
export default function Page({ invoice }: Props) {
    return (
        <DashboardLayout
            header={
                <BreadcrumbList>
                    <BreadcrumbItem className="hidden md:block">
                        <Link href={route("dashboard")}>Dashboard</Link>
                    </BreadcrumbItem>
                    <BreadcrumbSeparator className="hidden md:block" />
                    <BreadcrumbItem className="hidden md:block">
                        <Link href={route("invoices.index")}>Invoices</Link>
                    </BreadcrumbItem>
                    <BreadcrumbSeparator className="hidden md:block" />
                    <BreadcrumbItem>
                        <BreadcrumbPage>{invoice.reference_no}</BreadcrumbPage>
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
                <InvoiceDetail invoice={invoice} />
            </div>
        </DashboardLayout>
    );
}
