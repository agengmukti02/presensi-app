import React, { useState } from "react";
import { Head, router } from "@inertiajs/react";
import DashboardLayout from "../../Layouts/DashboardLayout";

export default function AdminDashboard({ stats, selectedDate }) {
    const [date, setDate] = useState(selectedDate || new Date().toISOString().split('T')[0]);

    const handleDateChange = () => {
        router.visit(`/dashboard?date=${date}`, {
            preserveState: false,
        });
    };

    const cards = [
        {
            title: "Total Pegawai",
            value: stats.totalPegawai,
            color: "bg-blue-600",
            icon: "👥",
        },
        {
            title: "Hadir",
            value: stats.hadir,
            color: "bg-green-600",
            icon: "✅",
        },
        {
            title: "Sakit",
            value: stats.sakit,
            color: "bg-yellow-600",
            icon: "🤒",
        },
        {
            title: "Izin",
            value: stats.izin,
            color: "bg-orange-600",
            icon: "📝",
        },
        {
            title: "Dinas Dalam",
            value: stats.dinasDalam,
            color: "bg-purple-600",
            icon: "🏢",
        },
        {
            title: "Dinas Luar",
            value: stats.dinasLuar,
            color: "bg-indigo-600",
            icon: "✈️",
        },
        {
            title: "Tidak Hadir",
            value: stats.tidakHadir,
            color: "bg-red-600",
            icon: "❌",
        },
    ];

    return (
        <DashboardLayout>
            <Head title="Dashboard Admin" />

            <div className="mb-6 flex gap-2 items-center">
                <label className="text-gray-700 font-medium">Pilih Tanggal:</label>
                <input
                    type="date"
                    value={date}
                    onChange={(e) => setDate(e.target.value)}
                    className="border rounded px-3 py-2"
                />
                <button
                    onClick={handleDateChange}
                    className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                >
                    Tampilkan
                </button>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {cards.map((card, index) => (
                    <div
                        key={index}
                        className="bg-white rounded-lg shadow-md overflow-hidden transform transition hover:shadow-xl hover:-translate-y-1 border border-gray-100"
                    >
                        <div className={`${card.color} h-1`}></div>
                        <div className="p-6">
                            <div className="flex flex-col items-center justify-center text-center">
                                <div className={`${card.color} bg-opacity-10 rounded-full p-4 mb-3`}>
                                    <span className="text-4xl">{card.icon}</span>
                                </div>
                                <p className="text-3xl font-bold text-gray-800 mb-2">{card.value}</p>
                                <h3 className="text-xs font-semibold text-gray-600 uppercase tracking-wide">
                                    {card.title}
                                </h3>
                            </div>
                        </div>
                    </div>
                ))}
            </div>

            <div className="mt-8 bg-white rounded-lg shadow p-6">
                <h3 className="text-xl font-semibold text-gray-800 mb-4">Ringkasan</h3>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p className="text-gray-600">Tanggal yang Dipilih:</p>
                        <p className="text-lg font-semibold text-gray-800">
                            {new Date(selectedDate).toLocaleDateString('id-ID', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            })}
                        </p>
                    </div>
                    <div>
                        <p className="text-gray-600">Persentase Kehadiran:</p>
                        <p className="text-lg font-semibold text-gray-800">
                            {stats.totalPegawai > 0
                                ? ((stats.hadir / stats.totalPegawai) * 100).toFixed(1)
                                : 0}%
                        </p>
                    </div>
                </div>
            </div>
        </DashboardLayout>
    );
}
