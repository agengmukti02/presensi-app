import React, { useState, useEffect } from "react";
import { Head } from "@inertiajs/react";
import DashboardLayout from "../../Layouts/DashboardLayout";
import AttendanceTable from "./AttendanceTable";

export default function PresensiPegawai({ days = [], rows = [], currentMonth, currentYear, employee }) {
    const [month, setMonth] = useState(currentMonth || new Date().getMonth() + 1);
    const [year, setYear] = useState(currentYear || new Date().getFullYear());

    const handleFilterChange = () => {
        window.location.href = `/presensi/pegawai?month=${month}&year=${year}`;
    };

    return (
        <DashboardLayout>
            <Head title="Presensi Saya" />

            <div className="mb-6">
                <h2 className="text-2xl font-bold text-gray-800 mb-2">Presensi Saya</h2>
                <p className="text-gray-600">
                    {employee?.nama} ({employee?.nip})
                </p>
            </div>

            <div className="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div className="flex gap-2 items-center">
                    <select
                        value={month}
                        onChange={(e) => setMonth(e.target.value)}
                        className="border rounded px-3 py-2"
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
                        className="border rounded px-3 py-2"
                    >
                        {Array.from({ length: 5 }, (_, i) => (
                            <option key={i} value={new Date().getFullYear() - 2 + i}>
                                {new Date().getFullYear() - 2 + i}
                            </option>
                        ))}
                    </select>
                    <button
                        onClick={handleFilterChange}
                        className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                    >
                        Tampilkan
                    </button>
                </div>

                <div className="flex gap-2">
                    <a
                        href={`/api/attendances/export/excel/pegawai/${month}/${year}`}
                        target="_blank"
                        className="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 flex items-center gap-2"
                    >
                        Excel
                    </a>
                    <a
                        href={`/api/attendances/export/pdf/pegawai/${month}/${year}`}
                        target="_blank"
                        className="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 flex items-center gap-2"
                    >
                        PDF
                    </a>
                </div>
            </div>

            <AttendanceTable rows={rows} days={days} />
        </DashboardLayout>
    );
}
