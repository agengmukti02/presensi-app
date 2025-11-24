import React, { useState, useEffect } from "react";
import { Head, router } from "@inertiajs/react";
import DashboardLayout from "../../Layouts/DashboardLayout";
import AttendanceTable from "./AttendanceTable";
import SearchForm from "./SearchForm";

export default function Dashboard({ days = [], rows = [], currentMonth, currentYear }) {
    const [search, setSearch] = useState("");
    const [filteredRows, setFilteredRows] = useState(rows);
    const [month, setMonth] = useState(currentMonth || new Date().getMonth() + 1);
    const [year, setYear] = useState(currentYear || new Date().getFullYear());

    useEffect(() => {
        if (rows.length > 0) {
            setFilteredRows(
                rows.filter((r) =>
                    (r.name + r.nip).toLowerCase().includes(search.toLowerCase())
                )
            );
        }
    }, [search, rows]);

    const handleFilterChange = () => {
        router.visit(`/dashboard?month=${month}&year=${year}`, {
            preserveState: true,
            preserveScroll: true,
            only: ["days", "rows", "currentMonth", "currentYear"],
        });
    };

    return (
        <DashboardLayout>
            <Head title="Dashboard" />

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
                        href={`/api/attendances/export/excel/${month}/${year}`}
                        target="_blank"
                        className="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 flex items-center gap-2"
                    >
                        Excel
                    </a>
                    <a
                        href={`/api/attendances/export/pdf/${month}/${year}`}
                        target="_blank"
                        className="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 flex items-center gap-2"
                    >
                        PDF
                    </a>
                </div>
            </div>

            <SearchForm onSearch={setSearch} />

            <AttendanceTable rows={filteredRows} days={days} />
        </DashboardLayout>
    );
}
