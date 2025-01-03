import AppHeader from "@/components/app-header";
import { AppSidebar } from "@/components/app-sidebar";
import { Breadcrumb } from "@/components/ui/breadcrumb";
import { SidebarInset, SidebarProvider } from "@/components/ui/sidebar";
import { Toaster } from "@/components/ui/toaster";
import { PropsWithChildren, ReactNode } from "react";

export default function DashboardLayout({
    header,
    children,
}: PropsWithChildren<{ header?: ReactNode }>) {
    return (
        <SidebarProvider>
            <AppSidebar />
            <SidebarInset>
                {header && (
                    <AppHeader>
                        <Breadcrumb>{header}</Breadcrumb>
                    </AppHeader>
                )}
                <main className="p-12 pt-0">{children}</main>
            </SidebarInset>
            <Toaster />
        </SidebarProvider>
    );
}
