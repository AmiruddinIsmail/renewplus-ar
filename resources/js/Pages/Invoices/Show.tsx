import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Invoice } from "@/types/invoice";
import { Head, useForm } from "@inertiajs/react";

export default function ShowInvoice({ invoice }: { invoice: Invoice }) {

    const calculateTotal = () => {
        return (parseFloat(`${invoice.subscription_fee}`) + parseFloat(`${invoice.charge_fee}`) - (parseFloat(`${invoice.credit_paid}`) + parseFloat(`${invoice.over_paid}`))).toFixed(2);
    }

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
                        <div className="w-3/5 mx-auto bg-white shadow-md rounded-lg p-8">
                            <h1 className="text-2xl font-bold mb-4">Invoice</h1>

                            <div className="flex justify-between mb-6">
                                <div>
                                    <h2 className="text-lg font-semibold">Bill To:</h2>
                                    <p>{invoice.customer.name}</p>
                                    <p>123 Main St</p>
                                    <p>City, ST 12345</p>
                                    <p>Email: {invoice.customer.email}</p>
                                </div>
                                <div>
                                    <h2 className="text-lg font-semibold">Invoice #:</h2>
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

                            <table className="min-w-full bg-white border border-gray-300">
                                <thead>
                                    <tr className="bg-gray-200">
                                        <th className="py-2 px-4 border-b text-left">Description</th>
                                        <th className="py-2 px-4 border-b text-right">Unit</th>
                                        <th className="py-2 px-4 border-b text-right">Total (RM)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td className="py-2 px-4 border-b">Monthly subscription</td>
                                        <td className="py-2 px-4 border-b text-right">1</td>
                                        <td className="py-2 px-4 border-b text-right">{invoice.subscription_fee}</td>
                                    </tr>
                                    {invoice.charge_fee > 0 && (
                                        <tr>
                                            <td className="py-2 px-4 border-b">Late Charges</td>
                                            <td className="py-2 px-4 border-b text-right">{invoice.charges.length}</td>
                                            <td className="py-2 px-4 border-b text-right">{invoice.charge_fee}</td>
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
                                        <td className="py-2 px-4 border-b" colSpan={2}>Total</td>
                                        <td className="py-2 px-4 border-b text-right">{calculateTotal()}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div className="mt-6">
                                <h2 className="text-lg font-semibold">Payment Instructions:</h2>
                                <p>Please make the payment to the following account:</p>
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
