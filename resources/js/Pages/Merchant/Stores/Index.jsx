import Pagination from "@/Components/Pagination";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";

export default function Index({ stores }) {
    console.log(stores);
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Stores
                </h2>
            }
        >
            <Head title="Stores" />
            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <h2 className="text-xl font-semibold leading-tight text-gray-800 mb-6">
                                Store List
                            </h2>
                            <Link
                                href={route("merchant.stores.create")}
                                className="bg-blue-500 text-white py-2 px-4 rounded mb-4"
                            >
                                Create Store
                            </Link>

                            <table className="min-w-full table-auto">
                                <thead>
                                    <tr>
                                        <th className="px-4 py-2 text-left">
                                            Name
                                        </th>
                                        <th className="px-4 py-2 text-left">
                                            Created At
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {stores?.data?.map((store) => (
                                        <tr key={store.id}>
                                            <td className="border px-4 py-2">
                                                {store.name}
                                            </td>
                                            <td className="border px-4 py-2">
                                                {new Date(
                                                    store.created_at
                                                ).toLocaleDateString()}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                            {/* Pagination Controls */}
                            <Pagination links={stores?.links || []} />
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
