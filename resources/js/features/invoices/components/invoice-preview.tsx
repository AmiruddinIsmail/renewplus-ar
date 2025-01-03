import React from "react";

const InvoicePreview = () => {
    const invoiceData = {
        invoiceNumber: "INV-12345",
        date: "2024-11-09",
        dueDate: "2024-12-09",
        client: {
            name: "John Doe",
            address: "123 Main St, City, Country",
            email: "johndoe@example.com",
        },
        items: [
            { description: "Web Design", quantity: 1, price: 500 },
            { description: "Development", quantity: 2, price: 350 },
            { description: "SEO Optimization", quantity: 1, price: 150 },
        ],
    };

    const totalAmount = invoiceData.items.reduce(
        (total, item) => total + item.quantity * item.price,
        0,
    );

    return (
        <div className="max-w-3xl mx-auto p-8 bg-white shadow-lg rounded-lg">
            <div className="flex justify-between items-center mb-8">
                <div>
                    <h1 className="text-3xl font-bold text-gray-800">
                        Invoice
                    </h1>
                    <p className="text-gray-600">
                        Invoice Number: {invoiceData.invoiceNumber}
                    </p>
                    <p className="text-gray-600">Date: {invoiceData.date}</p>
                    <p className="text-gray-600">
                        Due Date: {invoiceData.dueDate}
                    </p>
                </div>
                <div className="text-right">
                    <p className="text-lg font-bold text-gray-800">From:</p>
                    <p className="text-gray-600">Your Company Name</p>
                    <p className="text-gray-600">
                        123 Business St, City, Country
                    </p>
                    <p className="text-gray-600">contact@yourcompany.com</p>
                </div>
            </div>

            <div className="mb-8">
                <p className="text-lg font-bold text-gray-800">Bill To:</p>
                <p className="text-gray-600">{invoiceData.client.name}</p>
                <p className="text-gray-600">{invoiceData.client.address}</p>
                <p className="text-gray-600">{invoiceData.client.email}</p>
            </div>

            <div className="overflow-x-auto">
                <table className="min-w-full table-auto">
                    <thead>
                        <tr>
                            <th className="px-4 py-2 text-left text-sm font-semibold text-gray-600">
                                Description
                            </th>
                            <th className="px-4 py-2 text-left text-sm font-semibold text-gray-600">
                                Quantity
                            </th>
                            <th className="px-4 py-2 text-left text-sm font-semibold text-gray-600">
                                Price
                            </th>
                            <th className="px-4 py-2 text-left text-sm font-semibold text-gray-600">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {invoiceData.items.map((item, index) => (
                            <tr key={index}>
                                <td className="px-4 py-2 text-sm text-gray-700">
                                    {item.description}
                                </td>
                                <td className="px-4 py-2 text-sm text-gray-700">
                                    {item.quantity}
                                </td>
                                <td className="px-4 py-2 text-sm text-gray-700">
                                    ${item.price}
                                </td>
                                <td className="px-4 py-2 text-sm text-gray-700">
                                    ${item.quantity * item.price}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>

            <div className="flex justify-end mt-8">
                <div className="w-1/3 text-right">
                    <p className="text-lg font-semibold text-gray-800">
                        Total Amount:
                    </p>
                    <p className="text-xl font-bold text-gray-900">
                        ${totalAmount.toFixed(2)}
                    </p>
                </div>
            </div>
        </div>
    );
};

export default InvoicePreview;
