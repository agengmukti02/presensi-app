import React from "react";

export default function AttendanceTable({ rows = [], days = [] }) {
    return (
        <div className="overflow-auto border rounded-lg shadow-sm bg-white">
            <table className="w-full text-sm border-collapse">
                <thead className="bg-gray-100 sticky top-0 z-10">
                    <tr className="text-center">
                        <th className="border px-3 py-2 w-12 font-medium text-gray-600">No</th>
                        <th className="border px-3 py-2 w-48 sticky left-0 bg-gray-100 font-medium text-gray-600 z-20">Nama</th>
                        <th className="border px-3 py-2 w-32 sticky left-48 bg-gray-100 font-medium text-gray-600 z-20">NIP</th>
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
                                <td className="border px-2 py-2">{r.no}</td>
                                <td className="border px-2 py-2 sticky left-0 bg-white text-left font-medium text-gray-700 z-10">
                                    {r.name}
                                </td>
                                <td className="border px-2 py-2 sticky left-48 bg-white text-gray-600 z-10">
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
