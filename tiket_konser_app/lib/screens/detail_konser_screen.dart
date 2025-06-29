import 'package:cloud_firestore/cloud_firestore.dart';
import 'package:firebase_auth/firebase_auth.dart';
import 'package:flutter/material.dart';
import 'package:intl/intl.dart';

class DetailKonserScreen extends StatefulWidget {
  final String konserId;
  const DetailKonserScreen({super.key, required this.konserId});

  @override
  State<DetailKonserScreen> createState() => _DetailKonserScreenState();
}

class _DetailKonserScreenState extends State<DetailKonserScreen> {
  int _jumlahTiket = 1;
  bool _isLoading = false;
  late Future<DocumentSnapshot> _konserFuture;

  @override
  void initState() {
    super.initState();
    _konserFuture = FirebaseFirestore.instance
        .collection('konser')
        .doc(widget.konserId)
        .get();
  }

  void _tambahTiket() {
    setState(() {
      _jumlahTiket++;
    });
  }

  void _kurangiTiket() {
    if (_jumlahTiket > 1) {
      setState(() {
        _jumlahTiket--;
      });
    }
  }

  Future<void> _pesanTiket(DocumentSnapshot konserDoc) async {
    setState(() {
      _isLoading = true;
    });

    final User? pengguna = FirebaseAuth.instance.currentUser;
    if (pengguna == null) {
      if (!context.mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Anda harus login untuk memesan tiket.')),
      );
      setState(() {
        _isLoading = false;
      });
      return;
    }

    final dataKonser = konserDoc.data() as Map<String, dynamic>;
    final int stokSaatIni = dataKonser['stokTiket'];
    final int hargaTiket = dataKonser['hargaTiket'];

    if (stokSaatIni < _jumlahTiket) {
      if (!context.mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Maaf, stok tiket tidak mencukupi.')),
      );
      setState(() {
        _isLoading = false;
      });
      return;
    }

    try {
      await FirebaseFirestore.instance.runTransaction((transaction) async {
        final DocumentReference konserRef = FirebaseFirestore.instance
            .collection('konser')
            .doc(widget.konserId);
        final DocumentSnapshot snapshot = await transaction.get(konserRef);
        final int stokTerbaru =
            (snapshot.data() as Map<String, dynamic>)['stokTiket'];

        if (stokTerbaru < _jumlahTiket) {
          throw Exception('Stok tiket tidak mencukupi.');
        }

        transaction.update(konserRef, {
          'stokTiket': stokTerbaru - _jumlahTiket,
        });

        final DocumentReference pesananRef = FirebaseFirestore.instance
            .collection('pesanan')
            .doc();
        transaction.set(pesananRef, {
          'idPengguna': pengguna.uid,
          'idKonser': widget.konserId,
          'namaKonser': dataKonser['namaKonser'],
          'namaArtis': dataKonser['namaArtis'],
          'jumlahTiket': _jumlahTiket,
          'totalHarga': hargaTiket * _jumlahTiket,
          'tanggalPemesanan': Timestamp.now(),
          'statusPemesanan': 'pending_payment',
        });
      });

      if (!context.mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Pemesanan tiket berhasil! Lanjutkan ke pembayaran.'),
          backgroundColor: Colors.green,
        ),
      );
      Navigator.of(context).pop();
    } catch (e) {
      if (!context.mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Gagal memesan tiket: ${e.toString()}'),
          backgroundColor: Colors.red,
        ),
      );
    } finally {
      if (mounted) {
        setState(() {
          _isLoading = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Detail Konser')),
      body: FutureBuilder<DocumentSnapshot>(
        future: _konserFuture,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          }
          if (snapshot.hasError) {
            return Center(child: Text('Error: ${snapshot.error}'));
          }
          if (!snapshot.hasData || !snapshot.data!.exists) {
            return const Center(child: Text('Konser tidak ditemukan.'));
          }

          final data = snapshot.data!.data() as Map<String, dynamic>;
          final formatCurrency = NumberFormat.currency(
            locale: 'id_ID',
            symbol: 'Rp ',
            decimalDigits: 0,
          );
          final Timestamp timestamp = data['tanggal'] as Timestamp;
          final String tanggalFormatted = DateFormat(
            'EEEE, d MMMM yyyy',
            'id_ID',
          ).format(timestamp.toDate());

          return SingleChildScrollView(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Image.network(
                  data['urlGambar'],
                  width: double.infinity,
                  height: 250,
                  fit: BoxFit.cover,
                ),
                Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        data['namaKonser'],
                        style: Theme.of(context).textTheme.headlineMedium
                            ?.copyWith(fontWeight: FontWeight.bold),
                      ),
                      const SizedBox(height: 8),
                      Text(
                        'oleh ${data['namaArtis']}',
                        style: Theme.of(context).textTheme.titleLarge?.copyWith(
                          color: Colors.grey[700],
                        ),
                      ),
                      const SizedBox(height: 16),
                      const Divider(),
                      const SizedBox(height: 16),
                      Row(
                        children: [
                          const Icon(
                            Icons.calendar_today,
                            size: 20,
                            color: Colors.grey,
                          ),
                          const SizedBox(width: 12),
                          Text(
                            tanggalFormatted,
                            style: Theme.of(context).textTheme.titleMedium,
                          ),
                        ],
                      ),
                      const SizedBox(height: 12),
                      Row(
                        children: [
                          const Icon(
                            Icons.location_on,
                            size: 20,
                            color: Colors.grey,
                          ),
                          const SizedBox(width: 12),
                          Expanded(
                            child: Text(
                              data['lokasi'],
                              style: Theme.of(context).textTheme.titleMedium,
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 12),
                      Row(
                        children: [
                          const Icon(
                            Icons.confirmation_number,
                            size: 20,
                            color: Colors.grey,
                          ),
                          const SizedBox(width: 12),
                          Text(
                            '${data['stokTiket']} tiket tersisa',
                            style: Theme.of(context).textTheme.titleMedium,
                          ),
                        ],
                      ),
                      const SizedBox(height: 24),
                      Container(
                        padding: const EdgeInsets.all(16),
                        decoration: BoxDecoration(
                          color: Colors.blue.withOpacity(0.05),
                          borderRadius: BorderRadius.circular(12),
                          border: Border.all(
                            color: Colors.blue.withOpacity(0.2),
                          ),
                        ),
                        child: Column(
                          children: [
                            Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                Text(
                                  'Jumlah Tiket:',
                                  style: Theme.of(context).textTheme.titleLarge,
                                ),
                                Row(
                                  children: [
                                    IconButton.filled(
                                      icon: const Icon(Icons.remove),
                                      onPressed: _kurangiTiket,
                                      style: IconButton.styleFrom(
                                        backgroundColor: Colors.blue.shade200,
                                      ),
                                    ),
                                    SizedBox(
                                      width: 16,
                                      child: Center(
                                        child: Text(
                                          '$_jumlahTiket',
                                          style: Theme.of(
                                            context,
                                          ).textTheme.titleLarge,
                                        ),
                                      ),
                                    ),
                                    IconButton.filled(
                                      icon: const Icon(Icons.add),
                                      onPressed: _tambahTiket,
                                    ),
                                  ],
                                ),
                              ],
                            ),
                            const SizedBox(height: 16),
                            Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                Text(
                                  'Total Harga:',
                                  style: Theme.of(context).textTheme.titleLarge,
                                ),
                                Text(
                                  formatCurrency.format(
                                    data['hargaTiket'] * _jumlahTiket,
                                  ),
                                  style: Theme.of(context).textTheme.titleLarge
                                      ?.copyWith(fontWeight: FontWeight.bold),
                                ),
                              ],
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          );
        },
      ),
      bottomNavigationBar: FutureBuilder<DocumentSnapshot>(
        future: _konserFuture,
        builder: (context, snapshot) {
          final isButtonEnabled =
              snapshot.hasData && snapshot.data!.exists && !_isLoading;

          return Padding(
            padding: const EdgeInsets.all(16.0),
            child: _isLoading
                ? const Center(child: CircularProgressIndicator())
                : ElevatedButton(
                    onPressed: isButtonEnabled
                        ? () => _pesanTiket(snapshot.data!)
                        : null,
                    style: ElevatedButton.styleFrom(
                      padding: const EdgeInsets.symmetric(vertical: 16),
                      backgroundColor: isButtonEnabled
                          ? Theme.of(context).primaryColor
                          : Colors.grey,
                      foregroundColor: Colors.white,
                    ),
                    child: const Text('Pesan Sekarang'),
                  ),
          );
        },
      ),
    );
  }
}
