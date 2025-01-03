import { Button } from "@/components/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Customer } from "@/types/customer";
import { Loader } from "lucide-react";
import useEditCustomer from "../hooks/useEditCustomer";
import { usePage } from "@inertiajs/react";

export default function CustomerEdit({ customer }: { customer: Customer }) {
    const canEdit = usePage().props.auth.roles.includes("admin");

    const { data, setData, onSubmit, processing, errors } = useEditCustomer({
        customer,
    });

    return (
        <Card className="flex-1">
            <CardHeader>
                <CardTitle>Customer Summary</CardTitle>
            </CardHeader>
            <CardContent>
                <form onSubmit={onSubmit} className="grid gap-4">
                    <div className="space-y-2">
                        <Label htmlFor="name">Name as NRIC</Label>
                        <Input
                            id="name"
                            type="text"
                            name="name"
                            disabled={!canEdit}
                            value={data.name}
                            className={
                                errors.name &&
                                "border-destructive/80 text-destructive focus-visible:border-destructive/80 focus-visible:ring-destructive/30"
                            }
                            autoComplete="name"
                            onChange={(e) => setData("name", e.target.value)}
                        />

                        {errors.name && (
                            <p
                                className="mt-2 text-xs text-destructive"
                                role="alert"
                                aria-live="polite"
                            >
                                {errors.name}
                            </p>
                        )}
                    </div>
                    <div className="grid grid-cols-2 gap-4">
                        <div className="space-y-2">
                            <Label htmlFor="uuid">NRIC</Label>
                            <Input
                                id="uuid"
                                type="text"
                                name="uuid"
                                disabled={!canEdit}
                                value={data.uuid}
                                className={
                                    errors.uuid &&
                                    "border-destructive/80 text-destructive focus-visible:border-destructive/80 focus-visible:ring-destructive/30"
                                }
                                autoComplete="uuid"
                                onChange={(e) =>
                                    setData("uuid", e.target.value)
                                }
                            />
                            {errors.uuid && (
                                <p
                                    className="mt-2 text-xs text-destructive"
                                    role="alert"
                                    aria-live="polite"
                                >
                                    {errors.uuid}
                                </p>
                            )}
                        </div>
                        <div className="space-y-2">
                            <Label htmlFor="phone">Mobile No</Label>
                            <Input
                                id="phone"
                                type="text"
                                name="phone"
                                disabled={!canEdit}
                                value={data.phone ?? ""}
                                className={
                                    errors.phone &&
                                    "border-destructive/80 text-destructive focus-visible:border-destructive/80 focus-visible:ring-destructive/30"
                                }
                                autoComplete="Mobile No"
                                onChange={(e) =>
                                    setData("phone", e.target.value)
                                }
                            />
                            {errors.phone && (
                                <p
                                    className="mt-2 text-xs text-destructive"
                                    role="alert"
                                    aria-live="polite"
                                >
                                    {errors.phone}
                                </p>
                            )}
                        </div>
                    </div>

                    <div className="space-y-2">
                        <Label htmlFor="email">Email</Label>
                        <Input
                            id="email"
                            type="email"
                            name="email"
                            disabled={!canEdit}
                            value={data.email}
                            className={
                                errors.email &&
                                "border-destructive/80 text-destructive focus-visible:border-destructive/80 focus-visible:ring-destructive/30"
                            }
                            autoComplete="email"
                            onChange={(e) => setData("email", e.target.value)}
                        />
                        {errors.email && (
                            <p
                                className="mt-2 text-xs text-destructive"
                                role="alert"
                                aria-live="polite"
                            >
                                {errors.email}
                            </p>
                        )}
                    </div>

                    {canEdit && (
                        <div className="">
                            <Button disabled={processing} type="submit">
                                {processing && (
                                    <Loader className="h-4 w-4 animate-spin" />
                                )}
                                Update
                            </Button>
                        </div>
                    )}
                </form>
            </CardContent>
        </Card>
    );
}
