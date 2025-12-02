import React from "react";
import Sidebar from "../Pages/Dashboard/Sidebar";
import { usePage, router } from "@inertiajs/react";
import ApplicationLogo from "../Components/ApplicationLogo";

export default function DashboardLayout({ children }) {
    const { props } = usePage();
    const [isSidebarOpen, setIsSidebarOpen] = React.useState(false);

    const handleLogout = () => {
        router.post('/logout');
    };

    return (
        <div className="flex h-screen bg-gray-100">
            {/* Mobile Sidebar Overlay */}
            {isSidebarOpen && (
                <div
                    className="fixed inset-0 z-40 bg-black bg-opacity-50 md:hidden"
                    onClick={() => setIsSidebarOpen(false)}
                ></div>
            )}

            {/* Sidebar */}
            <div className={`fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out md:relative md:translate-x-0 ${isSidebarOpen ? 'translate-x-0' : '-translate-x-full'}`}>
                <Sidebar />
            </div>

            <main className="flex-1 flex flex-col overflow-hidden w-full">
                <header className="px-6 py-4 bg-white shadow-sm border-b flex justify-between items-center">
                    <div className="flex items-center gap-3">
                        <button
                            onClick={() => setIsSidebarOpen(!isSidebarOpen)}
                            className="md:hidden p-2 rounded-md text-gray-600 hover:bg-gray-100 focus:outline-none"
                        >
                            <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <ApplicationLogo className="h-8 w-8 hidden md:block" />
                        <h2 className="text-xl font-semibold">Dashboard Presensi</h2>
                    </div>
                    <div className="flex items-center gap-4">
                        <div className="text-gray-600 hidden md:block">
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
