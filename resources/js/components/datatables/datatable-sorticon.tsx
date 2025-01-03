import { ChevronDown, ChevronsUpDown, ChevronUp } from "lucide-react";

export default function DataTableSortIcon({
    sorted,
}: {
    sorted: string | boolean;
}) {
    if (sorted == false) {
        return <ChevronsUpDown className="h-4 w-4" />;
    }

    return sorted === "asc" ? (
        <ChevronDown className="h-4 w-4" />
    ) : (
        <ChevronUp className="h-4 w-4" />
    );
}
