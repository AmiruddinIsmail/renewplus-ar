import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { getParameterByName } from "@/lib/utils";
import { useForm, usePage } from "@inertiajs/react";
import { Loader } from "lucide-react";

export default function CustomerFilter() {
    const { url } = usePage();
    const { data, setData, get, processing, transform } = useForm({
        search:
            getParameterByName(encodeURIComponent("filter[search]"), url) ?? "",
    });

    const onSearch = (e: any) => {
        e.preventDefault();

        transform((data) => {
            if (data.search.length === 0) return {};

            return {
                "filter[search]": data.search,
                limit:
                    getParameterByName(encodeURIComponent("limit"), url) || 10,
            };
        });

        get(route("customers.index"), {
            only: ["table"],
            preserveState: true,
        });
    };

    return (
        <div className="flex items-center gap-2">
            <Input
                type="text"
                placeholder="Search"
                value={data.search}
                onChange={(e) => setData("search", e.target.value)}
                onKeyDown={(e) => e.key === "Enter" && onSearch(e)}
            />

            <Button
                onClick={onSearch}
                variant="secondary"
                disabled={processing}
            >
                {processing && <Loader className="h-4 w-4 animate-spin" />}
                Search
            </Button>
        </div>
    );
}
