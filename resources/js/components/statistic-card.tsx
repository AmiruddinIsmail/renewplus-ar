import { DollarSign } from "lucide-react";

export default function StatisticCard() {
    return (
        <div className="rounded-xl border bg-card text-card-foreground shadow-sm">
            <div className="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                <h3 className="text-sm font-medium tracking-tight">
                    Total Revenue
                </h3>
                <DollarSign className="h-4 w-4 text-muted-foreground" />
            </div>
            <div className="p-6 pt-0">
                <div className="text-2xl font-bold">$45,231.89</div>
            </div>
        </div>
    );
}
