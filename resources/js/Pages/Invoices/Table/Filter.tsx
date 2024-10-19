import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import { getParameterByName } from "@/lib/utils";
import { router, usePage } from "@inertiajs/react";
import { useState } from "react";

export const Filter = () => {
    const { url } = usePage();

    const [search, setSearch] = useState(
        getParameterByName(encodeURIComponent("filter[search]"), url) ?? "",
    );

    const [status, setStatus] = useState(
        getParameterByName(encodeURIComponent("filter[status]"), url) ?? "",
    );

    const isFilterValid = () => search.length > 0 || status.length > 0;

    const onSearch = (e: any) => {
        e.preventDefault();
        if (!isFilterValid()) return;

        router.visit(route("invoices.index"), {
            method: "get",
            data: {
                "filter[search]": search,
                "filter[status]": status,
            },
            preserveState: true,
        });
    };

    const resetSearch = () => {
        router.visit(route("invoices.index"), {
            preserveState: false,
        });
    };

    return (
        <div className="mb-4 mt-4 flex items-center gap-2">
            <Input
                type="text"
                placeholder="Search"
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                onKeyDown={(e) => e.key === "Enter" && onSearch(e)}
            />
            <Select onValueChange={setStatus}>
                <SelectTrigger className="w-[180px]">
                    <SelectValue
                        placeholder={status === "" ? "Status" : status}
                    />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="pending">Pending</SelectItem>
                    <SelectItem value="overdue">Overdue</SelectItem>
                    <SelectItem value="paid">Paid</SelectItem>
                    <SelectItem value="partial">Partialy Paid</SelectItem>
                </SelectContent>
            </Select>
            <Button
                onClick={onSearch}
                variant="secondary"
                disabled={!isFilterValid()}
            >
                Search
            </Button>

            {url.includes("?") && (
                <Button variant="destructive" onClick={resetSearch}>
                    Reset
                </Button>
            )}
        </div>
    );
};