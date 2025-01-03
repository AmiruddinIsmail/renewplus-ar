import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Customer } from "@/types/customer";
import { Order } from "@/types/order";

type Props = {
    order: Order;
};

export default function CustomerOrder({ order }: Props) {
    return (
        <Card className="flex-1">
            <CardHeader>
                <CardTitle>Order Summary</CardTitle>
            </CardHeader>
            <CardContent>
                <form className="grid gap-4">
                    <div className="grid gap-4 grid-cols-2">
                        <div className="grid gap-2">
                            <Label htmlFor="reference_no">Order No</Label>
                            <Input
                                id="reference_no"
                                type="text"
                                value={order?.reference_no}
                                disabled
                                className="mt-1 block w-full"
                            />
                        </div>
                        <div className="grid gap-2">
                            <Label htmlFor="contract_at">Contract Date</Label>
                            <Input
                                id="contract_at"
                                type="text"
                                disabled
                                value={order?.contract_at}
                                className="mt-1 block w-full"
                            />
                        </div>
                    </div>

                    <div className="grid gap-4 grid-cols-2">
                        <div className="grid gap-2">
                            <Label htmlFor="tenure">Tenure</Label>
                            <Input
                                id="tenure"
                                type="text"
                                disabled
                                value={`${order?.tenure} Months`}
                                className="mt-1 block w-full"
                            />
                        </div>
                        <div className="grid gap-2">
                            <Label htmlFor="subscription_amount">
                                Monthly Fee (RM)
                            </Label>
                            <Input
                                id="subscription_amount"
                                type="text"
                                value={`RM ${order?.subscription_amount}`}
                                disabled
                                className="mt-1 block w-full"
                            />
                        </div>
                    </div>

                    <div className="grid gap-4 grid-cols-2">
                        <div className="grid gap-2">
                            <Label htmlFor="payment_gateway">
                                Payment Provider
                            </Label>
                            <Input
                                id="payment_gateway"
                                type="text"
                                value={order?.payment_gateway}
                                disabled
                                className="mt-1 block w-full"
                            />
                        </div>
                        <div className="grid gap-2">
                            <Label htmlFor="payment_reference">
                                Payment Reference
                            </Label>
                            <Input
                                id="payment_reference"
                                type="text"
                                disabled
                                value={order?.payment_reference}
                                className="mt-1 block w-full"
                            />
                        </div>
                    </div>
                </form>
            </CardContent>
        </Card>
    );
}
