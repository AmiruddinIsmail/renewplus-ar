import DashboardLayout from "@/Layouts/DashboardLayout";
import { Head, Link, useForm } from "@inertiajs/react";
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "@/Components/ui/breadcrumb";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Customer } from "@/types/customer";
import { Label } from "@/Components/ui/label";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/Components/ui/popover";
import { Check, ChevronsUpDown, Loader2 } from "lucide-react";
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
} from "@/Components/ui/command";
import React, { FormEvent } from "react";
import { cn } from "@/lib/utils";
import { useToast } from "@/hooks/use-toast";

export default function UsersIndex({ customers }: { customers: Customer[] }) {
    const [open, setOpen] = React.useState(false);

    const { toast } = useToast();

    const form = useForm({
        customerId: "",
        issueAt: new Date().toISOString().slice(0, 10),
        amount: "",
    });

    const onSubmit = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        form.post(route("credits.store"), {
            onSuccess: () => {
                console.log("OK");
                toast({
                    title: "Success",
                    description: "Credit created successfully",
                    variant: "success",
                });
            },
        });
    };

    return (
        <>
            <DashboardLayout>
                <Head title="Users" />
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-lg font-semibold md:text-2xl">
                            Create Credit Notes
                        </h1>
                        <Breadcrumb>
                            <BreadcrumbList>
                                <BreadcrumbItem>
                                    <Link href={route("dashboard")} as="button">
                                        <BreadcrumbLink>Home</BreadcrumbLink>
                                    </Link>
                                </BreadcrumbItem>
                                <BreadcrumbSeparator />
                                <BreadcrumbItem>
                                    <Link
                                        href={route("credits.index")}
                                        as="button"
                                    >
                                        <BreadcrumbLink>
                                            Credit Notes
                                        </BreadcrumbLink>
                                    </Link>
                                </BreadcrumbItem>
                                <BreadcrumbSeparator />
                                <BreadcrumbItem>
                                    <BreadcrumbPage>Create</BreadcrumbPage>
                                </BreadcrumbItem>
                            </BreadcrumbList>
                        </Breadcrumb>
                    </div>
                </div>
                <form onSubmit={onSubmit} className="space-y-4">
                    <div className="flex flex-col gap-2 md:flex-row">
                        <div className="w-full">
                            <Label>Customer</Label>
                            <Popover open={open} onOpenChange={setOpen}>
                                <PopoverTrigger asChild>
                                    <div className="w-full">
                                        <Button
                                            variant="outline"
                                            type="button"
                                            role="combobox"
                                            aria-expanded={open}
                                            className="w-full justify-between"
                                        >
                                            {form.data.customerId
                                                ? customers.find(
                                                      (customer) =>
                                                          customer.id.toString() ===
                                                          form.data.customerId,
                                                  )?.name
                                                : "Select customer..."}
                                            <ChevronsUpDown className="ml-2 h-4 w-4 shrink-0 opacity-50" />
                                        </Button>
                                    </div>
                                </PopoverTrigger>
                                <PopoverContent
                                    align="start"
                                    className="w-[400px] p-0"
                                >
                                    <Command>
                                        <CommandInput placeholder="Search customer..." />
                                        <CommandList>
                                            <CommandEmpty>
                                                No framework found.
                                            </CommandEmpty>
                                            <CommandGroup>
                                                {customers.map((customer) => (
                                                    <CommandItem
                                                        key={customer.id}
                                                        value={customer.id.toString()}
                                                        onSelect={(
                                                            currentValue,
                                                        ) => {
                                                            // setValue(currentValue === value ? "" : currentValue)
                                                            form.setData(
                                                                "customerId",
                                                                currentValue,
                                                            );
                                                            setOpen(false);
                                                        }}
                                                    >
                                                        <Check
                                                            className={cn(
                                                                "mr-2 h-4 w-4",
                                                                form.data
                                                                    .customerId ===
                                                                    customer.id.toString()
                                                                    ? "opacity-100"
                                                                    : "opacity-0",
                                                            )}
                                                        />
                                                        {customer.name}
                                                    </CommandItem>
                                                ))}
                                            </CommandGroup>
                                        </CommandList>
                                    </Command>
                                </PopoverContent>
                            </Popover>
                            {form.errors.customerId && (
                                <p className="text-destructive">
                                    {form.errors.customerId}
                                </p>
                            )}
                        </div>
                        <div className="w-full">
                            <Label>Issue Date</Label>
                            <Input disabled value={form.data.issueAt} />
                            {form.errors.issueAt && (
                                <p className="text-destructive">
                                    {form.errors.issueAt}
                                </p>
                            )}
                        </div>
                    </div>
                    <div className="flex flex-col gap-2 md:flex-row">
                        <div className="w-1/2">
                            <Label>Amount</Label>
                            <Input
                                value={form.data.amount}
                                onChange={(e) =>
                                    form.setData("amount", e.target.value)
                                }
                            />
                            {form.errors.amount && (
                                <p className="text-destructive">
                                    {form.errors.amount}
                                </p>
                            )}
                        </div>
                    </div>
                    <Button type="submit" disabled={form.processing}>
                        {form.processing && (
                            <Loader2 className="mr-2 h-4 w-4 animate-spin" />
                        )}
                        Submit
                    </Button>
                </form>
            </DashboardLayout>
        </>
    );
}
