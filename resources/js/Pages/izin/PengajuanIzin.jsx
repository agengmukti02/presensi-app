import { useForm } from "@inertiajs/react";
import DashboardLayout from "../../Layouts/DashboardLayout";

export default function PengajuanIzin() {
    const { data, setData, post, processing, errors } = useForm({
        tipe: "izin",
        tanggal: "",
        keterangan: "",
    });

    const submit = (e) => {
        e.preventDefault();
        post("/izin/store");
    };

    return (
        <DashboardLayout>
            <h2 className="text-xl font-semibold mb-4">Pengajuan Izin/Cuti</h2>

            <form
                onSubmit={submit}
                className="bg-white p-5 rounded-lg shadow space-y-4"
            >
                <div>
                    <label className="block font-medium text-gray-700 mb-2">Tipe Pengajuan</label>
                    <select
                        className="w-full border border-gray-300 px-3 py-2 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        onChange={(e) => setData("tipe", e.target.value)}
                        value={data.tipe}
                        required
                    >
                        <option value="izin">Izin</option>
                        <option value="sakit">Sakit</option>
                        <option value="dd">Dinas Dalam</option>
                        <option value="dl">Dinas Luar</option>
                    </select>
                    {errors.tipe && <span className="text-red-500 text-sm">{errors.tipe}</span>}
                </div>

                <div>
                    <label className="block font-medium text-gray-700 mb-2">Tanggal</label>
                    <input
                        type="date"
                        className="w-full border border-gray-300 px-3 py-2 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        onChange={(e) => setData("tanggal", e.target.value)}
                        value={data.tanggal}
                        required
                    />
                    {errors.tanggal && <span className="text-red-500 text-sm">{errors.tanggal}</span>}
                </div>

                <div>
                    <label className="block font-medium text-gray-700 mb-2">Keterangan</label>
                    <textarea
                        className="w-full border border-gray-300 px-3 py-2 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        rows="3"
                        onChange={(e) => setData("keterangan", e.target.value)}
                        value={data.keterangan}
                        placeholder="Masukkan alasan pengajuan..."
                        required
                    ></textarea>
                    {errors.keterangan && <span className="text-red-500 text-sm">{errors.keterangan}</span>}
                </div>

                <button
                    disabled={processing}
                    className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-gray-400 transition-colors duration-200"
                >
                    {processing ? 'Mengirim...' : 'Ajukan'}
                </button>
            </form>
        </DashboardLayout>
    );
}
