<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\JobListing;
use App\Models\Training;
use App\Models\User;
use App\Models\UserEducation;
use App\Models\UserExperience;
use App\Models\UserSkill;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->environment() !== 'local') {
            throw new \RuntimeException('DemoSeeder hanya boleh dijalankan saat APP_ENV=local.');
        }

        $admin = User::query()->where('role', 'admin')->firstOrFail();

        // 1. Pengguna Tanaya Auliya Damayanti
        $tanaya = User::factory()->create([
            'name' => 'Tanaya Auliya Damayanti',
            'email' => 'tanaya@gmail.com',
            'role' => 'pengguna',
            'phone' => '08123456789',
            'gender' => 'Perempuan',
            'location' => 'Surabaya, Indonesia',
            'bio' => 'Mahasiswa Sistem Informasi tingkat akhir di UPN "Veteran" Jawa Timur yang memiliki minat mendalam di bidang UI/UX Design, Business Analyst, dan Data Analyst. Berpengalaman dalam riset pengguna, wireframing, dan analisis data.',
            'avatar' => null,
            'email_verified_at' => now(),
            'is_notification_active' => true,
        ]);

        // Skills Tanaya
        $skills = [
            'UI/UX Design',
            'Figma',
            'Microsoft Office',
            'SQL',
            'Data Analysis',
            'Problem Solving',
            'Teamwork',
            'Communication',
        ];
        foreach ($skills as $skill) {
            UserSkill::create([
                'user_id' => $tanaya->id,
                'skill_name' => $skill,
            ]);
        }

        // Pendidikan Tanaya
        UserEducation::create([
            'user_id' => $tanaya->id,
            'institution' => 'UPN "Veteran" Jawa Timur',
            'major' => 'S1 Sistem Informasi',
            'start_year' => '2024',
            'end_year' => 'Sekarang',
        ]);

        // Pengalaman Tanaya
        UserExperience::create([
            'user_id' => $tanaya->id,
            'position' => 'Asisten Praktikum Basis Data',
            'company' => 'UPN "Veteran" Jawa Timur',
            'period' => 'Januari 2026 - Sekarang',
            'description' => 'Membimbing mahasiswa dalam pemodelan data konseptual dan fisikal, pembuatan basis data relasional, penulisan query SQL terstruktur, serta normalisasi tabel.',
        ]);

        // 3. Lowongan (type: lowongan, work_system: Full Time)
        $firstDemoJobId = (JobListing::max('id') ?? 0) + 1;
        // Featured jobs (3 jobs marked is_featured = true for landing page)
        $jobBudiDaya = JobListing::create([
            'title' => 'Frontend Developer',
            'company_name' => 'PT Budi Daya',
            'company_logo' => null,
            'location' => 'Surabaya',
            'type' => 'lowongan',
            'work_system' => 'Full Time',
            'duration' => null,
            'department' => 'Technology & Engineering',
            'description' => 'Kami mencari Frontend Developer berbakat untuk membangun dan mengembangkan antarmuka web yang interaktif, responsif, dan berperforma tinggi bersama tim engineering kami.',
            'qualifications' => "Pendidikan minimal D3/S1 Teknik Informatika atau bidang terkait\nPengalaman menguasai HTML, CSS, JavaScript, dan framework modern (Vue/React)\nMemahami responsive web design dan optimasi cross-browser\nMampu bekerja sama dalam tim agile dan memiliki komunikasi yang baik",
            'is_featured' => true,
        ]);

        JobListing::create([
            'title' => 'Data Analyst',
            'company_name' => 'PT Telkom Indonesia',
            'company_logo' => null,
            'location' => 'Jakarta',
            'type' => 'lowongan',
            'work_system' => 'Full Time',
            'duration' => null,
            'department' => 'Data & Analytics',
            'description' => 'PT Telkom Indonesia membuka kesempatan bagi Data Analyst untuk menganalisis data operasional dan perilaku pelanggan guna mendukung pengambilan keputusan strategis bisnis telekomunikasi digital.',
            'qualifications' => "S1 bidang Statistik, Matematika, Sistem Informasi, atau bidang terkait\nMahir SQL untuk data extraction, cleaning, dan query optimasi\nBerpengalaman menggunakan Tableau, Power BI, atau Google Looker Studio\nKemampuan analytical thinking dan problem solving yang kuat",
            'is_featured' => true,
        ]);

        JobListing::create([
            'title' => 'Backend Developer',
            'company_name' => 'PT Shopee Indonesia',
            'company_logo' => null,
            'location' => 'Bandung',
            'type' => 'lowongan',
            'work_system' => 'Full Time',
            'duration' => null,
            'department' => 'Backend Engineering',
            'description' => 'Bergabunglah dengan Shopee untuk merancang dan memelihara microservices berskala tinggi yang melayani jutaan pengguna setiap hari dengan performa andal dan latensi rendah.',
            'qualifications' => "S1 Ilmu Komputer atau setara dengan pengalaman relevan\nKeahlian solid pada Go, Java, atau PHP/Laravel\nPengalaman dengan RESTful API, PostgreSQL, Redis, dan Message Broker (Kafka/RabbitMQ)\nTerbiasa dengan unit testing dan CI/CD pipeline",
            'is_featured' => true,
        ]);

        JobListing::create([
            'title' => 'Business Analyst',
            'company_name' => 'PT Bank Mandiri',
            'company_logo' => null,
            'location' => 'Surabaya',
            'type' => 'lowongan',
            'work_system' => 'Full Time',
            'duration' => null,
            'department' => 'Digital Banking',
            'description' => 'Menganalisis kebutuhan bisnis nasabah dan menerjemahkannya ke dalam dokumen spesifikasi teknis untuk pengembangan produk digital banking unggulan Bank Mandiri.',
            'qualifications' => "Pendidikan minimal S1 Sistem Informasi, Manajemen, atau Teknik Industri\nMampu menyusun BRD (Business Requirement Document) dan Use Case\nFamiliar dengan metodologi Scrum/Agile\nKemampuan interpersonal dan presentasi yang sangat baik",
            'is_featured' => false,
        ]);

        JobListing::create([
            'title' => 'Content Creator',
            'company_name' => 'PT Ruangguru',
            'company_logo' => null,
            'location' => 'Bali',
            'type' => 'lowongan',
            'work_system' => 'Full Time',
            'duration' => null,
            'department' => 'Creative & Media',
            'description' => 'Membuat konten edukasi digital yang kreatif, menarik, dan informatif untuk berbagai platform media sosial Ruangguru.',
            'qualifications' => "Pendidikan minimal D3/S1 Komunikasi, Desain, atau bidang terkait\nKreatif, update terhadap tren media sosial terkini (TikTok, Instagram, YouTube)\nKemampuan dasar video editing dan copywriting\nPortofolio konten yang relevan dan orisinal",
            'is_featured' => false,
        ]);

        JobListing::create([
            'title' => 'Social Media Specialist',
            'company_name' => 'PT Bukalapak',
            'company_logo' => null,
            'location' => 'Tangerang',
            'type' => 'lowongan',
            'work_system' => 'Full Time',
            'duration' => null,
            'department' => 'Marketing & Brand',
            'description' => 'Mengelola strategi konten media sosial Bukalapak, membangun engagement komunitas, serta memantau analitik performa campaign secara berkala.',
            'qualifications' => "S1 Ilmu Komunikasi, Pemasaran, atau jurusan terkait\nPengalaman minimal 1 tahun mengelola media sosial korporat/brand\nFasih menganalisis insight media sosial dan menyusun laporan bulanan\nKreatif dan memiliki kemampuan copywriting yang memikat",
            'is_featured' => false,
        ]);

        // Tambahan agar pencarian "Frontend Developer" menghasilkan 4 hasil
        JobListing::create([
            'title' => 'Frontend Developer',
            'company_name' => 'PT Kompas Gramedia',
            'company_logo' => null,
            'location' => 'Gresik',
            'type' => 'lowongan',
            'work_system' => 'Full Time',
            'duration' => null,
            'department' => 'Digital Publishing Tech',
            'description' => 'Mengembangkan portal berita dan platform digital publishing Kompas Gramedia dengan teknologi web modern yang cepat dan SEO-friendly.',
            'qualifications' => "Pendidikan D3/S1 di bidang IT/Ilmu Komputer\nKeahlian dalam HTML5, Tailwind CSS, TypeScript, dan Next.js/Nuxt.js\nMemahami konsep Web Core Vitals dan optimasi performa halaman\nDisiplin dan siap bekerja dalam timeline rilis berita",
            'is_featured' => false,
        ]);

        JobListing::create([
            'title' => 'Frontend Developer',
            'company_name' => 'PT Astra International',
            'company_logo' => null,
            'location' => 'Malang',
            'type' => 'lowongan',
            'work_system' => 'Full Time',
            'duration' => null,
            'department' => 'Information Technology',
            'description' => 'Membangun aplikasi internal dan dashboard interaktif grup Astra untuk memonitor supply chain dan operasional armada secara real-time.',
            'qualifications' => "S1 Teknik Informatika, Sistem Informasi, atau terkait\nMemahami integrasi REST API dan State Management (Pinia/Redux)\nMemiliki ketelitian tinggi dalam implementasi desain UI/UX\nPengalaman dengan Git version control",
            'is_featured' => false,
        ]);

        JobListing::create([
            'title' => 'Frontend Developer',
            'company_name' => 'PT Traveloka',
            'company_logo' => null,
            'location' => 'Surabaya',
            'type' => 'lowongan',
            'work_system' => 'Full Time',
            'duration' => null,
            'department' => 'User Interface Engineering',
            'description' => 'Menciptakan antarmuka pencarian tiket dan akomodasi yang mulus, responsif, dan intuitif bagi jutaan pengguna Traveloka di Asia Tenggara.',
            'qualifications' => "S1 bidang Komputer atau setara\nKeahlian mendalam dalam JavaScript/TypeScript dan React.js\nFamiliar dengan Micro Frontend architecture dan unit testing (Jest)\nKemampuan komunikasi bahasa Inggris yang memadai",
            'is_featured' => false,
        ]);

        // Tambahan lain
        $jobKompasGraphic = JobListing::create([
            'title' => 'Graphic Designer',
            'company_name' => 'PT Kompas Gramedia',
            'company_logo' => null,
            'location' => 'Gresik',
            'type' => 'lowongan',
            'work_system' => 'Full Time',
            'duration' => null,
            'department' => 'Creative Studio',
            'description' => 'Merancang materi visual promosi, infografis berita, dan identitas visual kampanye digital untuk jaringan media Kompas Gramedia.',
            'qualifications' => "D3/S1 Desain Komunikasi Visual (DKV) atau bidang kreatif terkait\nMahir Adobe Illustrator, Photoshop, dan InDesign\nMampu membuat ilustrasi dan infografis informatif\nWajib melampirkan portofolio desain terbaru",
            'is_featured' => false,
        ]);

        JobListing::create([
            'title' => 'IT Support',
            'company_name' => 'PT Astra International',
            'company_logo' => null,
            'location' => 'Malang',
            'type' => 'lowongan',
            'work_system' => 'Full Time',
            'duration' => null,
            'department' => 'IT Infrastructure',
            'description' => 'Memberikan dukungan teknis perangkat keras, perangkat lunak, dan jaringan operasional harian kantor cabang Astra International Malang.',
            'qualifications' => "Minimal SMK/D3 Teknik Komputer Jaringan atau sejenisnya\nMemahami troubleshooting Windows, hardware PC/laptop, dan printer jaringan\nFamiliar dengan konfigurasi LAN, WLAN, dan IP Address\nCekatan, ramah, dan solutif dalam menangani kendala pengguna",
            'is_featured' => false,
        ]);

        JobListing::create([
            'title' => 'Digital Marketing Specialist',
            'company_name' => 'PT Traveloka',
            'company_logo' => null,
            'location' => 'Surabaya',
            'type' => 'lowongan',
            'work_system' => 'Full Time',
            'duration' => null,
            'department' => 'Growth & Performance Marketing',
            'description' => 'Mengoptimalkan performa paid advertising (Google Ads, Meta Ads) dan merancang strategi akuisisi pengguna baru dengan ROI terbaik.',
            'qualifications' => "S1 Manajemen Pemasaran, Komunikasi, atau Teknik\nPengalaman mengelola Google Ads & Meta Ads Manager\nKuat dalam data analytics dan A/B testing campaign\nBerorientasi pada target konversi dan efisiensi biaya",
            'is_featured' => false,
        ]);

        // 4. Magang (type: magang, duration: "3-6 Bulan")
        $internStartup = JobListing::create([
            'title' => 'UI/UX Designer Intern',
            'company_name' => 'PT Startup Indonesia',
            'company_logo' => null,
            'location' => 'Lamongan',
            'type' => 'magang',
            'work_system' => 'Hybrid',
            'duration' => '3-6 Bulan',
            'department' => 'Product Design',
            'description' => 'Program magang desain produk digital. Kamu akan terlibat langsung dalam riset pengguna, wireframing, dan pembuatan prototipe interaktif aplikasi mobile.',
            'qualifications' => "Mahasiswa aktif atau fresh graduate jurusan DKV, Sistem Informasi, atau terkait\nMemahami dasar-dasar UI/UX dan Design Thinking\nFamiliar menggunakan Figma dan FigJam\nMemiliki kemauan belajar tinggi dan proaktif",
            'is_featured' => false,
        ]);

        JobListing::create([
            'title' => 'Mobile Developer Intern',
            'company_name' => 'PT Shopee Indonesia',
            'company_logo' => null,
            'location' => 'Surabaya',
            'type' => 'magang',
            'work_system' => 'Hybrid',
            'duration' => '3-6 Bulan',
            'department' => 'Mobile Engineering',
            'description' => 'Kesempatan magang untuk mengembangkan fitur-fitur baru aplikasi Shopee menggunakan Flutter atau native mobile teknologi bersama para engineer senior.',
            'qualifications' => "Mahasiswa aktif S1 Teknik Informatika/Ilmu Komputer minimal semester 5\nMemahami dasar pemrograman Dart/Flutter atau Kotlin/Swift\nMemahami konsep Object-Oriented Programming (OOP)\nAntusias dengan teknologi mobile terkini",
            'is_featured' => false,
        ]);

        JobListing::create([
            'title' => 'Digital Marketing Intern',
            'company_name' => 'PT Traveloka',
            'company_logo' => null,
            'location' => 'Lamongan',
            'type' => 'magang',
            'work_system' => 'Hybrid',
            'duration' => '3-6 Bulan',
            'department' => 'Growth Marketing',
            'description' => 'Mendukung tim pemasaran dalam penyusunan kampanye digital mingguan, analisis audiens, riset kata kunci, dan copywriting promosi produk Traveloka.',
            'qualifications' => "Mahasiswa aktif jurusan Marketing, Komunikasi, atau Bisnis\nFasilitas menggunakan platform media sosial utama\nMampu menulis copy promosi yang persuasif dan kreatif\nMemiliki kemampuan organisasi waktu yang baik",
            'is_featured' => false,
        ]);

        JobListing::create([
            'title' => 'Data Analyst Intern',
            'company_name' => 'PT Budi Daya',
            'company_logo' => null,
            'location' => 'Surabaya',
            'type' => 'magang',
            'work_system' => 'Hybrid',
            'duration' => '3-6 Bulan',
            'department' => 'Business Intelligence',
            'description' => 'Membantu proses ekstraksi data, pembersihan dataset, dan pembuatan visualisasi dashboard metrik bisnis utama bagi manajemen PT Budi Daya.',
            'qualifications' => "Mahasiswa aktif bidang Sains Data, Statistik, Matematika, atau Informatika\nMemiliki pengetahuan dasar SQL dan Microsoft Excel tingkat lanjut\nFamiliar dengan tools visualisasi data seperti Tableau atau Power BI\nTeliti dalam membaca angka dan pola data",
            'is_featured' => false,
        ]);

        JobListing::query()
            ->where('id', '>=', $firstDemoJobId)
            ->update(['created_by' => $admin->id, 'status' => 'terbit']);

        // 5. Pelatihan (relative future dates)
        $firstDemoTrainingId = (Training::max('id') ?? 0) + 1;
        // Gratis
        $trainingUI = Training::create([
            'title' => 'Belajar UI/UX untuk Pemula',
            'category' => 'Gratis',
            'schedule_date' => now()->addDays(5)->toDateString(),
            'start_time' => '09:00',
            'end_time' => '11:00',
            'platform' => 'Zoom',
            'zoom_link' => 'https://zoom.us/j/91234567810?pwd=careerupfreeui',
            'mentor_name' => 'Dimas Suryo',
            'mentor_role' => 'Lead UI/UX Designer',
            'mentor_avatar' => null,
            'duration' => '2 Jam',
            'level' => 'Pemula',
            'description' => 'Pelatihan interaktif untuk memahami konsep dasar perancangan antarmuka pengguna (UI) dan alur pengalaman pengguna (UX) secara praktis dan mudah dipahami.',
            'syllabus' => "Konsep Dasar UI/UX Design\nPrinsip Desain Visual & Hierarki Informasi\nPembuatan Wireframe Sederhana\nDasar-dasar Prototyping di Figma",
            'thumbnail' => null,
        ]);

        $trainingCanva = Training::create([
            'title' => 'Dasar Graphic Design dengan Canva',
            'category' => 'Gratis',
            'schedule_date' => now()->addDays(10)->toDateString(),
            'start_time' => '09:00',
            'end_time' => '11:00',
            'platform' => 'Zoom',
            'zoom_link' => 'https://zoom.us/j/91234567811?pwd=careerupfreecanva',
            'mentor_name' => 'Siti Rahma',
            'mentor_role' => 'Graphic Designer & Content Creator',
            'mentor_avatar' => null,
            'duration' => '2 Jam',
            'level' => 'Pemula',
            'description' => 'Pelajari cara membuat materi grafis yang estetis, menarik, dan profesional untuk portofolio maupun media sosial hanya dengan menggunakan Canva.',
            'syllabus' => "Pengenalan Antarmuka Canva\nTeori Warna dan Pemilihan Font\nMembuat Desain Feed Instagram & Banner\nTeknik Export dan Penggunaan Elemen Premium",
            'thumbnail' => null,
        ]);

        // Premium
        Training::create([
            'title' => 'Data Analytics',
            'category' => 'Premium',
            'schedule_date' => now()->addDays(7)->toDateString(),
            'start_time' => '07:00',
            'end_time' => '11:00',
            'platform' => 'Zoom',
            'zoom_link' => 'https://zoom.us/j/91234567812?pwd=careeruppremiumdata',
            'mentor_name' => 'Queen Sabrina',
            'mentor_role' => 'Data Analyst',
            'mentor_avatar' => null,
            'duration' => '8 Pertemuan',
            'level' => 'Menengah - Mahir',
            'description' => 'dasar-dasar analisis data dengan tools populer untuk pengambilan keputusan berbasis data',
            'syllabus' => "Pengenalan Data Analytics\nPengelolaan Data dengan Excel\nVisualisasi Data\nStudi Kasus Sederhana",
            'thumbnail' => null,
        ]);

        Training::create([
            'title' => 'Pemrograman Python',
            'category' => 'Premium',
            'schedule_date' => now()->addDays(14)->toDateString(),
            'start_time' => '07:00',
            'end_time' => '11:00',
            'platform' => 'Zoom',
            'zoom_link' => 'https://zoom.us/j/91234567813?pwd=careeruppremiumpython',
            'mentor_name' => 'Rian Pratama',
            'mentor_role' => 'Senior Python Engineer',
            'mentor_avatar' => null,
            'duration' => '6 Pertemuan',
            'level' => 'Pemula - Menengah',
            'description' => 'Kuasai pemrograman Python secara intensif mulai dari logika dasar hingga otomatisasi pengolahan data dan pembuatan aplikasi backend sederhana.',
            'syllabus' => "Fondasi Python & Struktur Data\nModular Programming & File Handling\nLibrary Pandas & NumPy untuk Analisis\nMini Project Pembuatan REST API Python",
            'thumbnail' => null,
        ]);

        Training::query()
            ->where('id', '>=', $firstDemoTrainingId)
            ->update(['status' => 'terbit']);

        Application::factory()->create([
            'user_id' => $tanaya->id,
            'job_listing_id' => $jobBudiDaya->id,
            'status' => 'Diproses',
        ]);
        Application::factory()->create([
            'user_id' => $tanaya->id,
            'job_listing_id' => $internStartup->id,
            'status' => 'Diterima',
        ]);
    }
}
