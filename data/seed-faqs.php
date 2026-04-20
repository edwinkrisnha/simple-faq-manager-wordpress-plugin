<?php
/**
 * FAQ Seeder — Kyrim FAQ data
 *
 * Run once via WP-CLI:
 *   wp eval-file wp-content/plugins/simple-faq-manager/data/seed-faqs.php
 *
 * Or via browser (delete this file afterwards):
 *   Add ?sfm_run_seed=1&sfm_seed_key=YOUR_SECRET to any admin URL and visit it
 *   while logged in as an administrator.
 *
 * The script is idempotent: it skips FAQs whose titles already exist and
 * skips categories that already exist.
 */

// ---------------------------------------------------------------------------
// Bootstrap — supports both WP-CLI eval-file and direct browser execution.
// ---------------------------------------------------------------------------

if ( ! defined( 'ABSPATH' ) ) {
	// Browser execution: load WordPress.
	$wp_load = dirname( __FILE__, 5 ) . '/wp-load.php';
	if ( ! file_exists( $wp_load ) ) {
		die( 'Cannot locate wp-load.php. Adjust the path or use WP-CLI.' );
	}
	require_once $wp_load;

	// Simple secret key guard (set your own value before running in browser).
	$expected_key = 'sfm_seed_2024';
	if (
		! isset( $_GET['sfm_run_seed'] ) ||
		! isset( $_GET['sfm_seed_key'] ) ||
		$_GET['sfm_seed_key'] !== $expected_key ||
		! current_user_can( 'manage_options' )
	) {
		wp_die( 'Unauthorized. Pass ?sfm_run_seed=1&sfm_seed_key=' . esc_html( $expected_key ) . ' while logged in as admin.' );
	}
}

// ---------------------------------------------------------------------------
// FAQ Data
// ---------------------------------------------------------------------------

$faq_data = array(

	'Tentang Kyrim' => array(
		array(
			'question' => 'Apa itu Kyrim?',
			'answer'   => '<p>Kyrim adalah platform spend management yang membantu perusahaan mengelola kebutuhan pembayaran operasional. Platform ini mendukung transfer massal ke 1.000 penerima melalui file .xls, pembayaran invoice, reimbursement, dan penggajian. Kyrim memegang lisensi PJP 3 dari Bank Indonesia.</p>',
		),
		array(
			'question' => 'Apa fitur pembayaran yang ada di aplikasi Kyrim?',
			'answer'   => '<p>Aplikasi mengelola proses pembayaran end-to-end, termasuk pengumpulan dokumen (invoice, kwitansi), sistem review dengan verifikasi maker-checker, dan pemrosesan pembayaran. Fitur meliputi transfer massal, review dan pembayaran invoice, pemrosesan reimbursement, dan manajemen penggajian.</p>',
		),
		array(
			'question' => 'Bagaimana aplikasi Kyrim membantu proses operasional pembayaran perusahaan saya?',
			'answer'   => '<p>Kyrim meningkatkan efisiensi dan keamanan melalui otomatisasi dan AI, termasuk: sistem input invoice dan reimbursement otomatis, ekstraksi data berbasis OCR, dan alur persetujuan terpusat yang menghilangkan pengecekan multi-aplikasi.</p>',
		),
		array(
			'question' => 'Apakah Kyrim aman?',
			'answer'   => '<p>Ya. Kyrim memegang sertifikasi keamanan ISO 27001:2022. Semua data dan transaksi dienkripsi untuk memastikan keamanan pengguna.</p>',
		),
		array(
			'question' => 'Apakah Kyrim berizin?',
			'answer'   => '<p>Ya, Kyrim adalah perusahaan pembayaran berlisensi di bawah kategori PJP 3 Bank Indonesia, dan beroperasi sesuai standar Indonesia.</p>',
		),
		array(
			'question' => 'Berapa biaya penggunaan aplikasi Kyrim?',
			'answer'   => '<p>Informasi harga tersedia dengan menjadwalkan pertemuan. Kyrim menawarkan promosi menarik untuk pengguna baru dengan skema transfer gratis.</p>',
		),
		array(
			'question' => 'Bagaimana cara membuat akun di Kyrim?',
			'answer'   => '<p>Daftarkan akun demo di dashboard Kyrim. Perusahaan kemudian melakukan verifikasi KYC yang memerlukan dokumen (akta pendirian, NPWP, data direktur). Setelah selesai, akses akun live diberikan.</p>',
		),
	),

	'Akun & Akses' => array(
		array(
			'question' => 'Apa itu Demo, Trial, dan Verified account di Kyrim?',
			'answer'   => '<p>Akun Demo mensimulasikan fitur tanpa transaksi nyata (14 hari). Akun Trial memungkinkan hingga 10 transfer dengan limit Rp1.000.000 (14 hari). Akun Verified menawarkan akses penuh setelah penyelesaian KYB.</p>',
		),
		array(
			'question' => 'Apa yang terjadi jika masa berlaku Demo/Trial habis?',
			'answer'   => '<p>Akun tidak ditutup tetapi pengguna kehilangan akses fitur. Upgrade ke Verified dengan menyelesaikan verifikasi KYB.</p>',
		),
		array(
			'question' => 'Bagaimana cara upgrade ke akun Verified?',
			'answer'   => '<p>Selesaikan verifikasi KYB dengan mengunggah dokumen perusahaan yang diperlukan sesuai instruksi dari akun Demo atau Trial.</p>',
		),
		array(
			'question' => 'Apa saja jenis role yang tersedia di Kyrim?',
			'answer'   => '<p>Role yang tersedia meliputi: Admin (manajemen akun), Maker (pembuatan transaksi), Checker (verifikasi/persetujuan), Payroll Maker/Checker (fungsi khusus payroll), dan Manager (akses persetujuan berbasis email).</p>',
		),
		array(
			'question' => 'Siapa yang otomatis menjadi Admin?',
			'answer'   => '<p>Pembuat akun pertama secara otomatis menjadi Admin.</p>',
		),
		array(
			'question' => 'Apa saja hak akses Admin?',
			'answer'   => '<p>Admin dapat mengundang anggota tim, menambah/menghapus akun perusahaan, mengelola kategori transfer dan penerima, mereset password/PIN, dan mengelola kode OTP.</p>',
		),
		array(
			'question' => 'Apa tugas utama Maker?',
			'answer'   => '<p>Maker membuat permintaan transaksi untuk Checker, menambah/menghapus penerima, membuat transfer, dan mengajukan permintaan refund.</p>',
		),
		array(
			'question' => 'Apa tugas utama Checker?',
			'answer'   => '<p>Checker memverifikasi semua transaksi sebelum diproses, menyetujui/menolak daftar penerima, transfer, dan permintaan refund.</p>',
		),
		array(
			'question' => 'Apa tugas utama Payroll Maker?',
			'answer'   => '<p>Payroll Maker menambahkan data karyawan, membuat permintaan pembayaran payroll, dan mengajukan payroll/reimbursement untuk ditinjau Payroll Checker.</p>',
		),
		array(
			'question' => 'Apa tugas utama Payroll Checker?',
			'answer'   => '<p>Payroll Checker meninjau dan memverifikasi data karyawan, pembayaran payroll, dan permintaan reimbursement, dengan kemampuan memberikan catatan penolakan.</p>',
		),
		array(
			'question' => 'Apa tugas utama Manager?',
			'answer'   => '<p>Manager menyetujui invoice dan reimbursement melalui email tanpa akses dashboard, memberikan otorisasi cepat dengan tetap menjaga keamanan.</p>',
		),
		array(
			'question' => 'Bagaimana cara menambahkan akun pengguna?',
			'answer'   => '<p>Buka Pengaturan &gt; Kelola Akun, klik Tambah Akun, masukkan email dan nama, pilih role, tambahkan akun, lalu validasi dengan PIN. Sistem mengirimkan link aktivasi yang berlaku 24 jam.</p>',
		),
		array(
			'question' => 'Bagaimana jika link aktivasi kedaluwarsa?',
			'answer'   => '<p>Klik Kirim Ulang Aktivasi dan sistem mengirimkan email aktivasi baru.</p>',
		),
		array(
			'question' => 'Bagaimana cara menghapus akun pengguna?',
			'answer'   => '<p>Buka Pengaturan &gt; Kelola Akun, pilih akun yang akan dihapus, klik ikon hapus, konfirmasi dengan PIN. Akun kehilangan akses Kyrim.</p>',
		),
		array(
			'question' => 'Apakah satu orang bisa menjadi Maker dan Checker bersamaan?',
			'answer'   => '<p>Tidak. Demi keamanan, role Maker dan Checker harus dipisahkan.</p>',
		),
		array(
			'question' => 'Bagaimana cara reset password/PIN jika saya lupa?',
			'answer'   => '<p>Hubungi admin Anda. Admin melakukan reset melalui halaman Kelola Akun, dan sistem mengirimkan link reset ke email Anda.</p>',
		),
		array(
			'question' => 'Bagaimana admin mereset password/PIN untuk user?',
			'answer'   => '<p>Buka Pengaturan &gt; Kelola Akun, pilih email pengguna, klik Reset Password atau Reset PIN, konfirmasi dengan PIN, sistem mengirimkan email reset.</p>',
		),
		array(
			'question' => 'Apakah ada batas waktu untuk link reset password/PIN?',
			'answer'   => '<p>Ya, link reset berlaku selama 24 jam. Jika kedaluwarsa, admin harus mereset ulang.</p>',
		),
	),

	'Pengaturan Dasar' => array(
		array(
			'question' => 'Apa itu rekening perusahaan di Kyrim?',
			'answer'   => '<p>Rekening perusahaan adalah nomor rekening bank bisnis resmi yang digunakan untuk refund dan pencatatan keuangan di Kyrim.</p>',
		),
		array(
			'question' => 'Bagaimana cara menambahkan rekening perusahaan?',
			'answer'   => '<p>Buka Pengaturan &gt; Rekening Perusahaan, klik Tambah Rekening, pilih bank, masukkan nomor rekening, verifikasi nama pemegang rekening, konfirmasi dengan PIN, lalu simpan.</p>',
		),
		array(
			'question' => 'Apakah ada batas jumlah rekening perusahaan yang bisa ditambahkan?',
			'answer'   => '<p>Ya, maksimal 5 rekening perusahaan per akun.</p>',
		),
		array(
			'question' => 'Apakah rekening yang ditambahkan harus atas nama perusahaan?',
			'answer'   => '<p>Ya, rekening harus terdaftar atas nama perusahaan. Rekening pribadi tidak diperbolehkan.</p>',
		),
		array(
			'question' => 'Kenapa saya tidak bisa menambahkan rekening perusahaan baru?',
			'answer'   => '<p>Pastikan Anda belum melebihi 5 rekening dan nomor rekening sudah benar serta sesuai nama perusahaan.</p>',
		),
		array(
			'question' => 'Bagaimana cara menghapus rekening perusahaan?',
			'answer'   => '<p>Buka Pengaturan &gt; Rekening Perusahaan, pilih rekening yang akan dihapus, klik Hapus, konfirmasi dengan PIN dan klik Ya.</p>',
		),
		array(
			'question' => 'Apakah saya bisa menghapus semua rekening perusahaan?',
			'answer'   => '<p>Tidak. Minimum satu rekening perusahaan aktif diperlukan untuk pemrosesan refund.</p>',
		),
		array(
			'question' => 'Apa itu kategori penerima di Kyrim?',
			'answer'   => '<p>Kategori penerima adalah label yang mengelompokkan penerima. Setiap akun memiliki minimal 1 dan maksimal 10 kategori.</p>',
		),
		array(
			'question' => 'Bagaimana cara menambahkan kategori penerima baru?',
			'answer'   => '<p>Buka Pengaturan &gt; Kategori Penerima, klik Tambah Kategori, masukkan nama, validasi dengan PIN, lalu simpan.</p>',
		),
		array(
			'question' => 'Apakah ada kategori default?',
			'answer'   => '<p>Ya, Kyrim menyediakan 3 kategori default yang tidak dapat dihapus sepenuhnya.</p>',
		),
		array(
			'question' => 'Bagaimana cara menghapus kategori penerima?',
			'answer'   => '<p>Buka Pengaturan &gt; Kategori Penerima, pilih kategori, klik Hapus, pindahkan semua penerima ke kategori lain, konfirmasi dengan PIN.</p>',
		),
		array(
			'question' => 'Apakah saya bisa memindahkan penerima saat menghapus kategori?',
			'answer'   => '<p>Ya, sebelum penghapusan, sistem memerlukan pemilihan kategori lain untuk memindahkan semua penerima.</p>',
		),
		array(
			'question' => 'Kenapa saya tidak bisa menambahkan kategori penerima?',
			'answer'   => '<p>Pastikan Anda belum mencapai maksimal 10 kategori. Hapus satu sebelum menambahkan yang baru.</p>',
		),
		array(
			'question' => 'Kenapa kategori penerima tidak bisa dihapus?',
			'answer'   => '<p>Minimum satu kategori aktif diperlukan. Sistem tidak mengizinkan penghapusan jika hanya tersisa satu kategori.</p>',
		),
		array(
			'question' => 'Apa itu kategori transfer di Kyrim?',
			'answer'   => '<p>Kategori transfer mengelompokkan transaksi berdasarkan jenis atau tujuan. Setiap akun memiliki minimal 1 dan maksimal 20 kategori.</p>',
		),
		array(
			'question' => 'Bagaimana cara menambahkan kategori transfer baru?',
			'answer'   => '<p>Buka Pengaturan &gt; Kategori Transfer, klik Tambah Kategori, masukkan nama, validasi dengan PIN, lalu simpan.</p>',
		),
		array(
			'question' => 'Apakah ada kategori default untuk transfer?',
			'answer'   => '<p>Ya, Kyrim menyediakan 5 kategori transfer default yang tidak dapat dihapus sepenuhnya.</p>',
		),
		array(
			'question' => 'Bagaimana cara menghapus kategori transfer?',
			'answer'   => '<p>Buka Pengaturan &gt; Kategori Transfer, pilih kategori yang akan dihapus, klik Hapus, konfirmasi dengan PIN dan klik Ya.</p>',
		),
		array(
			'question' => 'Kenapa saya tidak bisa menambahkan kategori transfer baru?',
			'answer'   => '<p>Pastikan Anda belum mencapai maksimal 20 kategori. Hapus satu sebelum menambahkan yang baru.</p>',
		),
		array(
			'question' => 'Kenapa kategori transfer tidak bisa dihapus?',
			'answer'   => '<p>Minimum satu kategori aktif diperlukan. Kategori yang dihapus tidak dapat dipilih untuk transaksi mendatang.</p>',
		),
	),

	'Transfer Domestik' => array(
		array(
			'question' => 'Bagaimana cara menambahkan penerima baru di Kyrim?',
			'answer'   => '<p>Buka Penerima &gt; Tambah Penerima, pilih bank, masukkan nomor rekening dan email, pilih kategori penerima, klik Tambah Penerima. Setelah validasi menunjukkan status OK, finalisasi dengan memasukkan PIN.</p>',
		),
		array(
			'question' => 'Apa yang harus dicek setelah mengisi data penerima?',
			'answer'   => '<p>Pastikan setiap baris penerima menunjukkan status OK setelah validasi. Edit dengan ikon Edit atau hapus dengan ikon X sebelum finalisasi.</p>',
		),
		array(
			'question' => 'Bagaimana proses finalisasi penerima baru?',
			'answer'   => '<p>Setelah validasi berhasil, klik Tambah Penerima, masukkan PIN, dan data penerima baru dikirim ke Checker untuk ditinjau.</p>',
		),
		array(
			'question' => 'Bagaimana cara Checker mereview daftar penerima baru?',
			'answer'   => '<p>Buka Penerima &gt; Tinjau, pilih penerima untuk ditinjau. Gunakan Multi Aksi untuk menyetujui/menolak beberapa sekaligus atau satu per satu. Simpan lalu masukkan PIN.</p>',
		),
		array(
			'question' => 'Apa hasil akhir setelah Checker mereview penerima baru?',
			'answer'   => '<p>Penerima yang disetujui ditambahkan ke daftar aktif. Penerima yang ditolak tidak disimpan dan Maker menerima notifikasi penolakan.</p>',
		),
		array(
			'question' => 'Bagaimana cara membuat transfer menggunakan Input Data?',
			'answer'   => '<p>Buka Transfer Dana &gt; Input Data, pilih kategori penerima, pilih penerima, masukkan nominal (minimal Rp10.000), tambahkan catatan transfer (opsional), klik Lanjut.</p>',
		),
		array(
			'question' => 'Apakah saya bisa menambahkan keterangan transfer?',
			'answer'   => '<p>Ya, tambahkan Kategori Transfer dan file pendukung (PDF, maks 2MB).</p>',
		),
		array(
			'question' => 'Bagaimana langkah setelah mengisi data transfer?',
			'answer'   => '<p>Klik Lanjut, pilih metode pembayaran, tinjau ringkasan. Jika sudah benar, klik Transfer, masukkan PIN, dan transaksi dikirim ke Checker.</p>',
		),
		array(
			'question' => 'Kapan dana perlu ditransfer ke metode pembayaran?',
			'answer'   => '<p>Setelah Checker menyetujui, Anda dapat mentransfer ke metode pembayaran yang dipilih.</p>',
		),
		array(
			'question' => 'Apa arti status transfer di Kyrim?',
			'answer'   => '<p>Status meliputi: Menunggu Persetujuan (Maker membuat, menunggu Checker), Settled (Checker menyetujui, dana belum ditransfer), Diproses (dana dikirim, Kyrim mendisbursing), Berhasil/Settled (sampai ke penerima), Refund, Kedaluwarsa (tidak disetujui/dibayar dalam 24 jam), Ditolak.</p>',
		),
		array(
			'question' => 'Bagaimana cara membuat transfer dengan Upload Data?',
			'answer'   => '<p>Buka Transfer Dana &gt; Upload Data, unggah file .CSV sesuai template. Buat template jika diperlukan. Dapatkan ID Penerima dari menu Penerima. Setelah upload, klik Lanjut, pilih metode pembayaran, tinjau, klik Transfer, masukkan PIN.</p>',
		),
		array(
			'question' => 'Bagaimana langkah selanjutnya setelah data terunggah?',
			'answer'   => '<p>Data CSV terisi otomatis ke sistem. Verifikasi keakuratan, klik Lanjut, pilih metode pembayaran, tinjau ringkasan, klik Transfer, masukkan PIN untuk pengiriman ke Checker.</p>',
		),
		array(
			'question' => 'Bagaimana cara Checker menyetujui permintaan transfer?',
			'answer'   => '<p>Buka Transfer Dana &gt; Tinjau, temukan transaksi, klik ID Transaksi untuk detail. Tinjau data dan lampiran, klik Setujui. Konfirmasi di popup, tambahkan catatan (opsional), klik Ya, masukkan PIN. Status berubah menjadi Disetujui.</p>',
		),
		array(
			'question' => 'Bagaimana cara Checker menolak permintaan transfer?',
			'answer'   => '<p>Buka Transfer Dana &gt; Tinjau, temukan transaksi, klik ID Transaksi, pilih Tolak. Isi catatan penolakan (opsional), klik Ya, masukkan PIN. Status berubah menjadi Ditolak.</p>',
		),
		array(
			'question' => 'Apa yang terjadi setelah Checker menyetujui transfer?',
			'answer'   => '<p>Transaksi masuk ke Maker yang menerima notifikasi email. Maker harus mentransfer ke metode pembayaran yang dipilih sebelum tenggat waktu.</p>',
		),
		array(
			'question' => 'Apa yang terjadi setelah Checker menolak transfer?',
			'answer'   => '<p>Maker menerima email penolakan dengan catatan (jika diberikan) dan harus membuat transfer baru.</p>',
		),
		array(
			'question' => 'Berapa lama batas waktu transfer setelah disetujui Checker?',
			'answer'   => '<p>24 jam sejak persetujuan. Setelah kedaluwarsa, status menjadi Kedaluwarsa.</p>',
		),
	),

	'Transfer Internasional' => array(
		array(
			'question' => 'Bagaimana cara menambahkan penerima untuk transfer internasional?',
			'answer'   => '<p>Buka Penerima Internasional &gt; Tambah Penerima Internasional. Untuk individu: masukkan negara tujuan, mata uang, nama lengkap sesuai ID, kewarganegaraan, alamat lengkap, email, kode SWIFT/BIC, nomor rekening/IBAN, tujuan transaksi. Untuk perusahaan: masukkan nama perusahaan, alamat, email, SWIFT/BIC, rekening/IBAN, tujuan, sumber pendapatan, dan hubungan. Klik Lanjut dan simpan dengan PIN.</p>',
		),
		array(
			'question' => 'Apakah data penerima internasional berbeda dengan penerima domestik?',
			'answer'   => '<p>Ya. Penerima internasional memerlukan kode SWIFT/BIC/IBAN dan alamat bank tujuan selain nomor rekening.</p>',
		),
		array(
			'question' => 'Bagaimana proses review penerima internasional?',
			'answer'   => '<p>Penerima internasional masuk ke antrian tinjauan Checker seperti penerima domestik. Checker menyetujui atau menolak sebelum aktivasi.</p>',
		),
		array(
			'question' => 'Apakah ada batas jumlah penerima internasional yang bisa ditambahkan?',
			'answer'   => '<p>Tidak ada batas spesifik, meskipun penggunaan kategori meningkatkan organisasi.</p>',
		),
		array(
			'question' => 'Bagaimana cara membuat transfer internasional dengan input data?',
			'answer'   => '<p>Buka Transfer Internasional &gt; Input Data, pilih negara tujuan dan mata uang, pilih penerima, masukkan nominal (minimal Rp10.000), tambahkan catatan transfer (opsional), klik Lanjut.</p>',
		),
		array(
			'question' => 'Apakah ada batas minimal dan maksimal nominal untuk transfer internasional?',
			'answer'   => '<p>Minimal Rp10.000 (sama seperti domestik). Maksimal per transaksi adalah Rp250.000.000 untuk kepatuhan regulasi.</p>',
		),
		array(
			'question' => 'Apakah saya bisa menambahkan keterangan tambahan pada transfer internasional?',
			'answer'   => '<p>Ya, tambahkan catatan transfer atau komentar tambahan yang diteruskan ke penerima.</p>',
		),
		array(
			'question' => 'Bagaimana proses setelah Maker mengajukan transfer internasional?',
			'answer'   => '<p>Transaksi masuk ke antrian persetujuan Checker. Setelah Checker menyetujui, lanjutkan dengan pembayaran sesuai instruksi untuk pemrosesan Kyrim.</p>',
		),
		array(
			'question' => 'Berapa lama dana transfer internasional sampai ke penerima?',
			'answer'   => '<p>Perkiraan SLA adalah 1–2 hari kerja setelah pembayaran berhasil dan dokumen pendukung lengkap. Waktu aktual bervariasi tergantung tujuan, bank koresponden, dan cut-off time.</p>',
		),
	),

	'Vendor & Invoice' => array(
		array(
			'question' => 'Bagaimana cara menambahkan vendor baru di Kyrim?',
			'answer'   => '<p>Buka Vendor &gt; Tambah Vendor, masukkan data yang diperlukan (nama vendor, jenis usaha, nama PIC, email, bank, nomor rekening). Opsional tambahkan dokumen (perjanjian, NPWP, SPPKP). Verifikasi rekening dengan tombol Cek Rekening, lalu simpan dengan PIN.</p>',
		),
		array(
			'question' => 'Apakah ada cara lain untuk menambahkan vendor di Kyrim?',
			'answer'   => '<p>Ya. Buka Vendor &gt; Tambah Vendor, klik Kirim Link, masukkan nama vendor dan email PIC. Sistem mengirimkan link undangan untuk onboarding mandiri vendor.</p>',
		),
		array(
			'question' => 'Bagaimana cara mengunggah atau membuat invoice untuk vendor?',
			'answer'   => '<p>Buka Invoice &gt; Kelola Invoice, klik Invoice Manual, pilih vendor, masukkan nomor invoice, tanggal jatuh tempo, nominal, deskripsi. Lampirkan dokumen pendukung (PDF, gambar). Klik Simpan.</p>',
		),
		array(
			'question' => 'Bagaimana cara membayar invoice vendor melalui Kyrim?',
			'answer'   => '<p>Setiap invoice memerlukan persetujuan dari PIC pemberi kerja, kemudian Maker, kemudian Checker. Setelah semua menyetujui, buka Invoice &gt; Kelola Invoice, pilih tab Disetujui, klik Bayar. Verifikasi detail, pilih metode pembayaran, validasi dengan PIN untuk pemrosesan langsung.</p>',
		),
		array(
			'question' => 'Apakah invoice harus selalu disetujui Checker sebelum dibayar?',
			'answer'   => '<p>Ya, semua invoice yang diajukan Maker memerlukan persetujuan Checker sebelum pembayaran.</p>',
		),
		array(
			'question' => 'Apakah Manager juga bisa menyetujui pembayaran invoice?',
			'answer'   => '<p>Ya. Manager tanpa akses dashboard dapat menyetujui/menolak invoice langsung melalui notifikasi email.</p>',
		),
		array(
			'question' => 'Apakah ada cara lain untuk input invoice vendor di Kyrim?',
			'answer'   => '<p>Ya. Vendor dapat mengunggah invoice sendiri menggunakan link yang disediakan. Vendor menerima email dengan link upload dan dapat melacak status pembayaran melalui link pelacakan.</p>',
		),
	),

	'Karyawan' => array(
		array(
			'question' => 'Bagaimana cara menambahkan karyawan baru di Kyrim?',
			'answer'   => '<p>Dua metode tersedia:</p><p><strong>Manual</strong> — Buka Karyawan &gt; Daftar Karyawan &gt; Semua Karyawan, klik Tambah Karyawan, isi data pribadi, status kepegawaian, data pajak dan BPJS, info bank, gaji pokok. Tandai semua kolom yang diperlukan. Klik Simpan, masukkan PIN 6 digit.</p><p><strong>Unggah Massal</strong> — Buka Karyawan &gt; Daftar Karyawan, klik Impor Karyawan, unduh template, isi maksimal 50 karyawan per unggahan, unggah file, tinjau pratinjau, klik Kirim, masukkan PIN. Keduanya menunggu persetujuan Payroll Checker.</p>',
		),
		array(
			'question' => 'Apakah karyawan yang sudah ditambahkan langsung muncul di payroll?',
			'answer'   => '<p>Ya, setelah penambahan, data karyawan terintegrasi otomatis dengan payroll tanpa entri ulang.</p>',
		),
		array(
			'question' => 'Siapa yang dapat menyetujui penambahan karyawan baru?',
			'answer'   => '<p>Payroll Checker harus menyetujui data karyawan baru sebelum aktivasi.</p>',
		),
		array(
			'question' => 'Apakah data karyawan di Kyrim digunakan untuk fitur lain?',
			'answer'   => '<p>Ya. Data karyawan terintegrasi otomatis dengan fitur Payroll dan Reimbursement.</p>',
		),
		array(
			'question' => 'Bagaimana cara memproses pengajuan pengunduran diri karyawan di Kyrim?',
			'answer'   => '<p>Buka Karyawan &gt; Daftar Karyawan &gt; Semua Karyawan, pilih karyawan, klik Pengunduran Diri. Masukkan tanggal pengunduran diri, alasan, dan detail kompensasi. Klik Kirim, masukkan PIN 6 digit. Menunggu persetujuan Payroll Checker.</p>',
		),
		array(
			'question' => 'Bagaimana cara membatalkan pengajuan pengunduran diri karyawan di Kyrim?',
			'answer'   => '<p>Buka Karyawan &gt; Tinjauan Status &gt; Pengunduran Diri, temukan status menunggu persetujuan, klik Batalkan Pengajuan, masukkan PIN 6 digit. Karyawan kembali ke status Aktif.</p>',
		),
		array(
			'question' => 'Apakah karyawan yang resign akan otomatis dikeluarkan dari payroll?',
			'answer'   => '<p>Ya, setelah Payroll Checker menyetujui pengunduran diri, karyawan otomatis keluar dari payroll aktif.</p>',
		),
	),

	'Reimbursement' => array(
		array(
			'question' => 'Siapa yang bisa mengajukan reimbursement di Kyrim?',
			'answer'   => '<p>Hanya karyawan melalui link formulir reimbursement.</p>',
		),
		array(
			'question' => 'Data apa saja yang harus diisi saat mengajukan reimbursement?',
			'answer'   => '<p>Yang diperlukan: bukti pembayaran/invoice, kategori reimbursement, tanggal, deskripsi, nominal reimbursement.</p>',
		),
		array(
			'question' => 'Apa alur persetujuan reimbursement di Kyrim?',
			'answer'   => '<p>Persetujuan hierarkis: Manager, kemudian Payroll Maker, kemudian Payroll Checker. Setelah semua menyetujui, reimbursement diproses.</p>',
		),
		array(
			'question' => 'Bagaimana cara membayar reimbursement yang sudah disetujui?',
			'answer'   => '<p>Buka Reimbursement &gt; Kelola Reimbursement, pilih tab Disetujui, pilih reimbursement yang akan dibayar. Diproses bersama transaksi lain (tidak terpisah). Setelah pembayaran Kyrim, dana ditransfer real-time ke rekening karyawan.</p>',
		),
		array(
			'question' => 'Apakah ada SLA waktu pencairan reimbursement?',
			'answer'   => '<p>Dana reimbursement diterima real-time setelah pembayaran Kyrim berhasil.</p>',
		),
		array(
			'question' => 'Bagaimana karyawan memantau status reimbursement mereka?',
			'answer'   => '<p>Karyawan memantau melalui link pelacakan yang dikirimkan email setelah pengajuan.</p>',
		),
		array(
			'question' => 'Apakah ada batas maksimal nominal reimbursement?',
			'answer'   => '<p>Ya, maksimal Rp250.000.000 per transaksi.</p>',
		),
		array(
			'question' => 'Apa saja kategori reimbursement yang tersedia?',
			'answer'   => '<p>Kategori yang tersedia: akomodasi, kesehatan, langganan perangkat lunak, makanan/minuman, pelatihan, pembelian barang, transportasi. Perusahaan tidak dapat menambahkan kategori kustom.</p>',
		),
	),

	'Payroll' => array(
		array(
			'question' => 'Apa itu fitur Payroll di Kyrim?',
			'answer'   => '<p>Fitur Payroll mengotomatiskan manajemen gaji, pencairan, dan perhitungan (gaji, THR, BPJS, pajak) dengan pembayaran real-time ke rekening karyawan.</p>',
		),
		array(
			'question' => 'Siapa yang dapat membuat dan menyetujui payroll?',
			'answer'   => '<p>Payroll Maker membuat, Payroll Checker menyetujui. Setelah persetujuan, pembayaran diproses dari dashboard Kyrim.</p>',
		),
		array(
			'question' => 'Bagaimana cara membuat payroll di Kyrim Dashboard?',
			'answer'   => '<p>Buka Payroll &gt; Riwayat Payroll, klik Buat Payroll. Pastikan Pengaturan Payroll sudah dikonfigurasi. Pilih periode payroll. Opsional sertakan THR. Pilih karyawan yang akan dibayar. Tambahkan tunjangan/potongan (opsional). Tinjau total gaji, konfirmasi dengan PIN 6 digit untuk dikirim ke Payroll Checker.</p>',
		),
		array(
			'question' => 'Apakah bisa membayar sebagian payroll saja?',
			'answer'   => '<p>Ya. Perusahaan bebas memilih karyawan mana yang akan dibayar tanpa memproses semua secara bersamaan.</p>',
		),
		array(
			'question' => 'Apakah payroll dibayarkan secara real time?',
			'answer'   => '<p>Ya, semua payroll real-time setelah pembayaran perusahaan berhasil melalui sistem Kyrim.</p>',
		),
		array(
			'question' => 'Apakah Kyrim mendukung perhitungan THR dan BPJS?',
			'answer'   => '<p>Ya. Fitur Payroll mendukung THR, BPJS Ketenagakerjaan, BPJS Kesehatan, dan perhitungan PPh 21 otomatis.</p>',
		),
		array(
			'question' => 'Apakah perusahaan bisa mengunduh laporan payroll?',
			'answer'   => '<p>Ya, laporan payroll lengkap (detail gaji, status pembayaran, riwayat transaksi) dapat diunduh dari dashboard.</p>',
		),
		array(
			'question' => 'Apa batas maksimal nominal payroll per karyawan?',
			'answer'   => '<p>Maksimal Rp250.000.000 per karyawan per transaksi.</p>',
		),
		array(
			'question' => 'Apa saja status transaksi yang ada di fitur Payroll?',
			'answer'   => '<p>Status: Menunggu Persetujuan (Maker membuat, menunggu Payroll Checker), Ditolak, Disetujui, Menunggu Pembayaran, Kedaluwarsa (disetujui tetapi tidak dibayar dalam tenggat), Settled.</p>',
		),
		array(
			'question' => 'Apa saja status transfer yang ada di fitur Payroll?',
			'answer'   => '<p>Status transfer: Diproses (Kyrim mengirimkan dana), Gagal (kesalahan seperti rekening salah), Berhasil (dana diterima di rekening karyawan).</p>',
		),
		array(
			'question' => 'Apa fungsi Pengaturan Payroll di Kyrim?',
			'answer'   => '<p>Pengaturan Payroll mengkonfigurasi semua komponen kompensasi: tunjangan, potongan, BPJS, pajak, THR, jadwal kerja, kebijakan prorata yang diterapkan otomatis pada setiap payroll.</p>',
		),
		array(
			'question' => 'Apa saja komponen yang bisa diatur di Pengaturan Payroll?',
			'answer'   => '<p>Komponen yang dapat dikonfigurasi: pengaturan pajak karyawan, ID TKU, tunjangan dan potongan, NPP BPJS, pengaturan THR, pengaturan perhitungan prorata, jadwal kerja dan hari libur.</p>',
		),
		array(
			'question' => 'Apa itu pengaturan pajak karyawan (Gross / Gross Up)?',
			'answer'   => '<p>Pengaturan pajak menentukan siapa yang menanggung pajak penghasilan: Gross (karyawan menanggung), Gross Up (perusahaan menanggung beban pajak karyawan).</p>',
		),
		array(
			'question' => 'Apa itu ID TKU dan NPP BPJS di Pengaturan Payroll?',
			'answer'   => '<p>ID TKU (Tempat Kegiatan Usaha) untuk administrasi pajak. NPP BPJS menentukan tarif JKK sesuai tingkat risiko kerja perusahaan.</p>',
		),
		array(
			'question' => 'Apakah saya bisa menambah atau menghapus komponen tunjangan dan potongan sendiri?',
			'answer'   => '<p>Ya, perusahaan dapat menambahkan tunjangan atau potongan kustom sesuai kebijakan internal.</p>',
		),
		array(
			'question' => 'Apa itu pengaturan pro-rate di Kyrim?',
			'answer'   => '<p>Pengaturan prorata menghitung gaji/THR untuk periode kerja tidak penuh: berdasarkan hari kerja (hanya hari aktif) atau hari kalender (total hari dalam bulan).</p>',
		),
		array(
			'question' => 'Apa fungsi pengaturan eligibilitas THR?',
			'answer'   => '<p>Menentukan durasi kerja minimum bagi karyawan baru untuk menerima THR (misalnya, setelah 3+ bulan).</p>',
		),
		array(
			'question' => 'Apa itu pembulatan dan pengali THR?',
			'answer'   => '<p>Pembulatan THR: melebihi hari tertentu (misalnya 15) dibulatkan menjadi 1 bulan. Pengali THR: menerapkan THR tambahan berdasarkan lama kerja (misalnya karyawan 5 tahun mendapat 2x THR).</p>',
		),
		array(
			'question' => 'Apakah bisa mengatur jadwal kerja dan hari libur di Pengaturan Payroll?',
			'answer'   => '<p>Ya, atur jadwal kerja standar (misalnya Senin–Jumat) dan hari libur nasional/bersama untuk perhitungan prorata dan absensi.</p>',
		),
		array(
			'question' => 'Apakah perubahan di Pengaturan Payroll langsung diterapkan ke payroll aktif?',
			'answer'   => '<p>Ya, perubahan yang disimpan diterapkan otomatis ke payroll berikutnya. Payroll yang sudah diajukan tidak terpengaruh.</p>',
		),
		array(
			'question' => 'Apakah Kyrim menghitung BPJS dan pajak otomatis?',
			'answer'   => '<p>Ya, sistem menghitung otomatis BPJS Ketenagakerjaan, BPJS Kesehatan, dan PPh 21 sesuai komponen dan status karyawan.</p>',
		),
		array(
			'question' => 'Apakah saya bisa mengunduh laporan perhitungan payroll?',
			'answer'   => '<p>Ya, laporan lengkap (potongan, tunjangan, BPJS, pajak) dapat diunduh dari dashboard.</p>',
		),
		array(
			'question' => 'Apakah Payroll di Kyrim bisa digunakan untuk pembayaran di luar gaji (bonus, lembur, dsb.)?',
			'answer'   => '<p>Ya, tambahkan komponen tunjangan kustom seperti bonus, lembur, insentif (sementara atau permanen).</p>',
		),
		array(
			'question' => 'Apakah data Payroll di Kyrim aman?',
			'answer'   => '<p>Sangat aman. Semua data payroll, karyawan, dan transaksi dienkripsi dengan akses terbatas sesuai role pengguna (hanya Payroll Maker/Checker).</p>',
		),
	),

	'HRIS' => array(
		array(
			'question' => 'Bagaimana cara mendaftarkan karyawan ke Platform Karyawan?',
			'answer'   => '<p>Pastikan karyawan sudah ditambahkan ke Dashboard dan disetujui. Sistem secara otomatis mengirimkan email undangan ke email karyawan. Karyawan mengklik tombol Aktivasi untuk membuat kata sandi dan menyelesaikan pendaftaran.</p>',
		),
		array(
			'question' => 'Apa yang dilakukan karyawan saat menerima email undangan Platform Karyawan?',
			'answer'   => '<p>Klik tombol Aktifkan Akun di email, buat kata sandi (minimal 8 karakter), terima Syarat &amp; Ketentuan (wajib), kirim. Aktivasi berhasil mengarahkan ke halaman utama Platform Karyawan.</p>',
		),
		array(
			'question' => 'Apakah karyawan bisa daftar tanpa undangan?',
			'answer'   => '<p>Tidak. Pendaftaran hanya dapat diakses melalui link undangan yang valid; pendaftaran mandiri tidak tersedia.</p>',
		),
		array(
			'question' => 'Bagaimana jika link undangan registrasi karyawan kedaluwarsa atau sudah tidak bisa dipakai?',
			'answer'   => '<p>Link pendaftaran bersifat unik per karyawan dengan masa berlaku. Jika kedaluwarsa/tidak valid, HR/Payroll mengirimkan ulang undangan untuk link baru.</p>',
		),
		array(
			'question' => 'Apa bedanya link undangan registrasi dengan link login Platform Karyawan?',
			'answer'   => '<p>Link pendaftaran bersifat unik per karyawan dengan batas waktu. Link login seragam untuk semua karyawan per perusahaan.</p>',
		),
		array(
			'question' => 'Bagaimana cara membagikan link login Platform Karyawan ke karyawan?',
			'answer'   => '<p>Buka Karyawan &gt; Daftar Karyawan, klik Bagikan Link, pilih karyawan, klik Kirim Email. Sistem mengirimkan link login melalui email.</p>',
		),
		array(
			'question' => 'Bagaimana cara menyalin link login Platform Karyawan untuk dibagikan via chat?',
			'answer'   => '<p>Buka Karyawan &gt; Daftar Karyawan, klik Salin Link. Sistem menyalin link login dan menampilkan notifikasi "Berhasil disalin".</p>',
		),
		array(
			'question' => 'Apakah saya bisa mengirim email login ke semua karyawan sekaligus?',
			'answer'   => '<p>Ya. Di modal Bagikan Link, centang Pilih Semua Karyawan, klik Kirim Email (aktif hanya jika ada karyawan yang dipilih).</p>',
		),
		array(
			'question' => 'Kenapa ada karyawan yang tidak muncul saat saya ingin bagikan link ke Karyawan?',
			'answer'   => '<p>Modal hanya menampilkan karyawan dengan status pendaftaran Bergabung. Karyawan dengan status Menunggu disembunyikan dan tidak tersedia.</p>',
		),
		array(
			'question' => 'Bagaimana cara kirim ulang email login Platform Karyawan?',
			'answer'   => '<p>Buka Karyawan &gt; Daftar Karyawan, klik Bagikan Link, pilih karyawan dengan status Bergabung, klik Kirim Email.</p>',
		),
		array(
			'question' => 'Bagaimana cara melihat laporan kehadiran karyawan di Kyrim Dashboard?',
			'answer'   => '<p>Buka Karyawan &gt; Kehadiran untuk data kehadiran per karyawan dengan filter rentang tanggal dan pencarian nama.</p>',
		),
		array(
			'question' => 'Filter apa saja yang tersedia di laporan Kehadiran?',
			'answer'   => '<p>Filter: pemilihan tanggal tunggal atau rentang, pencarian berbasis nama. Default menampilkan tanggal hari ini.</p>',
		),
		array(
			'question' => 'Bagaimana cara export laporan Kehadiran ke Excel?',
			'answer'   => '<p>Klik tombol Ekspor di kanan atas. File diunduh sebagai Excel (.xlsx) dengan semua kolom tabel dan data filter/rentang saat ini.</p>',
		),
		array(
			'question' => 'Di mana saya bisa melihat laporan cuti karyawan?',
			'answer'   => '<p>Buka Karyawan &gt; Manajemen Cuti.</p>',
		),
		array(
			'question' => 'Data apa saja yang tampil di tabel Laporan Cuti?',
			'answer'   => '<p>Tabel menampilkan: Status Karyawan, Nama, Tanggal Pengajuan, Tanggal Cuti, Jenis Cuti, Status, Manager (PIC Persetujuan), Departemen, Jabatan, Level Jabatan.</p>',
		),
		array(
			'question' => 'Filter apa saja yang tersedia di laporan Cuti Kerja?',
			'answer'   => '<p>Filter: pemilihan bulan (tunggal), pencarian nama. Keduanya dapat digunakan bersamaan.</p>',
		),
	),

	'Platform Karyawan' => array(
		array(
			'question' => 'Apa itu Platform Karyawan Kyrim?',
			'answer'   => '<p>Platform Karyawan adalah portal layanan mandiri untuk mengakses aktivitas HR (kehadiran, cuti, reimbursement, dokumen) melalui link khusus perusahaan.</p>',
		),
		array(
			'question' => 'Bagaimana cara karyawan login ke Platform Karyawan?',
			'answer'   => '<p>Akses link Platform Karyawan perusahaan, login dengan email karyawan dan kata sandi terdaftar. Login berhasil mengarahkan ke Halaman Utama.</p>',
		),
		array(
			'question' => 'Apakah karyawan bisa mengakses data karyawan lain?',
			'answer'   => '<p>Tidak. Platform membatasi akses data hanya untuk karyawan yang sedang login (tidak ada akses lintas karyawan).</p>',
		),
		array(
			'question' => 'Bagaimana cara keluar dari Platform Karyawan?',
			'answer'   => '<p>Klik tombol Logout di kanan atas. Sistem mengakhiri sesi dan mengarahkan kembali ke halaman login.</p>',
		),
		array(
			'question' => 'Apa saja yang tampil di Homepage Platform Karyawan?',
			'answer'   => '<p>Halaman Utama menampilkan salam dan akses menu untuk Request Absensi, Kehadiran, Cuti, Reimbursement, Dokumen, Riwayat.</p>',
		),
		array(
			'question' => 'Riwayat di Homepage menampilkan data apa saja?',
			'answer'   => '<p>Riwayat menampilkan maksimal 10 pengajuan terbaru (gabungan cuti dan reimbursement).</p>',
		),
		array(
			'question' => 'Bagaimana cara mengajukan Request Absensi?',
			'answer'   => '<p>Kolom yang diperlukan: Tanggal, Jam Masuk, Jam Keluar. Catatan dan lampiran bersifat opsional.</p>',
		),
		array(
			'question' => 'Data apa saja yang wajib diisi saat Request Absensi?',
			'answer'   => '<p>Wajib: Tanggal, Jam Masuk, Jam Keluar.</p>',
		),
		array(
			'question' => 'Apakah Catatan dan Lampiran wajib saat Request Absensi?',
			'answer'   => '<p>Tidak, keduanya opsional dan tidak menghalangi pengajuan jika tidak diisi.</p>',
		),
		array(
			'question' => 'Tanggal apa saja yang bisa dipilih untuk Request Absensi?',
			'answer'   => '<p>Maksimal 7 hari ke belakang (termasuk hari ini).</p>',
		),
		array(
			'question' => 'Kenapa ada tanggal yang tidak bisa dipilih saat Request Absensi?',
			'answer'   => '<p>Tanggal yang dinonaktifkan: lebih dari 7 hari yang lalu, tanggal mendatang, atau tanggal yang sudah pernah diajukan.</p>',
		),
		array(
			'question' => 'Format lampiran apa yang didukung untuk Request Absensi?',
			'answer'   => '<p>Mendukung JPG, PNG, PDF. Dapat dihapus sebelum dikirim.</p>',
		),
		array(
			'question' => 'Apakah saya bisa mengajukan Request Absensi lebih dari satu kali di tanggal yang sama?',
			'answer'   => '<p>Tidak. Sistem mencegah pengajuan duplikat untuk tanggal yang sama.</p>',
		),
		array(
			'question' => 'Bagaimana cara mengajukan cuti di Platform Karyawan?',
			'answer'   => '<p>Buka Cuti, klik Ajukan Cuti, isi formulir, klik Kirim. Pengajuan berhasil mengarahkan kembali ke Halaman Utama dengan notifikasi "Pengajuan cuti berhasil".</p>',
		),
		array(
			'question' => 'Jenis cuti apa yang tersedia?',
			'answer'   => '<p>Default: Cuti Tahunan dan Cuti Sakit.</p>',
		),
		array(
			'question' => 'Apakah bisa mengajukan cuti setengah hari?',
			'answer'   => '<p>Ya. Opsi Sehari Penuh/Setengah Hari muncul untuk pemilihan tanggal tunggal.</p>',
		),
		array(
			'question' => 'Apakah alasan cuti wajib diisi?',
			'answer'   => '<p>Tidak, kolom alasan bersifat opsional.</p>',
		),
		array(
			'question' => 'Apakah saya bisa menambahkan lampiran saat pengajuan cuti?',
			'answer'   => '<p>Ya (opsional). Mendukung PDF, PNG, JPEG, maks 2 MB.</p>',
		),
		array(
			'question' => 'Setelah mengajukan cuti, apakah saya menerima notifikasi email?',
			'answer'   => '<p>Ya. Sistem mengirimkan email konfirmasi dengan tanggal/durasi, jenis cuti, alasan, tombol pelacakan status.</p>',
		),
		array(
			'question' => 'Siapa yang menerima notifikasi untuk menyetujui pengajuan cuti?',
			'answer'   => '<p>Manager/PIC Persetujuan menerima email "menunggu persetujuan" dengan tombol halaman persetujuan.</p>',
		),
		array(
			'question' => 'Bagaimana cara melihat riwayat cuti saya?',
			'answer'   => '<p>Buka menu Cuti untuk melihat daftar permintaan cuti.</p>',
		),
		array(
			'question' => 'Status apa saja yang ada di pengajuan cuti?',
			'answer'   => '<p>Status: Menunggu Persetujuan, Disetujui, Ditolak, Dibatalkan.</p>',
		),
		array(
			'question' => 'Apa saja yang tampil di halaman detail cuti?',
			'answer'   => '<p>Detail menampilkan: status, waktu pengajuan, tanggal/durasi cuti, jenis cuti, riwayat persetujuan, alasan, lampiran (dapat diunduh).</p>',
		),
		array(
			'question' => 'Apakah pengajuan cuti bisa diubah setelah dikirim?',
			'answer'   => '<p>Tidak. Permintaan cuti yang sudah dikirim tidak dapat diubah.</p>',
		),
		array(
			'question' => 'Bagaimana cara membatalkan pengajuan cuti?',
			'answer'   => '<p>Tombol Batalkan muncul hanya jika status Menunggu Persetujuan. Pembatalan mengubah status menjadi Dibatalkan; permintaan tetap terlihat.</p>',
		),
		array(
			'question' => 'Apakah karyawan bisa mengajukan reimbursement dari Platform Karyawan?',
			'answer'   => '<p>Ya. Platform menyediakan akses ke riwayat reimbursement, status/detail, dan pengajuan reimbursement.</p>',
		),
		array(
			'question' => 'Apakah karyawan bisa melihat reimbursement karyawan lain?',
			'answer'   => '<p>Tidak. Karyawan hanya melihat dan mengajukan reimbursement milik mereka sendiri.</p>',
		),
		array(
			'question' => 'Dokumen apa saja yang tersedia di menu Dokumen?',
			'answer'   => '<p>Dokumen yang tersedia: Slip Gaji (bulanan), Bukti Potong Pajak (tahunan).</p>',
		),
		array(
			'question' => 'Bagaimana cara mengunduh Bukti Potong?',
			'answer'   => '<p>Buka Dokumen, pilih tahun, unduh PDF Bukti Potong jika tersedia.</p>',
		),
		array(
			'question' => 'Kenapa Bukti Potong saya tidak muncul di daftar dokumen?',
			'answer'   => '<p>Bukti Potong muncul hanya setelah dibuat secara resmi untuk karyawan dan tahun tersebut.</p>',
		),
		array(
			'question' => 'Apakah link download dokumen bisa dibuka tanpa login?',
			'answer'   => '<p>Tidak. Unduhan memerlukan sesi login yang terautentikasi. Sistem mencegah akses URL publik langsung dan membatasi dokumen untuk karyawan/perusahaan yang sesuai.</p>',
		),
	),
);

// ---------------------------------------------------------------------------
// Run seeder
// ---------------------------------------------------------------------------

$created_categories = 0;
$created_faqs       = 0;
$skipped_faqs       = 0;
$order_counter      = 0;

foreach ( $faq_data as $category_name => $faqs ) {

	// Get or create the category term.
	$term = term_exists( $category_name, 'faq_category' );
	if ( ! $term ) {
		$term = wp_insert_term( $category_name, 'faq_category' );
		if ( is_wp_error( $term ) ) {
			echo 'ERROR creating category "' . esc_html( $category_name ) . '": ' . esc_html( $term->get_error_message() ) . "\n";
			continue;
		}
		$created_categories++;
		echo 'Created category: ' . esc_html( $category_name ) . "\n";
	}

	$term_id = is_array( $term ) ? (int) $term['term_id'] : (int) $term;

	foreach ( $faqs as $faq ) {
		// Skip if a post with this title already exists.
		$existing = get_page_by_title( $faq['question'], OBJECT, 'faq' );
		if ( $existing ) {
			$skipped_faqs++;
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'faq',
				'post_status'  => 'publish',
				'post_title'   => $faq['question'],
				'post_content' => $faq['answer'],
			)
		);

		if ( is_wp_error( $post_id ) ) {
			echo 'ERROR creating FAQ "' . esc_html( $faq['question'] ) . '": ' . esc_html( $post_id->get_error_message() ) . "\n";
			continue;
		}

		wp_set_post_terms( $post_id, array( $term_id ), 'faq_category' );
		update_post_meta( $post_id, 'sfm_show_on_widget', false );
		update_post_meta( $post_id, 'sfm_widget_order', $order_counter++ );

		$created_faqs++;
	}
}

echo "\n=== Seeder complete ===\n";
echo "Categories created : {$created_categories}\n";
echo "FAQs created       : {$created_faqs}\n";
echo "FAQs skipped       : {$skipped_faqs} (already existed)\n";

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	echo '<br><br><strong>Done!</strong> Delete <code>data/seed-faqs.php</code> now.';
}
