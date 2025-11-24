import { useForm } from "@inertiajs/react";
import DashboardLayout from "../../Layouts/DashboardLayout";

export default function PengajuanIzin() {
    const { data, setData, post, processing } = useForm({
        tanggal: "",
        keterangan: "",
    });

    const submit = (e) => {
        e.preventDefault();
        post("/izin/store");
    };

    return (
        <DashboardLayout>
            <h2 className="text-xl font-semibold mb-4">Pengajuan Izin</h2>

            <form
                onSubmit={submit}
                className="bg-white p-5 rounded-lg shadow space-y-4"
            >
                <div>
                    <label className="block">Tanggal</label>
                    <input
                        type="date"
                        className="w-full border px-3 py-2 rounded"
                        onChange={(e) => setData("tanggal", e.target.value)}
                        value={data.tanggal}
                    />
                </div>

                <div>
                    <label className="block">Keterangan</label>
                    <textarea
                        className="w-full border px-3 py-2 rounded"
                        rows="3"
                        onChange={(e) => setData("keterangan", e.target.value)}
                        value={data.keterangan}
                    ></textarea>
                </div>

                <button
                    disabled={processing}
                    className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                >
                    Ajukan Izin
                </button>
            </form>
        </DashboardLayout>
    );
}
