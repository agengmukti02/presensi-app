import React from "react";

export default function AttendanceTable({ rows = [], days = [] }) {
    return (
        <div className="overflow-auto border rounded-lg shadow-sm bg-white max-h-[600px]">
            <table className="w-full text-sm border-collapse relative">
                <thead className="bg-gray-100 sticky top-0 z-30">
                    <tr className="text-center">
                        <th className="border px-3 py-2 w-12 font-medium text-gray-600 sticky left-0 bg-gray-100 z-40 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                            No
                        </th>
                        <th className="border px-3 py-2 min-w-[200px] sticky left-12 bg-gray-100 font-medium text-gray-600 z-40 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                            Nama
                        </th>
                        <th className="border px-3 py-2 min-w-[150px] sticky left-[248px] bg-gray-100 font-medium text-gray-600 z-40 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                            NIP
                        </th>
                        {days.map((d) => (
                            <th key={d} className="border px-2 py-2 w-16 font-medium text-gray-600">
                                {d}
                            </th>
                        ))}
                    </tr>
                </thead>
                <tbody>
                    {rows.length === 0 ? (
                        <tr>
                            <td colSpan={3 + days.length} className="text-center py-4 text-gray-500">
                                Tidak ada data presensi.
                            </td>
                        </tr>
                    ) : (
                        rows.map((r, i) => (
                            <tr key={i} className="text-center hover:bg-gray-50">
                                <td className="border px-2 py-2 sticky left-0 bg-white z-20 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                    {r.no}
                                </td>
                                <td className="border px-2 py-2 sticky left-12 bg-white text-left font-medium text-gray-700 z-20 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                    {r.name}
                                </td>
                                <td className="border px-2 py-2 sticky left-[248px] bg-white text-gray-600 z-20 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                    {r.nip}
                                </td>
                                {days.map((d) => {
                                    const val = r["d" + d];
                                    let content = "-";
                                    let className = "text-gray-400";

                                    if (val) {
                                        if (val === "sakit") {
                                            content = "S";
                                            className = "text-yellow-600 font-bold bg-yellow-100 rounded px-1";
                                        } else if (val === "izin") {
                                            content = "I";
                                            className = "text-blue-600 font-bold bg-blue-100 rounded px-1";
                                        } else if (val === "dd") {
                                            content = "DD";
                                            className = "text-purple-600 font-bold bg-purple-100 rounded px-1";
                                        } else if (val === "dl") {
                                            content = "DL";
                                            className = "text-orange-600 font-bold bg-orange-100 rounded px-1";
                                        } else {
                                            content = val; // Jam hadir
                                            className = "text-green-600 font-semibold";
                                        }
                                    }

                                    return (
                                        <td key={d} className="border px-1 py-2">
                                            <span className={className}>{content}</span>
                                        </td>
                                    );
                                })}
                            </tr>
                        ))
                    )}
                </tbody>
            </table>
        </div>
    );
}
