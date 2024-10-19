import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Invoice } from "@/types/invoice";
import { Head, useForm } from "@inertiajs/react";

export default function ShowInvoice({ invoice }: { invoice: Invoice }) {
    const calculateTotal = () => {
        return (
            parseFloat(`${invoice.subscription_fee}`) +
            parseFloat(`${invoice.charge_fee}`) -
            (parseFloat(`${invoice.credit_paid}`) +
                parseFloat(`${invoice.over_paid}`))
        ).toFixed(2);
    };

    return (
        <>
            <DashboardLayout>
                <div className="flex flex-col gap-4">
                    <Head title="Create User" />
                    <div className="flex items-center justify-between">
                        <h1 className="text-lg font-semibold md:text-2xl">
                            Invoice Detail
                        </h1>
                    </div>

                    <div className="mt-4 flex flex-col gap-4">
                        {/* {JSON.stringify(invoice)}

                        <div>
                            <ul>
                                {invoice.payments.map(payment => (
                                    <li>{payment.reference_no} - {payment.paid_at} - {(payment.pivot.amount / 100).toFixed(2)}</li>
                                ))}
                            </ul>
                        </div>

                        <div>
                            <ul>
                                {invoice.charges.map(charge => (
                                    <li>{charge.reference_no} - {charge.charged_at} - {(charge.amount / 100).toFixed(2)}</li>
                                ))}
                            </ul>
                        </div> */}
                        <div className="mx-auto w-3/5 rounded-lg bg-white p-8 shadow-md">
                            <h1 className="mb-4 text-2xl font-bold">Invoice</h1>

                            <div className="mb-6 flex justify-between">
                                <div>
                                    <h2 className="text-lg font-semibold">
                                        Bill To:
                                    </h2>
                                    <p>{invoice.customer.name}</p>
                                    <p>123 Main St</p>
                                    <p>City, ST 12345</p>
                                    <p>Email: {invoice.customer.email}</p>
                                </div>
                                <div>
                                    <h2 className="text-lg font-semibold">
                                        Invoice #:
                                    </h2>
                                    <p>{invoice.reference_no}</p>
                                    <table>
                                        <tr>
                                            <td>Issue Date</td>
                                            <td>:</td>
                                            <td>{invoice.issue_at}</td>
                                        </tr>
                                        <tr>
                                            <td>Due Date</td>
                                            <td>:</td>
                                            <td>{invoice.due_at}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <table className="min-w-full border border-gray-300 bg-white">
                                <thead>
                                    <tr className="bg-gray-200">
                                        <th className="border-b px-4 py-2 text-left">
                                            Description
                                        </th>
                                        <th className="border-b px-4 py-2 text-right">
                                            Unit
                                        </th>
                                        <th className="border-b px-4 py-2 text-right">
                                            Total (RM)
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td className="border-b px-4 py-2">
                                            Monthly subscription
                                        </td>
                                        <td className="border-b px-4 py-2 text-right">
                                            1
                                        </td>
                                        <td className="border-b px-4 py-2 text-right">
                                            {invoice.subscription_fee}
                                        </td>
                                    </tr>
                                    {invoice.charge_fee > 0 && (
                                        <tr>
                                            <td className="border-b px-4 py-2">
                                                Late Charges
                                            </td>
                                            <td className="border-b px-4 py-2 text-right">
                                                {invoice.charges.length}
                                            </td>
                                            <td className="border-b px-4 py-2 text-right">
                                                {invoice.charge_fee}
                                            </td>
                                        </tr>
                                    )}
                                    {/* <tr className="bg-gray-200">
                                        <td className="py-2 px-4 border-b" colSpan={2}>Subtotal</td>
                                        <td className="py-2 px-4 border-b text-right">$1,250.00</td>
                                    </tr>
                                    <tr>
                                        <td className="py-2 px-4 border-b" colSpan={2}>Tax (5%)</td>
                                        <td className="py-2 px-4 border-b text-right">$62.50</td>
                                    </tr> */}
                                    <tr className="font-bold">
                                        <td
                                            className="border-b px-4 py-2"
                                            colSpan={2}
                                        >
                                            Total
                                        </td>
                                        <td className="border-b px-4 py-2 text-right">
                                            {calculateTotal()}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div className="mt-6">
                                <h2 className="text-lg font-semibold">
                                    Payment Instructions:
                                </h2>
                                <p>
                                    Please make the payment to the following
                                    account:
                                </p>
                                <p>Bank: XYZ Bank</p>
                                <p>Account Number: 123456789</p>
                                <p>IBAN: US00XYZ1234567890</p>
                            </div>

                            <div className="mt-8 text-center">
                                <p>Thank you for your business!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </DashboardLayout>
        </>
    );
}
