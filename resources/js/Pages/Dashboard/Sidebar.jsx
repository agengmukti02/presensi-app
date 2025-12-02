import React from "react";
import { Link, usePage } from "@inertiajs/react";
import ApplicationLogo from "../../Components/ApplicationLogo";

export default function Sidebar() {
    const { url, props } = usePage();
    const user = props.auth.user;

    const links = [
        { name: "Dashboard", href: "/dashboard", roles: ["admin", "pegawai"] },
        { name: "Presensi", href: user.role === 'admin' ? "/presensi/admin" : "/presensi/pegawai", roles: ["admin", "pegawai"] },
        { name: "Pengajuan Izin", href: "/izin/create", roles: ["pegawai"] },
        { name: "Approval Izin", href: "/izin/approval", roles: ["admin"] },
    ];

    return (
        <aside className="w-64 bg-white shadow-md flex flex-col h-full">
            <div className="px-6 py-5 border-b flex items-center gap-3">
                <ApplicationLogo className="h-12 w-12" />
                <div>
                    <h1 className="text-lg font-bold text-blue-600">DISDUKCAPIL</h1>
                    <p className="text-xs text-gray-500">DIY Yogyakarta</p>
                </div>
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
