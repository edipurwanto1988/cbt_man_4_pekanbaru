CBT MAN 4 PEKANBARU
Dokumentasi Alur & Relasi Database
==================================

ISI DOKUMEN
-----------
1. Gambaran Umum Role & Tipe Ujian
2. Alur Lengkap PRETEST (Mode Kahoot) + Query
3. Alur Lengkap POSTTEST (CBT Standar) + Query
4. Relasi User (Admin, Guru, Siswa)
5. Relasi Kelas / Rombel
6. Relasi Bank Soal, Pertanyaan, Jawaban
7. Relasi PRETEST (Session, Peserta, Timer, Log, Hasil)
8. Relasi POSTTEST (Peserta, Log, Hasil)
9. Relasi Anti-Cheat (Posttest)
10. Relasi Lain (Settings, Jobs, Sessions, Cache)


========================================
1. GAMBARAN UMUM ROLE & TIPE UJIAN
========================================

ROLE USER
---------
1) Admin
   - Tabel: admins
   - Mengelola pengaturan global, tahun ajaran, master, dsb.

2) Guru
   - Tabel: guru
   - Role di kolom: role = 'guru'
   - Tugas utama:
     * Membuat dan mengelola bank soal
     * Membuka sesi PRETEST (mode kahoot)
     * Mengawasi POSTTEST (monitoring, anti-cheat)

3) Siswa
   - Tabel: siswa
   - Primary key: nisn
   - Sebagai peserta PRETEST dan POSTTEST

TIPE UJIAN
----------
Tipe ujian disimpan di tabel: bank_soals.type_test

1) PRETEST
   - type_test = 'pretest'
   - Mode: mirip Kahoot (guru mengendalikan pindah soal)
   - Timer per soal (menggunakan pretest_soal_timer)
   - Jawaban disimpan di pretest_log
   - Rekap nilai disimpan di pretest_hasil

2) POSTTEST
   - type_test = 'posttest'
   - Mode: CBT standar
   - Masing-masing siswa punya countdown sendiri (sisa_detik di posttest_peserta)
   - Jawaban disimpan di posttest_log
   - Rekap nilai disimpan di posttest_hasil
   - Anti-cheat menggunakan posttest_cheat_log dan posttest_cheat_notif


========================================
2. ALUR PRETEST (MODE KAHOOT) + QUERY
========================================

TABEL TERKAIT:
- pretest_session
- pretest_peserta
- pretest_soal_timer
- pretest_log
- pretest_hasil
- bank_soals
- pertanyaan_soals
- jawaban_soals
- siswa
- guru


STEP 1 — Guru Membuka PRETEST
------------------------------
Guru memilih bank soal dengan type_test = 'pretest' dan membuka halaman sesi.

Contoh query pengecekan sesi terakhir:

  SELECT *
  FROM pretest_session
  WHERE bank_soal_id = :bank_soal_id
  ORDER BY id DESC
  LIMIT 1;

Belum ada perubahan pada database (sifatnya hanya read).


STEP 2 — Siswa Join Waiting Room
--------------------------------
Ketika siswa join menggunakan kode sesi/gabungan, data peserta disimpan di pretest_peserta.

Query insert:

  INSERT INTO pretest_peserta (
      session_id,
      bank_soal_id,
      nisn,
      status,
      created_at
  ) VALUES (
      :session_id,
      :bank_soal_id,
      :nisn,
      'waiting',
      NOW()
  );


STEP 3 — Guru Menekan START PRETEST
-----------------------------------

A. Update sesi pretest menjadi running:

  UPDATE pretest_session
  SET start_time = NOW(),
      status = 'running'
  WHERE id = :session_id;

B. Aktifkan semua peserta (status = active):

  UPDATE pretest_peserta
  SET status = 'active',
      updated_at = NOW()
  WHERE session_id = :session_id;

C. Aktifkan soal pertama pada timer:

  UPDATE pretest_soal_timer
  SET status = 'running',
      waktu_mulai = NOW(),
      waktu_berakhir = DATE_ADD(NOW(), INTERVAL :max_time SECOND),
      updated_at = NOW()
  WHERE session_id = :session_id
    AND urutan_soal = 1;


STEP 4 — Siswa Menjawab Soal
-----------------------------
Setiap siswa menjawab soal yang sedang aktif (sesuai urutan di pretest_soal_timer).

Contoh insert ke pretest_log:

  INSERT INTO pretest_log (
      session_id,
      bank_soal_id,
      nisn,
      pertanyaan_id,
      jawaban_id,
      benar,
      waktu_respon,
      poin,
      created_at
  ) VALUES (
      :session_id,
      :bank_soal_id,
      :nisn,
      :pertanyaan_id,
      :jawaban_id,
      :benar,
      :waktu_respon,
      :poin,
      NOW()
  );

- benar        : 1 jika jawaban benar, 0 jika salah.
- waktu_respon: lama waktu menjawab (detik).
- poin        : poin yang didapat (bisa pakai bobot + rumus kecepatan kahoot).


STEP 5 — Guru Pindah Soal atau Timer Habis
------------------------------------------

A. Tandai soal sebelumnya sebagai finished:

  UPDATE pretest_soal_timer
  SET status = 'finished',
      updated_at = NOW()
  WHERE session_id = :session_id
    AND urutan_soal = :current;

B. Aktifkan soal berikutnya:

  UPDATE pretest_soal_timer
  SET status = 'running',
      waktu_mulai = NOW(),
      waktu_berakhir = DATE_ADD(NOW(), INTERVAL :max_time SECOND),
      updated_at = NOW()
  WHERE session_id = :session_id
    AND urutan_soal = :next;


STEP 6 — Sesi PRETEST Selesai
-----------------------------

Setelah soal terakhir selesai, sesi ditandai selesai:

  UPDATE pretest_session
  SET end_time = NOW(),
      status = 'finished',
      updated_at = NOW()
  WHERE id = :session_id;


STEP 7 — Hitung dan Simpan Hasil PRETEST
----------------------------------------

Secara logika:
- Hitung total_benar per siswa per session
- Hitung total_salah
- Hitung total_poin (akumulasi poin per soal)
- Hitung total_waktu_respon (akumulasi waktu_respon)
- Tentukan peringkat (ranking) jika perlu

Query insert ke pretest_hasil:

  INSERT INTO pretest_hasil (
      session_id,
      bank_soal_id,
      nisn,
      total_benar,
      total_salah,
      total_poin,
      total_waktu_respon,
      peringkat,
      created_at
  ) VALUES (
      :session_id,
      :bank_soal_id,
      :nisn,
      :total_benar,
      :total_salah,
      :total_poin,
      :total_waktu_respon,
      :peringkat,
      NOW()
  );


=============================================
3. ALUR POSTTEST (CBT STANDAR) + QUERY
=============================================

TABEL TERKAIT:
- posttest_peserta
- posttest_log
- posttest_hasil
- posttest_cheat_log
- posttest_cheat_notif
- bank_soals
- pertanyaan_soals
- jawaban_soals
- siswa
- guru


STEP 1 — Siswa Mulai POSTTEST
-----------------------------
Saat siswa menekan "Mulai Ujian" untuk bank_soal tertentu (type_test = 'posttest'),
buat record di posttest_peserta:

  INSERT INTO posttest_peserta (
      bank_soal_id,
      nisn,
      start_time,
      end_time,
      sisa_detik,
      status,
      cheat_status,
      created_at
  ) VALUES (
      :bank_soal_id,
      :nisn,
      NOW(),
      NULL,
      :durasi_detik,
      'ongoing',
      'normal',
      NOW()
  );


STEP 2 — Autosave Jawaban
--------------------------
Mekanisme:
- Jika belum ada jawaban untuk pertanyaan tertentu -> INSERT
- Jika sudah ada -> UPDATE

A. Insert jawaban baru:

  INSERT INTO posttest_log (
      peserta_id,
      pertanyaan_id,
      jawaban_id,
      is_ragu,
      created_at
  ) VALUES (
      :peserta_id,
      :pertanyaan_id,
      :jawaban_id,
      :is_ragu,
      NOW()
  );

B. Update jawaban jika sudah pernah menjawab:

  UPDATE posttest_log
  SET jawaban_id = :jawaban_id,
      is_ragu = :is_ragu,
      updated_at = NOW()
  WHERE peserta_id = :peserta_id
    AND pertanyaan_id = :pertanyaan_id;


STEP 3 — Autosave Sisa Waktu (tiap beberapa detik)
--------------------------------------------------

  UPDATE posttest_peserta
  SET sisa_detik = :sisa_detik,
      updated_at = NOW()
  WHERE id = :peserta_id;


STEP 4 — Waktu Habis atau Siswa Klik SUBMIT
-------------------------------------------

  UPDATE posttest_peserta
  SET end_time = NOW(),
      status = 'finished',
      updated_at = NOW()
  WHERE id = :peserta_id;


STEP 5 — Hitung Nilai Akhir POSTTEST
------------------------------------

Secara logika:
- Hitung total_benar, total_salah, total_kosong
- Hitung nilai_akhir (misal: (total_benar / total_soal) * 100)
- Hitung waktu_pengerjaan = selisih start_time dan end_time

  INSERT INTO posttest_hasil (
      bank_soal_id,
      nisn,
      total_benar,
      total_salah,
      total_kosong,
      nilai_akhir,
      waktu_pengerjaan,
      created_at
  ) VALUES (
      :bank_soal_id,
      :nisn,
      :total_benar,
      :total_salah,
      :total_kosong,
      :nilai_akhir,
      :waktu_pengerjaan,
      NOW()
  );


======================================================
4. RELASI USER (ADMIN, GURU, SISWA)
======================================================

4.1 Admin
---------
Tabel: admins
- Tidak memiliki foreign key.
- Role khusus pengelola sistem.

4.2 Guru
--------
Tabel: guru
- Digunakan dalam:
  - rombel.wali_kelas_id
  - (konseptual) bank_soals.created_by
  - (konseptual) bank_soals.pengawas_id
  - posttest_peserta.cheat_unblock_by
  - posttest_cheat_log.unblock_by

4.3 Siswa
---------
Tabel: siswa, PK: nisn
- Dipakai sebagai FK di:
  - rombel_detail.nisn
  - pretest_peserta.nisn
  - pretest_log.nisn
  - pretest_hasil.nisn
  - posttest_peserta.nisn
  - posttest_hasil.nisn
  - posttest_cheat_log.nisn
  - posttest_cheat_notif.nisn


======================================================
5. RELASI KELAS / ROMBEL
======================================================

5.1 Tabel tahun_ajaran
----------------------
Relasi:
- tahun_ajaran (1) — (N) rombel
- tahun_ajaran (1) — (N) bank_soals

5.2 Tabel tingkat_kelas
------------------------
Relasi:
- tingkat_kelas (1) — (N) rombel

5.3 Tabel rombel
----------------
Foreign key:
- tahun_ajaran_id → tahun_ajaran.id
- tingkat_id → tingkat_kelas.id
- wali_kelas_id → guru.id_guru

Relasi:
- guru (1) — (N) rombel
- tahun_ajaran (1) — (N) rombel
- tingkat_kelas (1) — (N) rombel

5.4 Tabel rombel_detail
-----------------------
Foreign key:
- rombel_id → rombel.id
- nisn → siswa.nisn

Relasi:
- rombel (1) — (N) rombel_detail
- siswa (1) — (N) rombel_detail

5.5 Tabel rombel_mapel
----------------------
Foreign key:
- rombel_id → rombel.id
- mata_pelajaran_id → mata_pelajaran.id

Relasi:
- rombel (1) — (N) rombel_mapel — (N) mata_pelajaran


======================================================
6. RELASI BANK SOAL, PERTANYAAN, JAWABAN
======================================================

6.1 Tabel bank_soals
--------------------
Foreign key:
- tahun_ajaran_id → tahun_ajaran.id
- mapel_id → mata_pelajaran.id

Relasi:
- tahun_ajaran (1) — (N) bank_soals
- mata_pelajaran (1) — (N) bank_soals

Secara konsep (tanpa FK):
- guru (1) — (N) bank_soals sebagai pembuat (created_by)
- guru (1) — (N) bank_soals sebagai pengawas (pengawas_id)

6.2 Tabel bank_soal_rombel
--------------------------
Foreign key:
- bank_soal_id → bank_soals.id
- rombel_id → rombel.id

Relasi:
- bank_soals (1) — (N) bank_soal_rombel
- rombel (1) — (N) bank_soal_rombel

Secara konsep:
- bank_soals dan rombel adalah hubungan many-to-many melalui bank_soal_rombel.

6.3 Tabel pertanyaan_soals
--------------------------
Foreign key:
- bank_soal_id → bank_soals.id

Relasi:
- bank_soals (1) — (N) pertanyaan_soals

6.4 Tabel jawaban_soals
-----------------------
Foreign key:
- pertanyaan_id → pertanyaan_soals.id

Relasi:
- pertanyaan_soals (1) — (N) jawaban_soals


======================================================
7. RELASI PRETEST (SESSION, PESERTA, TIMER, LOG, HASIL)
======================================================

Tabel-tabel:
- pretest_session
- pretest_peserta
- pretest_soal_timer
- pretest_log
- pretest_hasil

7.1 Tabel pretest_session
-------------------------
Foreign key:
- bank_soal_id → bank_soals.id
- (logis) guru_id → guru.id_guru (tidak selalu didefinisikan sebagai FK)

Relasi:
- bank_soals (1) — (1) pretest_session (One-to-One)
- guru (1) — (N) pretest_session (konseptual)

Catatan:
Setiap bank_soal hanya dapat memiliki SATU sesi pretest aktif pada suatu waktu.
Ini untuk memastikan bahwa tidak ada sesi pretest yang tumpang tindih untuk bank soal yang sama.

7.2 Tabel pretest_peserta
-------------------------
Foreign key:
- session_id → pretest_session.id
- bank_soal_id → bank_soals.id
- nisn → siswa.nisn

Relasi:
- pretest_session (1) — (N) pretest_peserta
- bank_soals (1) — (N) pretest_peserta
- siswa (1) — (N) pretest_peserta

7.3 Tabel pretest_soal_timer
----------------------------
Foreign key:
- session_id → pretest_session.id
- pertanyaan_id → pertanyaan_soals.id

Relasi:
- pretest_session (1) — (N) pretest_soal_timer
- pertanyaan_soals (1) — (N) pretest_soal_timer

7.4 Tabel pretest_log
---------------------
Foreign key:
- session_id → pretest_session.id
- bank_soal_id → bank_soals.id
- nisn → siswa.nisn
- pertanyaan_id → pertanyaan_soals.id
- jawaban_id → jawaban_soals.id

Relasi:
- pretest_session (1) — (N) pretest_log
- bank_soals (1) — (N) pretest_log
- siswa (1) — (N) pretest_log
- pertanyaan_soals (1) — (N) pretest_log
- jawaban_soals (1) — (N) pretest_log

7.5 Tabel pretest_hasil
-----------------------
Foreign key:
- session_id → pretest_session.id
- bank_soal_id → bank_soals.id
- nisn → siswa.nisn

Relasi:
- pretest_session (1) — (N) pretest_hasil
- bank_soals (1) — (N) pretest_hasil
- siswa (1) — (N) pretest_hasil


======================================================
8. RELASI POSTTEST (PESERTA, LOG, HASIL)
======================================================

Tabel-tabel:
- posttest_peserta
- posttest_log
- posttest_hasil

8.1 Tabel posttest_peserta
--------------------------
Foreign key:
- bank_soal_id → bank_soals.id
- nisn → siswa.nisn
- cheat_unblock_by → guru.id_guru (nullable)

Relasi:
- bank_soals (1) — (N) posttest_peserta
- siswa (1) — (N) posttest_peserta
- guru (1) — (N) posttest_peserta (hanya untuk unblock)

8.2 Tabel posttest_log
----------------------
Foreign key:
- peserta_id → posttest_peserta.id
- pertanyaan_id → pertanyaan_soals.id
- jawaban_id → jawaban_soals.id

Relasi:
- posttest_peserta (1) — (N) posttest_log
- pertanyaan_soals (1) — (N) posttest_log
- jawaban_soals (1) — (N) posttest_log

8.3 Tabel posttest_hasil
------------------------
Foreign key:
- bank_soal_id → bank_soals.id
- nisn → siswa.nisn

Relasi:
- bank_soals (1) — (N) posttest_hasil
- siswa (1) — (N) posttest_hasil


======================================================
9. RELASI ANTI-CHEAT (KHUSUS POSTTEST)
======================================================

Tabel-tabel:
- posttest_cheat_log
- posttest_cheat_notif

9.1 Tabel posttest_cheat_log
----------------------------
Foreign key:
- peserta_id → posttest_peserta.id
- bank_soal_id → bank_soals.id
- nisn → siswa.nisn
- unblock_by → guru.id_guru (nullable)

Relasi:
- posttest_peserta (1) — (N) posttest_cheat_log
- bank_soals (1) — (N) posttest_cheat_log
- siswa (1) — (N) posttest_cheat_log
- guru (1) — (N) posttest_cheat_log (sebagai pihak yang membuka blokir)

9.2 Tabel posttest_cheat_notif
------------------------------
Foreign key:
- peserta_id → posttest_peserta.id
- bank_soal_id → bank_soals.id
- nisn → siswa.nisn

Relasi:
- posttest_peserta (1) — (N) posttest_cheat_notif
- bank_soals (1) — (N) posttest_cheat_notif
- siswa (1) — (N) posttest_cheat_notif


======================================================
10. RELASI LAIN (SETTINGS, JOBS, SESSIONS, CACHE)
======================================================

10.1 Tabel settings
-------------------
- Tidak memiliki foreign key.
- Menyimpan konfigurasi aplikasi:
  - Instruksi Pretest
  - Instruksi Posttest
  - Setelan nama institusi, SEO, dll.

10.2 Tabel sessions
-------------------
- Menyimpan data sesi login (Laravel).
- Tidak memiliki foreign key eksplisit, hanya user_id opsional.

10.3 Tabel cache, cache_locks
-----------------------------
- Menyimpan cache Laravel.
- Tidak berelasi dengan tabel CBT.

10.4 Tabel jobs, job_batches, failed_jobs
----------------------------------------
- Digunakan untuk queue Laravel (background jobs).
- Tidak berelasi langsung dengan tabel CBT.


======================================================
RINGKASAN AKHIR (VERSI SINGKAT RELASI)
======================================================

• tahun_ajaran → rombel → rombel_detail → siswa
• tingkat_kelas → rombel
• guru → rombel (wali kelas)

• bank_soals → pertanyaan_soals → jawaban_soals
• bank_soals → bank_soal_rombel → rombel

• bank_soals → pretest_session → pretest_peserta → siswa
• pretest_session → pretest_soal_timer
• pretest_session → pretest_log
• pretest_session → pretest_hasil

• bank_soals → posttest_peserta → siswa
• posttest_peserta → posttest_log → pertanyaan_soals, jawaban_soals
• posttest_peserta → posttest_hasil

• posttest_peserta → posttest_cheat_log → guru
• posttest_peserta → posttest_cheat_notif

Dokumen ini dapat digunakan sebagai:
- Referensi analisa sistem CBT
- Bahan pembuatan ERD
- Panduan implementasi relasi di Laravel (Model, Migration, Relationship)
- Dokumentasi resmi untuk sekolah / madrasah.

SELESAI.
