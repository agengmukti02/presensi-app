import React from "react";
import Sidebar from "../Pages/Dashboard/Sidebar";
import { usePage } from "@inertiajs/react";

export default function DashboardLayout({ children }) {
    const { props } = usePage();

    return (
        <div className="flex h-screen bg-gray-100">
            <Sidebar />

            <main className="flex-1 flex flex-col overflow-hidden">
                <header className="px-6 py-4 bg-white shadow-sm border-b flex justify-between items-center">
                    <h2 className="text-xl font-semibold">Dashboard Presensi</h2>
                    <div className="text-gray-600">
                        Halo, <span className="font-bold">{props.auth.user.name}</span>
                    </div>
                </header>

                <div className="flex-1 overflow-y-auto p-6">
                    {children}
                </div>
            </main>
        </div>
    );
}
