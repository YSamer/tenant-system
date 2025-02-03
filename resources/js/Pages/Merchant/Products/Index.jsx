import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";
import Pagination from "@/Components/Pagination";

export default function Index({ products }) {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Products
                </h2>
            }
        >
            <Head title="Products" />
            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <h2 className="text-xl font-semibold leading-tight text-gray-800 mb-6">
                                Product List
                            </h2>
                            <Link
                                href={route("merchant.products.create")}
                                className="bg-blue-500 text-white py-2 px-4 rounded mb-4"
                            >
                                Create Product
                            </Link>

                            <table className="min-w-full table-auto">
                                <thead>
                                    <tr>
                                        <th className="px-4 py-2 text-left">
                                            Product Name
                                        </th>
                                        <th className="px-4 py-2 text-left">
                                            Category
                                        </th>
                                        <th className="px-4 py-2 text-left">
                                            Store
                                        </th>
                                        <th className="px-4 py-2 text-left">
                                            Price
                                        </th>
                                        <th className="px-4 py-2 text-left">
                                            Created At
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {products?.data?.map((product) => (
                                        <tr key={product.id}>
                                            <td className="border px-4 py-2">
                                                {product.name}
                                            </td>
                                            <td className="border px-4 py-2">
                                                {product.category.name}
                                            </td>
                                            <td className="border px-4 py-2">
                                                {product.store.name}
                                            </td>
                                            <td className="border px-4 py-2">
                                                {product.price}
                                            </td>
                                            <td className="border px-4 py-2">
                                                {new Date(
                                                    product.created_at
                                                ).toLocaleDateString()}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                            <Pagination links={products?.links || []} />
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
