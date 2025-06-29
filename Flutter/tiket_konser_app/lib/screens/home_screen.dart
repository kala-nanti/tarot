// lib/screens/home_screen.dart

import 'package:cloud_firestore/cloud_firestore.dart';
import 'package:firebase_auth/firebase_auth.dart';
import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:tiket_konser_app/screens/detail_konser_screen.dart';
import 'package:tiket_konser_app/screens/tiket_saya_screen.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Daftar Konser"),
        actions: [
          IconButton(
            icon: const Icon(Icons.local_activity),
            tooltip: 'Tiket Saya',
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => const TiketSayaScreen()),
              );
            },
          ),
          // Tombol Logout
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: () async {
              await FirebaseAuth.instance.signOut();
              // AuthGate akan otomatis mengarahkan ke halaman login
            },
          ),
        ],
      ),
      body: StreamBuilder<QuerySnapshot>(
        // Membuat stream untuk mendengarkan perubahan pada koleksi 'konser'
        stream: FirebaseFirestore.instance.collection('konser').snapshots(),
        builder: (context, snapshot) {
          // Jika koneksi masih menunggu, tampilkan loading indicator
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          }

          // Jika terjadi error, tampilkan pesan error
          if (snapshot.hasError) {
            return Center(child: Text('Terjadi kesalahan: ${snapshot.error}'));
          }

          // Jika tidak ada data, tampilkan pesan
          if (!snapshot.hasData || snapshot.data!.docs.isEmpty) {
            return const Center(child: Text('Belum ada konser yang tersedia.'));
          }

          // Jika data tersedia, bangun daftar konser
          final konserDocs = snapshot.data!.docs;

          return ListView.builder(
            itemCount: konserDocs.length,
            itemBuilder: (context, index) {
              final data = konserDocs[index].data() as Map<String, dynamic>;
              final konserId = konserDocs[index].id; // Ambil ID unik dokumen

              // Mengambil data tanggal dari Firestore dan memformatnya
              final Timestamp timestamp = data['tanggal'] as Timestamp;
              final DateTime tanggalKonser = timestamp.toDate();
              final String tanggalFormatted = DateFormat(
                'd MMMM yyyy',
                'id_ID',
              ).format(tanggalKonser);

              // Mengambil harga dan memformatnya sebagai mata uang
              final int harga = data['hargaTiket'] as int;
              final formatCurrency = NumberFormat.currency(
                locale: 'id_ID',
                symbol: 'Rp ',
                decimalDigits: 0,
              );
              final String hargaFormatted = formatCurrency.format(harga);

              // Widget InkWell membuat Card bisa di-klik
              return InkWell(
                onTap: () {
                  // Navigasi ke halaman detail dengan mengirimkan ID konser
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) =>
                          DetailKonserScreen(konserId: konserId),
                    ),
                  );
                },
                child: Card(
                  margin: const EdgeInsets.symmetric(
                    horizontal: 16,
                    vertical: 8,
                  ),
                  elevation: 4,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      // Gambar Konser
                      ClipRRect(
                        borderRadius: const BorderRadius.only(
                          topLeft: Radius.circular(12),
                          topRight: Radius.circular(12),
                        ),
                        child: Image.network(
                          data['urlGambar'] ??
                              'https://via.placeholder.com/400x200',
                          height: 200,
                          width: double.infinity,
                          fit: BoxFit.cover,
                          errorBuilder: (context, error, stackTrace) {
                            return Container(
                              height: 200,
                              color: Colors.grey[200],
                              child: Icon(
                                Icons.broken_image,
                                color: Colors.grey,
                                size: 50,
                              ),
                            );
                          },
                        ),
                      ),
                      Padding(
                        padding: const EdgeInsets.all(16.0),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            // Nama Konser dan Artis
                            Text(
                              data['namaKonser'] ??
                                  'Nama Konser Tidak Tersedia',
                              style: Theme.of(context).textTheme.headlineSmall
                                  ?.copyWith(fontWeight: FontWeight.bold),
                            ),
                            const SizedBox(height: 4),
                            Text(
                              data['namaArtis'] ?? 'Nama Artis',
                              style: Theme.of(context).textTheme.titleMedium
                                  ?.copyWith(color: Colors.grey[700]),
                            ),
                            const SizedBox(height: 12),
                            // Info Tanggal dan Lokasi
                            Row(
                              children: [
                                Icon(
                                  Icons.calendar_today,
                                  size: 16,
                                  color: Colors.grey[600],
                                ),
                                const SizedBox(width: 8),
                                Text(tanggalFormatted),
                              ],
                            ),
                            const SizedBox(height: 8),
                            Row(
                              children: [
                                Icon(
                                  Icons.location_on,
                                  size: 16,
                                  color: Colors.grey[600],
                                ),
                                const SizedBox(width: 8),
                                Expanded(
                                  child: Text(
                                    data['lokasi'] ?? 'Lokasi Tidak Tersedia',
                                  ),
                                ),
                              ],
                            ),
                            const SizedBox(height: 16),
                            // Harga Tiket
                            Align(
                              alignment: Alignment.centerRight,
                              child: Text(
                                hargaFormatted,
                                style: Theme.of(context).textTheme.titleLarge
                                    ?.copyWith(
                                      color: Theme.of(context).primaryColor,
                                      fontWeight: FontWeight.bold,
                                    ),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              );
            },
          );
        },
      ),
    );
  }
}
