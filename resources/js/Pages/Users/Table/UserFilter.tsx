import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { getParameterByName } from "@/lib/utils";
import { router, usePage } from "@inertiajs/react";
import { useState } from "react";

export default function UserFilter() {
    const { url } = usePage();

    const [search, setSearch] = useState(
        getParameterByName(encodeURIComponent("filter[search]"), url) ?? ""
    );

    const isFilterValid = () => search.length > 0;

    const onSearch = (e: any) => {
        e.preventDefault();
        if (!isFilterValid()) return;

        router.visit(route("users.index"), {
            method: "get",
            data: {
                "filter[search]": search,
            },
            preserveState: true,
        });
    };

    const resetSearch = () => {
        router.visit(route("users.index"), {
            preserveState: false,
        });
    };

    return (
        <div className="flex items-center mt-4 mb-4 gap-2">
            <Input
                type="text"
                placeholder="Search"
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                onKeyDown={(e) => e.key === "Enter" && onSearch(e)}
            />

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
}
