// lib/screens/tiket_saya_screen.dart

import 'package:cloud_firestore/cloud_firestore.dart';
import 'package:firebase_auth/firebase_auth.dart';
import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:tiket_konser_app/screens/pembayaran_screen.dart';

class TiketSayaScreen extends StatelessWidget {
  const TiketSayaScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final User? currentUser = FirebaseAuth.instance.currentUser;

    // Jika user tidak login, tampilkan pesan
    if (currentUser == null) {
      return Scaffold(
        appBar: AppBar(title: const Text('Tiket Saya')),
        body: const Center(
          child: Text('Silakan login untuk melihat tiket Anda.'),
        ),
      );
    }

    return Scaffold(
      appBar: AppBar(
        title: const Text('Tiket Saya'),
      ),
      body: StreamBuilder<QuerySnapshot>(
        // Query untuk mengambil pesanan milik user yang sedang login
        stream: FirebaseFirestore.instance
            .collection('pesanan')
            .where('idPengguna', isEqualTo: currentUser.uid)
            .orderBy('tanggalPemesanan', descending: true) // Urutkan dari terbaru
            .snapshots(),
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          }
          if (snapshot.hasError) {
            return Center(child: Text('Error: ${snapshot.error}'));
          }
          if (!snapshot.hasData || snapshot.data!.docs.isEmpty) {
            return const Center(child: Text('Anda belum memiliki pesanan tiket.'));
          }

          final pesananDocs = snapshot.data!.docs;

          return ListView.builder(
            itemCount: pesananDocs.length,
            itemBuilder: (context, index) {
              final data = pesananDocs[index].data() as Map<String, dynamic>;
              final pesananId = pesananDocs[index].id;
              final formatCurrency = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp ', decimalDigits: 0);

              return Card(
                margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                child: ListTile(
                  title: Text(data['namaKonser'], style: const TextStyle(fontWeight: FontWeight.bold)),
                  subtitle: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text('${data['jumlahTiket']} Tiket'),
                      Text(formatCurrency.format(data['totalHarga'])),
                    ],
                  ),
                  trailing: _buildStatusWidget(context, data['statusPemesanan'], pesananId),
                ),
              );
            },
          );
        },
      ),
    );
  }

  // Widget helper untuk menampilkan status dan tombol
  Widget _buildStatusWidget(BuildContext context, String status, String pesananId) {
    switch (status) {
      case 'pending_payment':
        return ElevatedButton(
          onPressed: () {
            Navigator.push(context, MaterialPageRoute(builder: (_) => PembayaranScreen(pesananId: pesananId)));
          },
          style: ElevatedButton.styleFrom(backgroundColor: Colors.orange),
          child: const Text('Bayar'),
        );
      case 'paid':
        return const Chip(
          label: Text('Lunas'),
          backgroundColor: Colors.green,
          labelStyle: TextStyle(color: Colors.white),
        );
      default:
        return Text(status);
    }
  }
}