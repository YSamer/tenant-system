import { Link } from "@inertiajs/react";

export default function Pagination({ links }) {
    return (
        <nav className="text-center mt-4">
            {links.map((link) => (
                <Link
                    preserveScroll={!link.url}
                    href={link.url || ""}
                    key={link.label}
                    className={
                        "inline-block py-2 px-3 mx-1 rounded-lg text-xs " +
                        (link.active
                            ? "text-white bg-gray-950 "
                            : "text-gray-400 ") +
                        (!link.url
                            ? "!text-gray-500 cursor-not-allowed "
                            : "hover:bg-gray-950")
                    }
                    dangerouslySetInnerHTML={{ __html: link.label }}
                ></Link>
            ))}
        </nav>
    );
}
