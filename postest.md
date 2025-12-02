PENJELASAN POSTTEST_SESSION TIDAK DIPERLUKAN
=============================================

1. PENGANTAR
------------
Dalam struktur database CBT yang Anda gunakan, fungsi sesi ujian posttest tidak lagi memerlukan tabel `posttest_session`. Semua fungsi sesi sudah diambil alih oleh tabel `posttest_peserta`, sehingga tabel session global menjadi tidak relevan.

2. ALASAN MENGAPA POSTTEST_SESSION TIDAK PERLU
----------------------------------------------

A. Semua fungsi session sudah ada di `posttest_peserta`:
- bank_soal_id → menentukan ujian
- nisn → identitas peserta
- start_time → waktu mulai
- end_time → waktu selesai
- sisa_detik → timer siswa
- status → ongoing/finished
- cheat_status → normal/blocked
- cheat_reason → alasan pelanggaran
- cheat_unblock_by → guru yang membuka blokir
- cheat_unblock_at → waktu dibuka blokir

Dengan kolom-kolom ini, setiap siswa sudah memiliki sesi individual di dalam `posttest_peserta`.

B. Semua tabel lain bergantung pada `posttest_peserta`:
- posttest_log memakai peserta_id
- posttest_cheat_log memakai peserta_id
- posttest_cheat_notif memakai peserta_id
- posttest_hasil memakai nisn + bank_soal_id

Jika membuat `posttest_session`, maka struktur seluruh tabel harus berubah dan menjadi lebih rumit tanpa manfaat tambahan.

C. Model Ujian POSTTEST → bukan sesi massal:
Pretest bersifat serentak (mirip Kahoot), sehingga `pretest_session` sangat diperlukan.

Posttest bersifat:
- individual
- timer per siswa
- status per siswa
- blokir per siswa

Model ini **tidak membutuhkan session global**.

D. Tabel session sebelumnya memang sempat dibuat lalu dihapus:
Dalam migrasi Anda terdapat:
- create_posttest_sessions_table
- kemudian dihapus melalui delete_pretest_posttest_tables

Ini menandakan desain Anda sudah disesuaikan dan model session global memang dibuang.

3. KESIMPULAN
-------------
❌ Tabel `posttest_session` TIDAK DIPERLUKAN.

Karena:
1. Semua kebutuhan sesi sudah ditangani oleh `posttest_peserta`.
2. Relasi seluruh modul mengandalkan peserta_id, bukan session_id.
3. Posttest bukan ujian serentak seperti pretest.
4. Penghapusan tabel session dalam migrasi menunjukkan desain final sudah menetapkan hal tersebut.

4. REKOMENDASI
--------------
Jika di kemudian hari ingin menambah fitur:
- multi-sesi posttest
- penjadwalan sesi massal
- pengendalian soal oleh guru

Baru tabel `posttest_session` boleh dipertimbangkan.

Namun untuk sistem saat ini, database Anda sudah optimal tanpa tabel 
