import Pagination from "@/Components/Pagination";
import AdminAuthenticatedLayout from "@/Layouts/AdminAuthenticatedLayout";
import { Head } from "@inertiajs/react";

export default function Users({ users }) {
    console.log(users);
    return (
        <AdminAuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Users
                </h2>
            }
        >
            <Head title="Users" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <h2 className="text-xl font-semibold leading-tight text-gray-800 mb-6">
                                Users List
                            </h2>

                            <table className="min-w-full table-auto">
                                <thead>
                                    <tr>
                                        <th className="px-4 py-2 text-left">
                                            Name
                                        </th>
                                        <th className="px-4 py-2 text-left">
                                            Email
                                        </th>
                                        <th className="px-4 py-2 text-left">
                                            Created At
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {users.data.map((user) => (
                                        <tr key={user.id}>
                                            <td className="border px-4 py-2">
                                                {user.name}
                                            </td>
                                            <td className="border px-4 py-2">
                                                {user.email}
                                            </td>
                                            <td className="border px-4 py-2">
                                                {user.created_at}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>

                            {/* Pagination Controls */}
                            <Pagination links={users?.links || []} />
                        </div>
                    </div>
                </div>
            </div>
        </AdminAuthenticatedLayout>
    );
}
