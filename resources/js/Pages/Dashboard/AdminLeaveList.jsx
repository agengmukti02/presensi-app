import React from "react";
import { Head, router } from "@inertiajs/react";
import DashboardLayout from "../../Layouts/DashboardLayout";

export default function AdminLeaveList({ leaveRequests = [], flash = {} }) {
    const [processing, setProcessing] = React.useState(false);

    const handleApprove = (id) => {
        if (confirm("Setujui pengajuan izin ini?")) {
            setProcessing(true);
            router.put(`/izin/approve/${id}`, {}, {
                onFinish: () => setProcessing(false),
            });
        }
    };

    const handleReject = (id) => {
        if (confirm("Tolak pengajuan izin ini?")) {
            setProcessing(true);
            router.put(`/izin/reject/${id}`, {}, {
                onFinish: () => setProcessing(false),
            });
        }
    };

    return (
        <DashboardLayout>
            <Head title="Approval Izin" />

            <h2 className="text-xl font-semibold mb-4">Daftar Pengajuan Izin</h2>

            <div className="bg-white rounded-lg shadow overflow-hidden">
                {/* Mobile View (Card Layout) */}
                <div className="block md:hidden">
                    {leaveRequests.length === 0 ? (
                        <div className="p-4 text-center text-gray-500">
                            Tidak ada data pengajuan izin.
                        </div>
                    ) : (
                        <div className="divide-y divide-gray-200">
                            {leaveRequests.map((request) => (
                                <div key={request.id} className="p-4">
                                    <div className="flex justify-between items-start mb-2">
                                        <div>
                                            <div className="text-sm font-medium text-gray-900">{request.employee?.nama}</div>
                                            <div className="text-xs text-gray-500">{request.employee?.nip}</div>
                                        </div>
                                        <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${request.status === 'approved' ? 'bg-green-100 text-green-800' :
                                            request.status === 'rejected' ? 'bg-red-100 text-red-800' :
                                                'bg-gray-100 text-gray-800'
                                            }`}>
                                            {request.status.toUpperCase()}
                                        </span>
                                    </div>

                                    <div className="grid grid-cols-2 gap-x-4 gap-y-2 text-sm text-gray-600 mb-3">
                                        <div>
                                            <span className="block text-xs text-gray-400">Tanggal</span>
                                            {request.start_date} s/d {request.end_date}
                                        </div>
                                        <div>
                                            <span className="block text-xs text-gray-400">Tipe</span>
                                            <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${request.type === 'sakit' ? 'bg-yellow-100 text-yellow-800' :
                                                request.type === 'izin' ? 'bg-blue-100 text-blue-800' :
                                                    request.type === 'dd' ? 'bg-purple-100 text-purple-800' :
                                                        'bg-indigo-100 text-indigo-800'
                                                }`}>
                                                {request.type === 'izin' ? 'IZIN' :
                                                    request.type === 'sakit' ? 'SAKIT' :
                                                        request.type === 'dd' ? 'DINAS DALAM' :
                                                            'DINAS LUAR'}
                                            </span>
                                        </div>
                                        <div className="col-span-2">
                                            <span className="block text-xs text-gray-400">Alasan</span>
                                            {request.reason}
                                        </div>
                                        <div className="col-span-2">
                                            <span className="block text-xs text-gray-400">Disetujui Oleh</span>
                                            {request.approved_by?.name || '-'}
                                        </div>
                                    </div>

                                    {request.status === 'pending' && (
                                        <div className="flex space-x-2 mt-3 pt-3 border-t border-gray-100">
                                            <button
                                                onClick={() => handleApprove(request.id)}
                                                disabled={processing}
                                                className="flex-1 bg-green-50 text-green-700 px-3 py-2 rounded text-sm font-medium hover:bg-green-100 transition-colors"
                                            >
                                                Setujui
                                            </button>
                                            <button
                                                onClick={() => handleReject(request.id)}
                                                disabled={processing}
                                                className="flex-1 bg-red-50 text-red-700 px-3 py-2 rounded text-sm font-medium hover:bg-red-100 transition-colors"
                                            >
                                                Tolak
                                            </button>
                                        </div>
                                    )}
                                </div>
                            ))}
                        </div>
                    )}
                </div>

                {/* Desktop View (Table Layout) */}
                <div className="hidden md:block overflow-x-auto">
                    <table className="min-w-full divide-y divide-gray-200">
                        <thead className="bg-gray-50">
                            <tr>
                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pegawai</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alasan</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Disetujui Oleh</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody className="bg-white divide-y divide-gray-200">
                            {leaveRequests.length === 0 ? (
                                <tr>
                                    <td colSpan="7" className="px-6 py-4 text-center text-gray-500">
                                        Tidak ada data pengajuan izin.
                                    </td>
                                </tr>
                            ) : (
                                leaveRequests.map((request) => (
                                    <tr key={request.id}>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <div className="text-sm font-medium text-gray-900">{request.employee?.nama}</div>
                                            <div className="text-sm text-gray-500">{request.employee?.nip}</div>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {request.start_date} s/d {request.end_date}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${request.type === 'sakit' ? 'bg-yellow-100 text-yellow-800' :
                                                request.type === 'izin' ? 'bg-blue-100 text-blue-800' :
                                                    request.type === 'dd' ? 'bg-purple-100 text-purple-800' :
                                                        'bg-indigo-100 text-indigo-800'
                                                }`}>
                                                {request.type === 'izin' ? 'IZIN' :
                                                    request.type === 'sakit' ? 'SAKIT' :
                                                        request.type === 'dd' ? 'DINAS DALAM' :
                                                            'DINAS LUAR'}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                            {request.reason}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${request.status === 'approved' ? 'bg-green-100 text-green-800' :
                                                request.status === 'rejected' ? 'bg-red-100 text-red-800' :
                                                    'bg-gray-100 text-gray-800'
                                                }`}>
                                                {request.status.toUpperCase()}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {request.approved_by?.name || '-'}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            {request.status === 'pending' && (
                                                <div className="flex space-x-2">
                                                    <button
                                                        onClick={() => handleApprove(request.id)}
                                                        disabled={processing}
                                                        className="text-green-600 hover:text-green-900"
                                                    >
                                                        Setujui
                                                    </button>
                                                    <button
                                                        onClick={() => handleReject(request.id)}
                                                        disabled={processing}
                                                        className="text-red-600 hover:text-red-900"
                                                    >
                                                        Tolak
                                                    </button>
                                                </div>
                                            )}
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </DashboardLayout>
    );
}
