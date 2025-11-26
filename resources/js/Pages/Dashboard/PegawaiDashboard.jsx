import React, { useState } from "react";
import { Head } from "@inertiajs/react";
import DashboardLayout from "../../Layouts/DashboardLayout";
import AttendanceTable from "./AttendanceTable";
import StatCard from "../../Components/StatCard";

export default function PegawaiDashboard({ days = [], rows = [], currentMonth, currentYear, employee, stats = {} }) {
    const [month, setMonth] = useState(currentMonth || new Date().getMonth() + 1);
    const [year, setYear] = useState(currentYear || new Date().getFullYear());

    const handleFilterChange = () => {
        window.location.href = `/dashboard?month=${month}&year=${year}`;
    };

    return (
        <DashboardLayout>
            <Head title="Dashboard Pegawai" />

            <div className="mb-6">
                <h2 className="text-2xl font-bold text-gray-800 mb-2">Dashboard Pegawai</h2>
                <p className="text-gray-600">
                    {employee?.nama} ({employee?.nip})
                </p>
            </div>

            {/* Statistik Pribadi */}
            <div className="mb-6">
                <h3 className="text-lg font-semibold text-gray-800 mb-4">
                    Statistik Bulan {new Date(0, currentMonth - 1).toLocaleString("id-ID", { month: "long" })} {currentYear}
                </h3>
                <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    <StatCard
                        title="Hadir"
                        value={stats.hadir || 0}
                        icon="✓"
                        color="green"
                        subtitle={`${stats.persentaseKehadiran || 0}% dari ${stats.totalHariKerja || 0} hari kerja`}
                    />
                    <StatCard
                        title="Sakit"
                        value={stats.sakit || 0}
                        icon="🏥"
                        color="yellow"
                    />
                    <StatCard
                        title="Izin"
                        value={stats.izin || 0}
                        icon="📝"
                        color="blue"
                    />
                    <StatCard
                        title="Terlambat"
                        value={stats.terlambat || 0}
                        icon="⏰"
                        color="orange"
                    />
                    <StatCard
                        title="Tidak Hadir"
                        value={stats.tidakHadir || 0}
                        icon="✗"
                        color="red"
                    />
                </div>
                {(stats.dinasDalam > 0 || stats.dinasLuar > 0) && (
                    <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mt-4">
                        {stats.dinasDalam > 0 && (
                            <StatCard
                                title="Dinas Dalam"
                                value={stats.dinasDalam}
                                icon="🏢"
                                color="purple"
                            />
                        )}
                        {stats.dinasLuar > 0 && (
                            <StatCard
                                title="Dinas Luar"
                                value={stats.dinasLuar}
                                icon="✈️"
                                color="purple"
                            />
                        )}
                    </div>
                )}
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
