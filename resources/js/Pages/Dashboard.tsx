import DashboardLayout from "@/Layouts/DashboardLayout";
import { Head } from "@inertiajs/react";

export default function Dashboard() {
    return (
        <DashboardLayout>
            <Head title="Dashboard" />

            <div className="flex items-center">
                <h1 className="text-lg font-semibold md:text-2xl">Dashboard</h1>
            </div>
        </DashboardLayout>
    );
}
