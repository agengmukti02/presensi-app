<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        // ===== ADMIN (hanya user, tidak ada data employee) =====
        $adminList = [
            ['198110262002031001', 'KANGJENG PANGERAN HARYA YUDANEGARA S.E., M.Si., Ph.D.','kangjeng@admin.com'],
            ['198103092006041011', 'SUEDY S.Sos., M.P.A.','suedi@admin.com'],
            ['198005082011011004', 'ANDRIYAN MURYANTO S.H., M.A','andriyan@admin.com'],
            ['198411202009022006', 'Dr  RR. PRAMILIH WAHYU NASTITI S.T.P., MMA','pramilih@admin.com'],
            ['198404282011011008', 'ALEXANDER PRIYASMA S.I.P.','alexander@admin.com'],
            ['197308151998032005', 'SITI SANGADAH S.IP.','sangadah@admin.com'],
            ['198707022010011006', 'BENNY SAPTIANTO S.Sos.','benny@admin.com'],
            ['197502181998032002', 'RINI SRI WAHYUNI S.E., M.M.','rini@admin.com'],
            ['199605072020122031', 'TIAR NISHA HUTAMI, A.Md.','tiar@admin.com'],
            ['199001012015031001', 'ADMINISTRATOR','admin@admin.com'],
        ];

        foreach ($adminList as [$nip, $name, $email]) {
            // Buat user admin dengan email @admin.com (NIP = null untuk admin murni)
            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'nip' => null, // Admin tidak punya NIP
                    'email' => $email,
                    'role' => 'admin',
                    'password' => Hash::make('admin123'),
                    'email_verified_at' => now(),
                ]
            );
        }
        $kabidList = [
            ['198110262002031001', 'KANGJENG PANGERAN HARYA YUDANEGARA S.E., M.Si., Ph.D.', 'Pembina Tk. I', 'IV/b', 'Kepala Dinas Pemberdayaan Masyarakat, Kalurahan, Kependudukan dan Pencatatan Sipil', 'PNS', 'Aktif'],
            ['198103092006041011', 'SUEDY S.Sos., M.P.A.', 'Pembina', 'IV/a', 'Kepala Bidang Pemajuan Pembangunan Kalurahan dan Kelurahan', 'PNS', 'Aktif'],
            ['198005082011011004', 'ANDRIYAN MURYANTO S.H., M.A', 'Penata Tk. I', 'III/d', 'Kepala Bidang Pemberdayaan Masyarakat Kalurahan dan Kelurahan', 'PNS', 'Aktif'],
            ['198411202009022006', 'Dr  RR. PRAMILIH WAHYU NASTITI S.T.P., MMA', 'Pembina', 'IV/a', 'Kepala Bidang Pembinaan Penyelenggaraan Pemerintahan Kalurahan', 'PNS', 'Aktif'],
            ['198404282011011008', 'ALEXANDER PRIYASMA S.I.P.', 'Penata Tk. I', 'III/d', 'Kepala Bidang Kependudukan dan Pencatatan Sipil', 'PNS', 'Aktif'],
            ['197308151998032005', 'SITI SANGADAH S.IP.', 'Penata Tk. I', 'III/d', 'Kepala Subbagian Keuangan', 'PNS', 'Aktif'],
            ['198707022010011006', 'BENNY SAPTIANTO S.Sos.', 'Penata Tk. I', 'III/d', 'Kepala Subbagian Umum', 'PNS', 'Aktif'],
            ['197502181998032002', 'RINI SRI WAHYUNI S.E., M.M.', 'Pembina', 'IV/a', 'Sekretaris', 'PNS', 'Aktif'],
            ['199605072020122031', 'TIAR NISHA HUTAMI, A.Md.', 'Pengatur', 'II/c', 'Operator Sistem Informasi Administrasi Kependudukan Terampil', 'PNS', 'Aktif'],
        ];

        foreach ($kabidList as [$nip, $nama, $pangkat, $golongan, $jabatan, $status, $kedudukan]) {
            $emailPegawai = strtolower(str_replace([' ', '.', ','], '', $nama)) . '@presensi.com';
            
            // Buat user pegawai dengan NIP
            $user = User::updateOrCreate(
                ['nip' => $nip],
                [
                    'name' => $nama,
                    'nip' => $nip,
                    'email' => $emailPegawai,
                    'role' => 'pegawai',
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                ]
            );
            
            // Buat data employee
            Employee::updateOrCreate(
                ['nip' => $nip],
                [
                    'user_id' => $user->id,
                    'nip' => $nip,
                    'nama' => $nama,
                    'pangkat' => $pangkat,
                    'golongan' => $golongan,
                    'jabatan' => $jabatan,
                    'status_pegawai' => $status,
                    'kedudukan' => $kedudukan,
                ]
            );
        }
        // ===== LIST PEGAWAI DARI DATA NOMINATIF =====
        $pegawaiList = [
            ['199505042020121015', 'ARIF ARDIASMONO, S. Kom', 'Penata Muda', 'III/a', 'Administrator Database Kependudukan Ahli Pertama', 'PNS', 'Aktif'],
            ['199701302020122011', 'RACHMADILLA SEKAR LARASATI, S.I.P.', 'Penata Muda', 'III/a', 'Analis Kebijakan Ahli Pertama', 'PNS', 'Aktif'],
            ['198008052015022001', 'HENI SITI WAHYUNI, S.Sos', 'Penata Muda Tk. I', 'III/b', 'Penggerak Swadaya Masyarakat Ahli Pertama', 'PNS', 'Aktif'],
            ['198205202015022001', 'RENI DWI PUTRANTI, S.Sos', 'Penata Muda Tk. I', 'III/b', 'Penggerak Swadaya Masyarakat Ahli Pertama', 'PNS', 'Aktif'],
            ['197404121993021001', 'SETYO WARJIYANA, S.I.P., M.P.A.', 'Pembina', 'IV/a', 'Penggerak Swadaya Masyarakat Ahli Muda', 'PNS', 'Aktif'],
            ['197708052011012005', 'MURTI MAHARINI, S.Sos., M.Ec.Dev.', 'Pembina', 'IV/a', 'Penggerak Swadaya Masyarakat Ahli Madya', 'PNS', 'Aktif'],
            ['198506062011011017', 'ROOSSY BUDIAWAN, S.KM., MPA.', 'Pembina', 'IV/a', 'Penggerak Swadaya Masyarakat Ahli Madya', 'PNS', 'Aktif'],
            ['197502181998032002', 'RINI SRI WAHYUNI S.E., M.M.', 'Pembina', 'IV/a', 'Sekretaris', 'PNS', 'Aktif'],
            ['199308082016091001', 'FATUROKHMAN EKA NUGRAHA, S.IP', 'Penata', 'III/c', 'Penelaah Teknis Kebijakan', 'PNS', 'Aktif'],
            ['198005162006042007', 'BUDI RISWANTI, S.IP.', 'Penata Tk. I', 'III/d', 'Penelaah Teknis Kebijakan', 'PNS', 'Aktif'],
            ['198707022010011006', 'BENNY SAPTIANTO S.Sos.', 'Penata Tk. I', 'III/d', 'Kepala Subbagian Umum', 'PNS', 'Aktif'],
            ['199607052020122025', 'DIAN JULI LESTARI, A.Md', 'Pengatur Tk. I', 'II/d', 'Pengolah Data dan Informasi', 'PNS', 'Aktif'],
            ['198706272009122001', 'RETNO YUNI WULANDARI, S.H., M.Si.', 'Penata Tk. I', 'III/d', 'Penelaah Teknis Kebijakan', 'PNS', 'Aktif'],
            ['197106221990022001', 'YUNI SUKAWATI', 'Penata Muda', 'III/a', 'Operator Layanan Operasional', 'PNS', 'Aktif'],
            ['196804071990031011', 'TRI WARSANA', 'Penata Muda Tk. I', 'III/b', 'Pengadministrasi Perkantoran', 'PNS', 'Aktif'],
            ['197308151998032005', 'SITI SANGADAH S.IP.', 'Penata Tk. I', 'III/d', 'Kepala Subbagian Keuangan', 'PNS', 'Aktif'],
            ['199301312020122023', 'PUJI AYU LESTARI, A.Md', 'Pengatur Tk. I', 'II/d', 'Pengolah Data dan Informasi', 'PNS', 'Aktif'],
            ['199412242020122034', 'CHITA ANINDIA, A.Md', 'Pengatur Tk. I', 'II/d', 'Pengolah Data dan Informasi', 'PNS', 'Aktif'],
            ['197410152007012010', 'ISTI NURWIDAYATI, A.Md.', 'Penata Muda', 'III/a', 'Pengolah Data dan Informasi', 'PNS', 'Aktif'],
            ['199102082015022002', 'ARROF FEFKHIATIN, S.IP.', 'Penata', 'III/c', 'Penelaah Teknis Kebijakan', 'PNS', 'Aktif'],
            ['199604052022022001', 'CLAUDIA DEWI KUSUMANINGRUM, S.I.P.', 'Penata Muda', 'III/a', 'Penelaah Teknis Kebijakan', 'PNS', 'Aktif'],
            ['199511122017081001', 'GINANG ADI PRADANA, S.STP', 'Penata Muda Tk. I', 'III/b', 'Penelaah Teknis Kebijakan', 'PNS', 'Aktif'],
            ['199406022020122027', 'ERMA SETYO WIENARI, S.Sos., M.Sc.', 'Penata Muda Tk. I', 'III/b', 'Penelaah Teknis Kebijakan', 'PNS', 'Aktif'],
            ['198502232011011008', 'YOHANES KRISTIAN ADIYUWANA, S.H., M.A.P.', 'Penata', 'III/c', 'Penelaah Teknis Kebijakan', 'PNS', 'Aktif'],
            ['197111081992031004', 'HERIBERTUS TEGUH SUTOMO', 'Penata Muda Tk. I', 'III/b', 'Pengadministrasi Perkantoran', 'PNS', 'Aktif'],
            ['198906232020122016', 'NIKEN YUNI PRATIWI, S.Sos.', 'Penata Muda Tk. I', 'III/b', 'Penelaah Teknis Kebijakan', 'PNS', 'Aktif'],
            ['196906041992031007', 'IMAM SAFII, S.Pd.I.,M.M', 'Pembina', 'IV/a', 'Penelaah Teknis Kebijakan', 'PNS', 'Aktif'],
            ['197312312007011011', 'ISMI DERMAWAN , SE', 'Penata Tk. I', 'III/d', 'Penelaah Teknis Kebijakan', 'PNS', 'Aktif'],
            ['199404142022021002', 'AAN APRILIYANTO IMAN SANJAYA, S.Psi', 'Penata Muda', 'III/a', 'Penelaah Teknis Kebijakan', 'PNS', 'Aktif'],
            ['199308062020122020', 'WISTI MASLIKHAH, S.Psi.', 'Penata Muda Tk. I', 'III/b', 'Penelaah Teknis Kebijakan', 'PNS', 'Aktif'],
            ['199502102020122012', 'LINTANG ZIA NARESWARI, S.Psi', 'Penata Muda Tk. I', 'III/b', 'Penelaah Teknis Kebijakan', 'PNS', 'Aktif'],
            ['198404282011011008', 'ALEXANDER PRIYASMA S.I.P.', 'Penata Tk. I', 'III/d', 'Kepala Bidang Kependudukan dan Pencatatan Sipil', 'PNS', 'Aktif'],
            ['196910031994012001', 'RADEN RARA WARAWIDAYATI, S.Sos.', 'Penata Tk. I', 'III/d', 'Penelaah Teknis Kebijakan', 'PNS', 'Aktif'],
            ['198804162007011003', 'MOHAMMAD GAZALI, S.IP', 'Penata Tk. I', 'III/d', 'Penelaah Teknis Kebijakan', 'PNS', 'Aktif'],
            ['198001112009011003', 'GUNIA INSAR YANUAR', 'Penata Muda', 'III/a', 'Pengadministrasi Perkantoran', 'PNS', 'Aktif'],
            ['199909062023082001', 'ERIKA AULIA LESTARI , S.Tr.I.P.', 'Penata Muda', 'III/a', 'Penelaah Teknis Kebijakan', 'PNS', 'Aktif'],
            // PPPK
            ['199409112025212012', 'DEVI CITRA SARI, S.Sos.', '-', 'IX', 'Penata Layanan Operasional', 'PPPK', 'Aktif'],
            ['199307262025211004', 'YULI KURNIAWAN', '-', 'V', 'Pengadministrasi Perkantoran', 'PPPK', 'Aktif'],
            ['198905052025211012', 'MIFTAH AS’ADI ROMADHONI, S.H.I.', '-', 'IX', 'Penata Layanan Operasional', 'PPPK', 'Aktif'],
            ['199407102024211003', 'LISTYO ADI CAHYO, S.Sos', '-', 'IX', 'Analis Kebijakan Ahli Pertama', 'PPPK', 'Aktif'],
            ['198705122024212005', 'BABY SHEINA, S.K.M', '-', 'IX', 'Analis Kebijakan Ahli Pertama', 'PPPK', 'Aktif'],
            ['199508312024212007', 'ULFI MIFTAHULJANAH, S.Sos.', '-', 'IX', 'Analis Kebijakan Ahli Pertama', 'PPPK', 'Aktif'],
            ['199308022024212013', 'ALMIRA SURYANITA, S.Sos', '-', 'IX', 'Penggerak Swadaya Masyarakat Ahli Pertama', 'PPPK', 'Aktif'],
            ['198512112024211003', 'STEPHANUS TRI HARTANTO, S.Sos.', '-', 'IX', 'Penggerak Swadaya Masyarakat Ahli Pertama', 'PPPK', 'Aktif'],
            ['197404082024212001', 'ARUM WIDAYATSIH, S.Sos.', '-', 'IX', 'Penggerak Swadaya Masyarakat Ahli Pertama', 'PPPK', 'Aktif'],
        ];

        // ===== GENERATE USER PEGAWAI (yang belum ada di adminList) + EMPLOYEE =====
        // NIP yang sudah dibuat sebagai admin
        $adminNips = array_column($adminList, 0);
        
        foreach ($pegawaiList as $p) {
            [$nip, $nama, $pangkat, $golongan, $jabatan, $status, $kedudukan] = $p;
            
            // Skip jika sudah ada di adminList
            if (in_array($nip, $adminNips)) {
                continue;
            }
            
            $email = strtolower(str_replace([' ', '.', ','], '', $nama)) . '@presensi.com';

            $user = User::updateOrCreate(
                ['nip' => $nip],
                [
                    'name' => $nama,
                    'nip' => $nip,
                    'email' => $email,
                    'role' => 'pegawai',
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                ]
            );

            Employee::updateOrCreate(
                ['nip' => $nip],
                [
                    'user_id' => $user->id,
                    'nip' => $nip,
                    'nama' => $nama,
                    'pangkat' => $pangkat,
                    'golongan' => $golongan,
                    'jabatan' => $jabatan,
                    'status_pegawai' => $status,
                    'kedudukan' => $kedudukan,
                ]
            );
        }
    }
}
