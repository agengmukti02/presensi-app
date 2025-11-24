import React from "react";
import { Link, usePage } from "@inertiajs/react";

export default function Sidebar() {
    const { url, props } = usePage();
    const user = props.auth.user;

    const links = [
        { name: "Dashboard", href: "/dashboard", roles: ["admin", "pegawai"] },
        { name: "Data Pegawai", href: "/pegawai", roles: ["admin"] },
        { name: "Presensi", href: "/presensi", roles: ["admin", "pegawai"] },
        { name: "Pengajuan Izin", href: "/izin/create", roles: ["pegawai"] },
        { name: "Approval Izin", href: "/izin/approval", roles: ["admin"] },
    ];

    return (
        <aside className="w-64 bg-white shadow-md flex flex-col h-full">
            <div className="px-6 py-5 border-b">
                <h1 className="text-xl font-bold text-blue-600">PRESENSI APP</h1>
            </div>

            <nav className="flex-1 p-4 space-y-2">
                {links.map((link) => {
                    if (!link.roles.includes(user.role)) return null;

                    return (
                        <Link
                            key={link.href}
                            href={link.href}
                            className={`block px-3 py-2 rounded-lg font-medium ${url.startsWith(link.href)
                                    ? "bg-blue-600 text-white"
                                    : "text-gray-700 hover:bg-gray-200"
                                }`}
                        >
                            {link.name}
                        </Link>
                    );
                })}
            </nav>
        </aside>
    );
}
