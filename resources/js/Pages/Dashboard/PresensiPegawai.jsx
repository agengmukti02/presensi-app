import React, { useState } from "react";
import { Head } from "@inertiajs/react";
import DashboardLayout from "../../Layouts/DashboardLayout";

export default function PresensiPegawai({ days = [], rows = [], currentMonth, currentYear, employee }) {
    const [month, setMonth] = useState(currentMonth || new Date().getMonth() + 1);
    const [year, setYear] = useState(currentYear || new Date().getFullYear());

    const handleFilterChange = () => {
        window.location.href = `/presensi/pegawai?month=${month}&year=${year}`;
    };

    // Helper to get status for a specific day
    const getStatusForDay = (day) => {
        if (!rows.length) return null;
        const row = rows[0]; // Since we are viewing single employee
        const val = row[`d${day}`];

        if (!val) return null;

        if (val === "sakit") return { type: "sakit", label: "Sakit", color: "bg-yellow-100 text-yellow-800 border-yellow-200" };
        if (val === "izin") return { type: "izin", label: "Izin", color: "bg-blue-100 text-blue-800 border-blue-200" };
        if (val === "dd") return { type: "dd", label: "Dinas Dalam", color: "bg-purple-100 text-purple-800 border-purple-200" };
        if (val === "dl") return { type: "dl", label: "Dinas Luar", color: "bg-orange-100 text-orange-800 border-orange-200" };

        // Assuming val is time string like "08:00"
        return { type: "hadir", label: val, color: "bg-green-100 text-green-800 border-green-200" };
    };

    // Generate calendar grid
    const daysInMonth = new Date(year, month, 0).getDate();
    const firstDayOfMonth = new Date(year, month - 1, 1).getDay(); // 0 = Sunday

    // Adjust for Monday start if needed, but standard calendar usually starts Sunday
    // Let's stick to standard Sunday start for grid alignment
    const emptyDays = Array.from({ length: firstDayOfMonth }, (_, i) => i);
    const calendarDays = Array.from({ length: daysInMonth }, (_, i) => i + 1);

    return (
        <DashboardLayout>
            <Head title="Presensi Saya" />

            {/* Header Identity */}
            <div className="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
                <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h2 className="text-2xl font-bold text-gray-800">Presensi Saya</h2>
                        <div className="mt-1 text-gray-600">
                            <div className="font-medium text-lg">{employee?.nama}</div>
                            <div className="text-sm opacity-75">{employee?.nip}</div>
                        </div>
                    </div>

                    {/* Filters & Actions */}
                    <div className="flex flex-wrap gap-3 items-center">
                        <select
                            value={month}
                            onChange={(e) => setMonth(e.target.value)}
                            className="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                        >
                            {Array.from({ length: 12 }, (_, i) => (
                                <option key={i + 1} value={i + 1}>
                                    {new Date(0, i).toLocaleString("id-ID", { month: "long" })}
                                </option>
                            ))}
                        </select>
                        <select
                            value={year}
                            onChange={(e) => setYear(e.target.value)}
                            className="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                        >
                            {Array.from({ length: 5 }, (_, i) => (
                                <option key={i} value={new Date().getFullYear() - 2 + i}>
                                    {new Date().getFullYear() - 2 + i}
                                </option>
                            ))}
                        </select>
                        <button
                            onClick={handleFilterChange}
                            className="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors"
                        >
                            Tampilkan
                        </button>

                        <div className="h-6 w-px bg-gray-300 mx-1 hidden md:block"></div>

                        <a
                            href={`/api/attendances/export/excel/pegawai/${month}/${year}`}
                            target="_blank"
                            className="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors flex items-center gap-2"
                        >
                            <span>Excel</span>
                        </a>
                        <a
                            href={`/api/attendances/export/pdf/pegawai/${month}/${year}`}
                            target="_blank"
                            className="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition-colors flex items-center gap-2"
                        >
                            <span>PDF</span>
                        </a>
                    </div>
                </div>
            </div>

            {/* Calendar View */}
            <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                {/* Calendar Header */}
                <div className="grid grid-cols-7 bg-gray-50 border-b border-gray-200">
                    {['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'].map((day) => (
                        <div key={day} className="py-3 text-center text-sm font-semibold text-gray-600">
                            <span className="hidden md:inline">{day}</span>
                            <span className="md:hidden">{day.substring(0, 3)}</span>
                        </div>
                    ))}
                </div>

                {/* Calendar Grid */}
                <div className="grid grid-cols-7 auto-rows-fr bg-gray-200 gap-px">
                    {/* Empty cells for previous month */}
                    {emptyDays.map((_, i) => (
                        <div key={`empty-${i}`} className="bg-white min-h-[100px] p-2 opacity-50"></div>
                    ))}

                    {/* Days of current month */}
                    {calendarDays.map((day) => {
                        const status = getStatusForDay(day);
                        const isWeekend = new Date(year, month - 1, day).getDay() === 0 || new Date(year, month - 1, day).getDay() === 6;

                        return (
                            <div key={day} className={`bg-white min-h-[100px] p-2 transition-colors hover:bg-gray-50 ${isWeekend ? 'bg-gray-50/50' : ''}`}>
                                <div className="flex justify-between items-start">
                                    <span className={`text-sm font-medium h-7 w-7 flex items-center justify-center rounded-full ${status ? 'bg-gray-100 text-gray-900' : 'text-gray-500'
                                        }`}>
                                        {day}
                                    </span>
                                </div>

                                <div className="mt-2">
                                    {status ? (
                                        <div className={`text-xs p-1.5 rounded border ${status.color} font-medium text-center`}>
                                            {status.label}
                                        </div>
                                    ) : (
                                        !isWeekend && (
                                            <div className="text-xs text-center text-gray-400 py-1">
                                                -
                                            </div>
                                        )
                                    )}
                                </div>
                            </div>
                        );
                    })}
                </div>
            </div>

            {/* Legend / Labels */}
            <div className="mt-6 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 className="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wider">Keterangan Status</h3>
                <div className="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div className="flex items-center gap-3">
                        <div className="w-4 h-4 rounded bg-green-100 border border-green-200"></div>
                        <span className="text-sm text-gray-600">Hadir (Jam Masuk)</span>
                    </div>
                    <div className="flex items-center gap-3">
                        <div className="w-4 h-4 rounded bg-yellow-100 border border-yellow-200"></div>
                        <span className="text-sm text-gray-600">Sakit</span>
                    </div>
                    <div className="flex items-center gap-3">
                        <div className="w-4 h-4 rounded bg-blue-100 border border-blue-200"></div>
                        <span className="text-sm text-gray-600">Izin</span>
                    </div>
                    <div className="flex items-center gap-3">
                        <div className="w-4 h-4 rounded bg-purple-100 border border-purple-200"></div>
                        <span className="text-sm text-gray-600">Dinas Dalam</span>
                    </div>
                    <div className="flex items-center gap-3">
                        <div className="w-4 h-4 rounded bg-orange-100 border border-orange-200"></div>
                        <span className="text-sm text-gray-600">Dinas Luar</span>
                    </div>
                </div>
            </div>
        </DashboardLayout>
    );
}
