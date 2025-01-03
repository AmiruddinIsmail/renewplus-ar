import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableFooter,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";
import CustomerOrder from "@/features/customers/components/customer-order";
import { Invoice } from "@/types/invoice";
import { Payment } from "@/types/payment";

type Props = {
    payment: Payment;
};

export default function PaymentDetail({ payment }: Props) {
    return (
        <div className="grid gap-4">
            <div className="grid gap-4 grid-cols-2">
                <Card className="flex-1">
                    <CardHeader>
                        <CardTitle>Payment Detail</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="grid gap-4">
                            <div className="grid gap-4 grid-cols-2">
                                <div className="grid gap-2">
                                    <Label htmlFor="reference_no">
                                        Reference No
                                    </Label>
                                    <Input
                                        id="reference_no"
                                        type="text"
                                        value={payment.reference_no}
                                        disabled
                                        className="mt-1 block w-full"
                                    />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="contract_at">
                                        Issue Date
                                    </Label>
                                    <Input
                                        id="contract_at"
                                        type="text"
                                        disabled
                                        value={payment.paid_at}
                                        className="mt-1 block w-full"
                                    />
                                </div>
                            </div>

                            <div className="grid gap-4 grid-cols-2">
                                <div className="grid gap-2">
                                    <Label htmlFor="tenure">Amount (RM)</Label>
                                    <Input
                                        id="tenure"
                                        type="text"
                                        disabled
                                        value={payment.amount}
                                        className="mt-1 block w-full"
                                    />
                                </div>
                            </div>

                            <div className="grid gap-4 grid-cols-2">
                                <div className="grid gap-2">
                                    <Label htmlFor="tenure">Status</Label>
                                    <Input
                                        id="tenure"
                                        type="text"
                                        disabled
                                        value={
                                            payment.unresolved
                                                ? "Unresolved"
                                                : "Resolved"
                                        }
                                        className="mt-1 block w-full"
                                    />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="subscription_amount">
                                        Unresolved Amount (RM)
                                    </Label>
                                    <Input
                                        id="subscription_amount"
                                        type="text"
                                        value={payment.unresolved_amount}
                                        disabled
                                        className="mt-1 block w-full"
                                    />
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
                <CustomerOrder order={payment.order} />
            </div>

            <div className="grid gap-4 grid-cols-2">
                {payment.invoices && payment.invoices.length > 0 && (
                    <Card className="flex-1">
                        <CardHeader>
                            <CardTitle>List of Invoices</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="grid gap-4">
                                <Table>
                                    <TableHeader>
                                        <TableRow>
                                            <TableHead>Reference No</TableHead>
                                            <TableHead>Issue At</TableHead>
                                            <TableHead className="text-right">
                                                Amount
                                            </TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        {payment.invoices.map(
                                            (invoice: Invoice) => (
                                                <TableRow key={invoice.id}>
                                                    <TableCell className="font-medium">
                                                        {invoice.reference_no}
                                                    </TableCell>
                                                    <TableCell>
                                                        {invoice.issue_at}
                                                    </TableCell>
                                                    <TableCell className="text-right">
                                                        {invoice.pivot.amount}
                                                    </TableCell>
                                                </TableRow>
                                            ),
                                        )}
                                    </TableBody>
                                </Table>
                            </div>
                        </CardContent>
                    </Card>
                )}
            </div>
        </div>
    );
}
