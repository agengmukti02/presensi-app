import React, { useState } from "react";
import { Head, router } from "@inertiajs/react";
import DashboardLayout from "../../Layouts/DashboardLayout";
import AttendanceTable from "./AttendanceTable";
import StatCard from "../../Components/StatCard";

export default function PegawaiDashboard({
    days = [],
    rows = [],
    currentMonth,
    currentYear,
    employee,
    stats = {},
    hasCheckedInToday = false,
    todayAttendance = null
}) {
    const [month, setMonth] = useState(currentMonth || new Date().getMonth() + 1);
    const [year, setYear] = useState(currentYear || new Date().getFullYear());
    const [isCheckingIn, setIsCheckingIn] = useState(false);
    const [checkInMessage, setCheckInMessage] = useState('');
    const [showMessage, setShowMessage] = useState(false);

    const handleFilterChange = () => {
        window.location.href = `/dashboard?month=${month}&year=${year}`;
    };

    const handleCheckIn = () => {
        setIsCheckingIn(true);
        setCheckInMessage('');
        setShowMessage(false);

        router.post('/presensi/hadir', {}, {
            preserveScroll: true,
            onSuccess: () => {
                setCheckInMessage('Presensi berhasil dicatat.');
                setShowMessage(true);
                setTimeout(() => {
                    router.reload();
                }, 1500);
            },
            onError: (errors) => {
                const errorMessage = errors.message || Object.values(errors)[0] || 'Terjadi kesalahan saat melakukan presensi';
                setCheckInMessage(errorMessage);
                setShowMessage(true);
                setIsCheckingIn(false);
            },
            onFinish: () => {
                // Don't set isCheckingIn to false here on success, let the reload handle it
            },
        });
    };

    const formatTime = (timeString) => {
        if (!timeString) return '';
        const time = new Date(`2000-01-01 ${timeString}`);
        return time.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
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

            {/* Tombol Hadir / Status Presensi Hari Ini */}
            <div className="mb-6">
                {showMessage && (
                    <div className={`p-4 rounded-lg mb-4 ${checkInMessage.includes('berhasil')
                        ? 'bg-green-100 border border-green-400 text-green-700'
                        : 'bg-red-100 border border-red-400 text-red-700'
                        }`}>
                        <p className="font-medium">{checkInMessage}</p>
                    </div>
                )}

                {!hasCheckedInToday ? (
                    <div className="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                        <div className="flex items-center justify-between">
                            <div>
                                <h3 className="text-lg font-semibold text-gray-800 mb-1">
                                    Presensi Hari Ini
                                </h3>
                                <p className="text-gray-600 text-sm">
                                    Klik tombol di samping untuk melakukan presensi hadir
                                </p>
                            </div>
                            <button
                                onClick={handleCheckIn}
                                disabled={isCheckingIn}
                                className={`px-6 py-3 rounded-lg font-semibold text-white transition-all duration-200 flex items-center gap-2 ${isCheckingIn
                                    ? 'bg-gray-400 cursor-not-allowed'
                                    : 'bg-green-600 hover:bg-green-700 hover:shadow-lg'
                                    }`}
                            >
                                {isCheckingIn ? (
                                    <>
                                        <svg className="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Menyimpan...
                                    </>
                                ) : (
                                    <>
                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Hadir
                                    </>
                                )}
                            </button>
                        </div>
                    </div>
                ) : (
                    <div className="bg-green-50 rounded-lg shadow-md p-6 border-l-4 border-green-500">
                        <div className="flex items-center gap-3">
                            <div className="bg-green-500 rounded-full p-3">
                                <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 className="text-lg font-semibold text-green-800">
                                    Anda Sudah Hadir Hari Ini
                                </h3>
                                <p className="text-green-700 text-sm">
                                    Jam masuk: <span className="font-semibold">{formatTime(todayAttendance?.time_in)}</span>
                                    {todayAttendance?.time_out && (
                                        <> | Estimasi jam pulang: <span className="font-semibold">{formatTime(todayAttendance?.time_out)}</span></>
                                    )}
                                </p>
                            </div>
                        </div>
                    </div>
                )}
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
