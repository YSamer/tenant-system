import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm } from "@inertiajs/react";

export default function Create({ stores }) {
    const { data, setData, post, processing, errors } = useForm({
        name: "",
        store_id: "", // Store selection
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route("merchant.categories.store"));
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Create Category
                </h2>
            }
        >
            <Head title="Create Category" />
            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <h2 className="text-xl font-semibold leading-tight text-gray-800 mb-6">
                                Create Category
                            </h2>

                            <form onSubmit={handleSubmit}>
                                <div className="mb-4">
                                    <label className="block text-sm font-medium text-gray-700">
                                        Category Name
                                    </label>
                                    <input
                                        type="text"
                                        name="name"
                                        value={data.name}
                                        onChange={(e) =>
                                            setData("name", e.target.value)
                                        }
                                        className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    />
                                    {errors.name && (
                                        <div className="text-red-500 text-sm mt-1">
                                            {errors.name}
                                        </div>
                                    )}
                                </div>

                                <div className="mb-4">
                                    <label className="block text-sm font-medium text-gray-700">
                                        Select Store
                                    </label>
                                    <select
                                        name="store_id"
                                        value={data.store_id}
                                        onChange={(e) =>
                                            setData("store_id", e.target.value)
                                        }
                                        className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    >
                                        <option value="">Select Store</option>
                                        {stores.map((store) => (
                                            <option
                                                key={store.id}
                                                value={store.id}
                                            >
                                                {store.name}
                                            </option>
                                        ))}
                                    </select>
                                    {errors.store_id && (
                                        <div className="text-red-500 text-sm mt-1">
                                            {errors.store_id}
                                        </div>
                                    )}
                                </div>

                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="bg-blue-500 text-white py-2 px-4 rounded"
                                >
                                    {processing
                                        ? "Creating..."
                                        : "Create Category"}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
