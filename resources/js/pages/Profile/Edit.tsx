import { PageProps } from "@/types";
import { Head, Link } from "@inertiajs/react";
import DashboardLayout from "@/layouts/dashboard-layout";
import {
    BreadcrumbItem,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "@/components/ui/breadcrumb";
import UpdateProfileInformation from "@/features/profile/components/UpdateProfileInformationForm";
import UpdatePasswordForm from "@/features/profile/components/UpdatePasswordForm";
import DeleteUserForm from "@/features/profile/components/DeleteUserForm";

export default function Edit({
    mustVerifyEmail,
    status,
}: PageProps<{ mustVerifyEmail: boolean; status?: string }>) {
    return (
        <DashboardLayout
            header={
                <BreadcrumbList>
                    <BreadcrumbItem className="hidden md:block">
                        <Link href={route("dashboard")}>Dashboard</Link>
                    </BreadcrumbItem>
                    <BreadcrumbSeparator className="hidden md:block" />
                    <BreadcrumbItem>
                        <BreadcrumbPage>My Profile</BreadcrumbPage>
                    </BreadcrumbItem>
                </BreadcrumbList>
            }
        >
            <Head title="Profile" />

            <div className="">
                <div className="flex gap-4 justify-between items-start">
                    <UpdateProfileInformation
                        mustVerifyEmail={mustVerifyEmail}
                        status={status}
                        className="w-full"
                    />
                    <UpdatePasswordForm className="w-full" />
                </div>
                <DeleteUserForm className="max-w-xl" />
            </div>
        </DashboardLayout>
    );
}
