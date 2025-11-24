import React from "react";
import Sidebar from "../Pages/Dashboard/Sidebar";
import { usePage, router } from "@inertiajs/react";

export default function DashboardLayout({ children }) {
    const { props } = usePage();

    const handleLogout = () => {
        router.post('/logout');
    };

    return (
        <div className="flex h-screen bg-gray-100">
            <Sidebar />

            <main className="flex-1 flex flex-col overflow-hidden">
                <header className="px-6 py-4 bg-white shadow-sm border-b flex justify-between items-center">
                    <h2 className="text-xl font-semibold">Dashboard Presensi</h2>
                    <div className="flex items-center gap-4">
                        <div className="text-gray-600">
                            Halo, <span className="font-bold">{props.auth.user.name}</span>
                        </div>
                        <button
                            onClick={handleLogout}
                            className="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition-colors duration-200 text-sm font-medium"
                        >
                            Logout
                        </button>
                    </div>
                </header>

                <div className="flex-1 overflow-y-auto p-6">
                    {children}
                </div>
            </main>
        </div>
    );
}
